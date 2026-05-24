<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\QuestionBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionBankController extends Controller
{
    /**
     * Display a listing of questions across all banks.
     */
    public function index(Request $request)
    {
        // Get bank metadata from database
        $bankConfig = QuestionBank::select('bank_group', 'bank_name', 'bank_icon', 'bank_description')
            ->groupBy('bank_group', 'bank_name', 'bank_icon', 'bank_description')
            ->orderBy('bank_group')
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

        // Get question counts per bank
        $bankCounts = QuestionBank::select('bank_group', DB::raw('COUNT(*) as count'))
            ->groupBy('bank_group')
            ->pluck('count', 'bank_group');

        $currentBank = $request->get('bank', array_key_first($bankConfig) ?? 1);

        $query = QuestionBank::where('bank_group', $currentBank);

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

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
            'bank_group'    => 'required|integer|min:1',
            'level'         => 'required|in:Easy,Medium,Hard',
            'soal_text'     => 'required|string',
            'tipe'          => 'required|in:multiple_choice,short_answer',
            'pilihan_a'     => 'nullable|required_if:tipe,multiple_choice|string',
            'pilihan_b'     => 'nullable|required_if:tipe,multiple_choice|string',
            'pilihan_c'     => 'nullable|required_if:tipe,multiple_choice|string',
            'pilihan_d'     => 'nullable|required_if:tipe,multiple_choice|string',
            'jawaban_benar' => 'required|string',
            'bobot_xp'      => 'required|integer|min:1',
        ]);

        // Inherit metadata from existing bank
        $existingBank = QuestionBank::where('bank_group', $validated['bank_group'])->first();
        if ($existingBank) {
            $validated['bank_name']        = $existingBank->bank_name;
            $validated['bank_icon']        = $existingBank->bank_icon;
            $validated['bank_description'] = $existingBank->bank_description;
        }

        $validated['created_by'] = auth()->id();

        QuestionBank::create($validated);

        return redirect()->route('dosen.questions.index', ['bank' => $validated['bank_group']])
            ->with('success', 'Soal berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(QuestionBank $question)
    {
        return view('dosen.questions.edit', compact('question'));
    }

    /**
     * Update the specified question.
     */
    public function update(Request $request, QuestionBank $question)
    {
        $validated = $request->validate([
            'bank_group'    => 'sometimes|integer|min:1',
            'level'         => 'required|in:Easy,Medium,Hard',
            'soal_text'     => 'required|string',
            'tipe'          => 'required|in:multiple_choice,short_answer',
            'pilihan_a'     => 'nullable|required_if:tipe,multiple_choice|string',
            'pilihan_b'     => 'nullable|required_if:tipe,multiple_choice|string',
            'pilihan_c'     => 'nullable|required_if:tipe,multiple_choice|string',
            'pilihan_d'     => 'nullable|required_if:tipe,multiple_choice|string',
            'jawaban_benar' => 'required|string',
            'bobot_xp'      => 'required|integer|min:1',
        ]);

        $question->update($validated);

        $bankGroup = $validated['bank_group'] ?? $question->bank_group;

        return redirect()->route('dosen.questions.index', ['bank' => $bankGroup])
            ->with('success', 'Soal berhasil diupdate!');
    }

    /**
     * Remove the specified question.
     */
    public function destroy(QuestionBank $question)
    {
        $bankGroup = $question->bank_group;
        $question->delete();

        return redirect()->route('dosen.questions.index', ['bank' => $bankGroup])
            ->with('success', 'Soal berhasil dihapus!');
    }

    /**
     * Show form to create a new question bank with multiple questions inline.
     */
    public function createBank()
    {
        return view('dosen.questions.bank-create');
    }

    /**
     * Store a brand new question bank with multiple questions.
     */
    public function storeBank(Request $request)
    {
        $validated = $request->validate([
            'bank_name'        => 'required|string|max:255',
            'bank_description' => 'nullable|string',
            'questions'                       => 'required|array|min:1',
            'questions.*.level'               => 'required|in:Easy,Medium,Hard',
            'questions.*.soal_text'           => 'required|string',
            'questions.*.tipe'                => 'required|in:multiple_choice,short_answer',
            'questions.*.pilihan_a'           => 'nullable|required_if:questions.*.tipe,multiple_choice|string',
            'questions.*.pilihan_b'           => 'nullable|required_if:questions.*.tipe,multiple_choice|string',
            'questions.*.pilihan_c'           => 'nullable|required_if:questions.*.tipe,multiple_choice|string',
            'questions.*.pilihan_d'           => 'nullable|required_if:questions.*.tipe,multiple_choice|string',
            'questions.*.jawaban_benar'       => 'required|string',
            'questions.*.bobot_xp'            => 'required|integer|min:1',
        ]);

        // Pastikan nama bank unik (case-insensitive)
        $exists = QuestionBank::whereRaw('LOWER(bank_name) = ?', [strtolower($validated['bank_name'])])->exists();
        if ($exists) {
            return back()->withInput()->withErrors([
                'bank_name' => 'Nama bank soal sudah dipakai. Gunakan nama lain.',
            ]);
        }

        $newBankGroup = ((int) (QuestionBank::max('bank_group') ?? 0)) + 1;
        $userId = auth()->id();

        DB::transaction(function () use ($validated, $newBankGroup, $userId) {
            foreach ($validated['questions'] as $q) {
                QuestionBank::create([
                    'bank_group'       => $newBankGroup,
                    'bank_name'        => $validated['bank_name'],
                    'bank_icon'        => 'quiz' ?? null,
                    'bank_description' => $validated['bank_description'] ?? null,
                    'level'            => $q['level'],
                    'soal_text'        => $q['soal_text'],
                    'tipe'             => $q['tipe'],
                    'pilihan_a'        => $q['pilihan_a'] ?? null,
                    'pilihan_b'        => $q['pilihan_b'] ?? null,
                    'pilihan_c'        => $q['pilihan_c'] ?? null,
                    'pilihan_d'        => $q['pilihan_d'] ?? null,
                    'jawaban_benar'    => $q['jawaban_benar'],
                    'bobot_xp'         => $q['bobot_xp'],
                    'created_by'       => $userId,
                ]);
            }
        });

        return redirect()->route('dosen.questions.index', ['bank' => $newBankGroup])
            ->with('success', 'Bank soal "' . $validated['bank_name'] . '" berhasil dibuat dengan ' . count($validated['questions']) . ' soal.');
    }

    /**
     * Delete an entire question bank (all questions in that bank_group).
     * Refuse if the bank is still referenced by any solo_raid event.
     */
    public function destroyBank(int $bank)
    {
        $exists = QuestionBank::where('bank_group', $bank)->exists();
        if (!$exists) {
            return back()->with('error', 'Bank soal tidak ditemukan.');
        }

        $bankName = QuestionBank::where('bank_group', $bank)->value('bank_name') ?: ('Bank #' . $bank);

        $usedBy = \App\Models\SoloRaid::where('question_bank_id', $bank)
            ->select('id', 'nama')
            ->limit(5)
            ->get();

        if ($usedBy->isNotEmpty()) {
            $names = $usedBy->pluck('nama')->implode(', ');
            return back()->with('error',
                "Bank soal \"$bankName\" masih dipakai oleh event: $names. Hapus atau ubah event tersebut terlebih dulu."
            );
        }

        $deleted = DB::transaction(function () use ($bank) {
            return QuestionBank::where('bank_group', $bank)->delete();
        });

        return redirect()->route('dosen.questions.index')
            ->with('success', "Bank soal \"$bankName\" beserta $deleted soal di dalamnya berhasil dihapus.");
    }

    /**
     * Download CSV template for bulk upload.
     */
    public function downloadTemplate()
    {
        $headers = [
            'level', 'soal_text', 'tipe',
            'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d',
            'jawaban_benar', 'bobot_xp',
        ];

        $samples = [
            ['Easy', 'Apa kepanjangan dari PHP?', 'multiple_choice', 'PHP Hypertext Preprocessor', 'Personal Home Page', 'Private Hosting Page', 'Pre Hyper Process', 'PHP Hypertext Preprocessor', 10],
            ['Medium', 'Tag pembuka skrip PHP yang benar adalah?', 'short_answer', '', '', '', '', '<?php', 15],
            ['Hard', 'Manakah yang termasuk superglobal di PHP?', 'multiple_choice', '$_VARS', '$_SERVER', '$_GLOBAL', '$_THIS', '$_SERVER', 20],
        ];

        $callback = function () use ($headers, $samples) {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF"); // UTF-8 BOM (Excel)
            fputcsv($out, $headers);
            foreach ($samples as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, 'question_bank_template.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Handle bulk upload of questions via CSV (one upload = one bank).
     */
    public function bulkUpload(Request $request)
    {
        $request->validate([
            'file'                 => 'required|file|mimes:csv,txt|max:5120',
            'target_bank'          => 'required|string',
            'new_bank_name'        => 'nullable|required_if:target_bank,new|string|max:255',
            'new_bank_description' => 'nullable|string',
        ]);

        try {
            $rows = $this->parseCsv($request->file('file')->getRealPath());

            if (empty($rows)) {
                return back()->withErrors(['file' => 'File CSV kosong atau tidak valid.']);
            }

            if ($request->input('target_bank') === 'new') {
                $bankName = trim((string) $request->input('new_bank_name'));

                $exists = QuestionBank::whereRaw('LOWER(bank_name) = ?', [strtolower($bankName)])->exists();
                if ($exists) {
                    return back()->withErrors(['new_bank_name' => 'Nama bank soal sudah dipakai. Gunakan nama lain.']);
                }

                $targetBankGroup = ((int) (QuestionBank::max('bank_group') ?? 0)) + 1;
                $bankIcon        = 'quiz';
                $bankDescription = $request->input('new_bank_description');
                $isNewBank       = true;
            } else {
                $targetBankGroup = (int) $request->input('target_bank');
                $existingBank    = QuestionBank::where('bank_group', $targetBankGroup)->first();

                if (!$existingBank) {
                    return back()->withErrors(['target_bank' => 'Bank soal tujuan tidak ditemukan.']);
                }
                $bankName        = $existingBank->bank_name;
                $bankIcon        = $existingBank->bank_icon ?: 'quiz';
                $bankDescription = $existingBank->bank_description;
                $isNewBank       = false;
            }

            $count   = 0;
            $skipped = [];
            $userId  = auth()->id();

            DB::transaction(function () use ($rows, $targetBankGroup, $bankName, $bankIcon, $bankDescription, $userId, &$count, &$skipped) {
                foreach ($rows as $i => $row) {
                    $level        = trim((string) ($row['level'] ?? ''));
                    $soal         = trim((string) ($row['soal_text'] ?? ''));
                    $tipe         = trim((string) ($row['tipe'] ?? ''));
                    $jawabanBenar = trim((string) ($row['jawaban_benar'] ?? ''));

                    if ($soal === '' || $jawabanBenar === '') {
                        $skipped[] = ($i + 2);
                        continue;
                    }
                    if (!in_array($level, ['Easy', 'Medium', 'Hard'], true)) {
                        $skipped[] = ($i + 2);
                        continue;
                    }
                    if (!in_array($tipe, ['multiple_choice', 'short_answer'], true)) {
                        $skipped[] = ($i + 2);
                        continue;
                    }

                    QuestionBank::create([
                        'bank_group'       => $targetBankGroup,
                        'bank_name'        => $bankName,
                        'bank_icon'        => $bankIcon,
                        'bank_description' => $bankDescription,
                        'level'            => $level,
                        'soal_text'        => $soal,
                        'tipe'             => $tipe,
                        'pilihan_a'        => trim((string) ($row['pilihan_a'] ?? '')) ?: null,
                        'pilihan_b'        => trim((string) ($row['pilihan_b'] ?? '')) ?: null,
                        'pilihan_c'        => trim((string) ($row['pilihan_c'] ?? '')) ?: null,
                        'pilihan_d'        => trim((string) ($row['pilihan_d'] ?? '')) ?: null,
                        'jawaban_benar'    => $jawabanBenar,
                        'bobot_xp'         => (int) ($row['bobot_xp'] ?? match ($level) {
                            'Easy' => 10, 'Medium' => 15, 'Hard' => 20, default => 10,
                        }),
                        'created_by'       => $userId,
                    ]);
                    $count++;
                }
            });

            $message = "Berhasil mengimpor $count soal";
            $message .= $isNewBank
                ? " ke bank baru \"$bankName\"."
                : " ke bank \"$bankName\".";
            if (!empty($skipped)) {
                $message .= ' Baris dilewati (data tidak valid): ' . implode(', ', $skipped) . '.';
            }

            return redirect()->route('dosen.questions.index', ['bank' => $targetBankGroup])
                ->with('success', $message);

        } catch (\Throwable $e) {
            return back()->withErrors(['file' => 'Gagal memproses file: ' . $e->getMessage()]);
        }
    }

    /**
     * Parse a CSV file into associative rows keyed by header.
     */
    private function parseCsv(string $path): array
    {
        if (!is_file($path)) return [];
        $handle = fopen($path, 'r');
        if (!$handle) return [];

        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return [];
        }
        $headers = array_map(fn ($h) => strtolower(trim((string) $h)), $headers);

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) === 1 && trim((string) $data[0]) === '') {
                continue;
            }
            $data = array_pad($data, count($headers), null);
            $data = array_slice($data, 0, count($headers));
            $rows[] = array_combine($headers, $data);
        }
        fclose($handle);
        return $rows;
    }
}
