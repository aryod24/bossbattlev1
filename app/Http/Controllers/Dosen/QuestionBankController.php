<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\QuestionBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionBankController extends Controller
{
    /**
     * Display a listing of questions (all questions, not just created by this dosen).
     */
    public function index(Request $request)
    {
        // Get bank metadata from database (ALL banks, not filtered by created_by)
        $bankConfig = QuestionBank::select('bank_group', 'bank_name', 'bank_icon', 'bank_description')
            ->groupBy('bank_group', 'bank_name', 'bank_icon', 'bank_description')
            ->get()
            ->keyBy('bank_group')
            ->map(function ($item) {
                return [
                    'name' => $item->bank_name,
                    'icon' => $item->bank_icon ?? 'quiz',
                    'description' => $item->bank_description ?? '',
                ];
            })
            ->toArray();

        // Get question counts per bank (ALL questions)
        $bankCounts = QuestionBank::select('bank_group', \DB::raw('COUNT(*) as count'))
            ->groupBy('bank_group')
            ->pluck('count', 'bank_group');

        // Filter by bank group (default to first available bank or Bank 1)
        $currentBank = $request->get('bank', array_key_first($bankConfig) ?? 1);
        
        $query = QuestionBank::where('bank_group', $currentBank);

        // Filter by level
        if ($request->has('level') && $request->level != '') {
            $query->where('level', $request->level);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where('soal_text', 'like', '%' . $request->search . '%');
        }

        $questions = $query->latest()->paginate(10)->withQueryString();

        return view('dosen.questions.index', compact('questions', 'currentBank', 'bankConfig', 'bankCounts'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create()
    {
        return view('dosen.questions.create');
    }

    /**
     * Store a newly created question.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'soal_text' => 'required|string',
            'tipe_soal' => 'required|in:multiple_choice,short_answer',
            'opsi_a' => 'required_if:tipe_soal,multiple_choice',
            'opsi_b' => 'required_if:tipe_soal,multiple_choice',
            'opsi_c' => 'required_if:tipe_soal,multiple_choice',
            'opsi_d' => 'required_if:tipe_soal,multiple_choice',
            'jawaban_benar' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        $validated['created_by'] = auth()->id();

        QuestionBank::create($validated);

        return redirect()->route('dosen.questions.index')->with('success', 'Soal berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(QuestionBank $question)
    {
        // Ensure only creator can edit
        if ($question->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('dosen.questions.edit', compact('question'));
    }

    /**
     * Update the specified question.
     */
    public function update(Request $request, QuestionBank $question)
    {
        // Ensure only creator can update
        if ($question->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'soal_text' => 'required|string',
            'tipe_soal' => 'required|in:multiple_choice,short_answer',
            'opsi_a' => 'required_if:tipe_soal,multiple_choice',
            'opsi_b' => 'required_if:tipe_soal,multiple_choice',
            'opsi_c' => 'required_if:tipe_soal,multiple_choice',
            'opsi_d' => 'required_if:tipe_soal,multiple_choice',
            'jawaban_benar' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        $question->update($validated);

        return redirect()->route('dosen.questions.index')->with('success', 'Soal berhasil diupdate!');
    }

    /**
     * Remove the specified question.
     */
    public function destroy(QuestionBank $question)
    {
        // Ensure only creator can delete
        if ($question->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $question->delete();

        return redirect()->route('dosen.questions.index')->with('success', 'Soal berhasil dihapus!');
    }

    /**
     * Download template for bulk upload.
     */
    public function downloadTemplate()
    {
        $filepath = storage_path('app/templates/question_template.csv');
        
        if (file_exists($filepath)) {
            return response()->download($filepath);
        }

        // Create template on-the-fly if not exists
        $headers = ['soal_text', 'tipe_soal', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'jawaban_benar', 'difficulty'];
        $sample = ['Apa itu Laravel?', 'multiple_choice', 'PHP Framework', 'Database', 'Editor', 'Language', 'PHP Framework', 'easy'];
        
        $csv = implode(',', $headers) . "\n" . implode(',', $sample);
        
        return response()->streamDownload(function() use ($csv) {
            echo $csv;
        }, 'question_template.csv');
    }

    /**
     * Handle bulk upload of questions.
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $data = array_map('str_getcsv', file($file->getRealPath()));
        $headers = array_shift($data);

        $created = 0;
        foreach ($data as $row) {
            $questionData = array_combine($headers, $row);
            $questionData['created_by'] = auth()->id();
            
            QuestionBank::create($questionData);
            $created++;
        }

        return redirect()->route('dosen.questions.index')
            ->with('success', "$created soal berhasil diimport!");
    }
}
