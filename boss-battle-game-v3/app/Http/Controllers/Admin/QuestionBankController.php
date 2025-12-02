<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionBank;
use Illuminate\Http\Request;

class QuestionBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = QuestionBank::query();

        if ($request->has('level') && $request->level != '') {
            $query->where('level', $request->level);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('soal_text', 'like', '%' . $request->search . '%');
        }

        $questions = $query->latest()->paginate(10);

        return view('admin.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.questions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'level' => 'required|in:Easy,Medium,Hard',
            'soal_text' => 'required|string',
            'tipe' => 'required|in:multiple_choice,short_answer',
            'pilihan_a' => 'nullable|required_if:tipe,multiple_choice|string',
            'pilihan_b' => 'nullable|required_if:tipe,multiple_choice|string',
            'pilihan_c' => 'nullable|required_if:tipe,multiple_choice|string',
            'pilihan_d' => 'nullable|required_if:tipe,multiple_choice|string',
            'jawaban_benar' => 'required|string',
            'bobot_xp' => 'required|integer|min:1',
        ]);

        QuestionBank::create($validated);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuestionBank $question)
    {
        return view('admin.questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuestionBank $question)
    {
        $validated = $request->validate([
            'level' => 'required|in:Easy,Medium,Hard',
            'soal_text' => 'required|string',
            'tipe' => 'required|in:multiple_choice,short_answer',
            'pilihan_a' => 'nullable|required_if:tipe,multiple_choice|string',
            'pilihan_b' => 'nullable|required_if:tipe,multiple_choice|string',
            'pilihan_c' => 'nullable|required_if:tipe,multiple_choice|string',
            'pilihan_d' => 'nullable|required_if:tipe,multiple_choice|string',
            'jawaban_benar' => 'required|string',
            'bobot_xp' => 'required|integer|min:1',
        ]);

        $question->update($validated);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionBank $question)
    {
        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question deleted successfully.');
    }

    /**
     * Download JSON template for bulk upload.
     */
    public function downloadTemplate()
    {
        $template = [
            [
                'level' => 'Easy',
                'soal_text' => 'Contoh Pertanyaan Pilihan Ganda?',
                'tipe' => 'multiple_choice',
                'pilihan_a' => 'Opsi A',
                'pilihan_b' => 'Opsi B',
                'pilihan_c' => 'Opsi C',
                'pilihan_d' => 'Opsi D',
                'jawaban_benar' => 'Opsi A',
                'bobot_xp' => 10
            ],
            [
                'level' => 'Medium',
                'soal_text' => 'Contoh Pertanyaan Isian Singkat?',
                'tipe' => 'short_answer',
                'pilihan_a' => null,
                'pilihan_b' => null,
                'pilihan_c' => null,
                'pilihan_d' => null,
                'jawaban_benar' => 'Jawaban Singkat',
                'bobot_xp' => 20
            ]
        ];

        return response()->json($template, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="question_bank_template.json"',
        ]);
    }

    /**
     * Handle bulk upload of questions via JSON.
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json,txt',
        ]);

        try {
            $jsonContent = file_get_contents($request->file('file')->getRealPath());
            $questions = json_decode($jsonContent, true);

            if (!is_array($questions)) {
                return back()->withErrors(['file' => 'Invalid JSON format.']);
            }

            $count = 0;
            foreach ($questions as $q) {
                // Basic validation for each item
                if (empty($q['level']) || empty($q['soal_text']) || empty($q['tipe']) || empty($q['jawaban_benar'])) {
                    continue; // Skip invalid items
                }

                QuestionBank::create([
                    'level' => $q['level'],
                    'soal_text' => $q['soal_text'],
                    'tipe' => $q['tipe'],
                    'pilihan_a' => $q['pilihan_a'] ?? null,
                    'pilihan_b' => $q['pilihan_b'] ?? null,
                    'pilihan_c' => $q['pilihan_c'] ?? null,
                    'pilihan_d' => $q['pilihan_d'] ?? null,
                    'jawaban_benar' => $q['jawaban_benar'],
                    'bobot_xp' => $q['bobot_xp'] ?? 10,
                ]);
                $count++;
            }

            return redirect()->route('admin.questions.index')
                ->with('success', "Successfully imported $count questions.");

        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Error processing file: ' . $e->getMessage()]);
        }
    }
}
