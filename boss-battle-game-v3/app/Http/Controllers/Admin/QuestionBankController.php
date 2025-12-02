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

        // Get bank configuration and counts
        $bankConfig = config('question_banks.banks', []);
        $bankCounts = QuestionBank::select('bank_group', \DB::raw('COUNT(*) as count'))
            ->groupBy('bank_group')
            ->pluck('count', 'bank_group');

        // Filter by bank group (default to first available bank or Bank 1)
        $currentBank = $request->get('bank', array_key_first($bankConfig) ?? 1);
        $query->where('bank_group', $currentBank);

        // Filter by level
        if ($request->has('level') && $request->level != '') {
            $query->where('level', $request->level);
        }

        // Search by question text
        if ($request->has('search') && $request->search != '') {
            $query->where('soal_text', 'like', '%' . $request->search . '%');
        }

        $questions = $query->latest()->paginate(10)->withQueryString();

        return view('admin.questions.index', compact('questions', 'currentBank', 'bankConfig', 'bankCounts'));
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
            'bank_group' => 'sometimes|integer|in:1,2,3',
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

        // Default to Bank 1 if not provided
        $validated['bank_group'] = $validated['bank_group'] ?? 1;

        QuestionBank::create($validated);

        return redirect()->route('admin.questions.index', ['bank' => $validated['bank_group']])
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
            'bank_group' => 'sometimes|integer|in:1,2,3',
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

        $bankGroup = $validated['bank_group'] ?? $question->bank_group;

        return redirect()->route('admin.questions.index', ['bank' => $bankGroup])
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
            $data = json_decode($jsonContent, true);

            if (!is_array($data)) {
                return back()->withErrors(['file' => 'Invalid JSON format.']);
            }

            // Check if new format with bank metadata or old format (array of questions)
            $bankName = $data['bank_name'] ?? null;
            $bankIcon = $data['bank_icon'] ?? config('question_banks.default_icon');
            $bankDescription = $data['bank_description'] ?? '';
            $questions = $data['questions'] ?? $data; // Support both formats

            // Determine bank_group
            $bankConfig = config('question_banks.banks', []);
            $targetBankGroup = null;

            if ($bankName) {
                // Search for existing bank by name
                foreach ($bankConfig as $bankId => $bank) {
                    if (strcasecmp($bank['name'], $bankName) === 0) {
                        $targetBankGroup = $bankId;
                        break;
                    }
                }

                // If not found, create new bank_group
                if ($targetBankGroup === null) {
                    $maxBankGroup = QuestionBank::max('bank_group') ?? 0;
                    $targetBankGroup = $maxBankGroup + 1;
                    $isNewBank = true;
                } else {
                    $isNewBank = false;
                }
            } else {
                // Old format without bank_name, use Bank 1 as default
                $targetBankGroup = 1;
                $isNewBank = false;
            }

            // Insert questions
            $count = 0;
            foreach ($questions as $q) {
                // Basic validation
                if (empty($q['level']) || empty($q['soal_text']) || empty($q['tipe']) || empty($q['jawaban_benar'])) {
                    continue;
                }

                QuestionBank::create([
                    'bank_group' => $targetBankGroup,
                    'level' => $q['level'],
                    'soal_text' => $q['soal_text'],
                    'tipe' => $q['tipe'],
                    'pilihan_a' => $q['pilihan_a'] ?? null,
                    'pilihan_b' => $q['pilihan_b'] ?? null,
                    'pilihan_c' => $q['pilihan_c'] ?? null,
                    'pilihan_d' => $q['pilihan_d'] ?? null,
                    'jawaban_benar' => $q['jawaban_benar'],
                    'bobot_xp' => $q['bobot_xp'] ?? config("question_banks.default_xp.{$q['level']}", 10),
                ]);
                $count++;
            }

            // Build success message
            $message = "Successfully imported $count questions";
            if ($isNewBank && $bankName) {
                $message .= " to NEW bank: \"$bankName\" (Bank Group $targetBankGroup)";
                $message .= "\n\n⚠️ IMPORTANT: Add this bank to config/question_banks.php:";
                $message .= "\n$targetBankGroup => ['name' => '$bankName', 'icon' => '$bankIcon', 'description' => '$bankDescription']";
            } elseif ($bankName) {
                $message .= " to existing bank: \"$bankName\"";
            }

            return redirect()->route('admin.questions.index', ['bank' => $targetBankGroup])
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Error processing file: ' . $e->getMessage()]);
        }
    }
}
