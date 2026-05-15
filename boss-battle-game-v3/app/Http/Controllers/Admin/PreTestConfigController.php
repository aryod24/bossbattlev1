<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\QuestionBank;

class PreTestConfigController extends Controller
{
    private $configPath = 'pretest_config.json';

    public function edit()
    {
        // Get all unique bank_groups with their names
        $bankGroups = QuestionBank::select('bank_group', 'bank_name')
            ->whereNotNull('bank_group')
            ->distinct()
            ->orderBy('bank_group')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->bank_group => "Bank Group {$item->bank_group}: {$item->bank_name}"];
            });

        // Load current config
        $config = self::getConfig();

        return view('admin.pretest.edit', compact('bankGroups', 'config'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'bank_group' => 'nullable|string',
            'easy_count' => 'required|integer|min:0',
            'medium_count' => 'required|integer|min:0',
            'hard_count' => 'required|integer|min:0',
        ]);

        $config = [
            'bank_group' => $request->bank_group,
            'composition' => [
                'Easy' => (int) $request->easy_count,
                'Medium' => (int) $request->medium_count,
                'Hard' => (int) $request->hard_count,
            ],
            'total_questions' => (int) $request->easy_count + (int) $request->medium_count + (int) $request->hard_count,
        ];

        Storage::disk('local')->put($this->configPath, json_encode($config, JSON_PRETTY_PRINT));

        return redirect()->route('admin.pretest.edit')->with('success', 'Konfigurasi Pre-Test berhasil disimpan!');
    }

    public static function getConfig()
    {
        $path = 'pretest_config.json';
        if (Storage::disk('local')->exists($path)) {
            $data = json_decode(Storage::disk('local')->get($path), true);
            if ($data && isset($data['composition'])) {
                return $data;
            }
        }

        // Default config
        return [
            'bank_group' => null,
            'composition' => [
                'Easy' => 10,
                'Medium' => 10,
                'Hard' => 10,
            ],
            'total_questions' => 30,
        ];
    }
}
