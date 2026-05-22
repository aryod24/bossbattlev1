<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\SessionAnswer;
use App\Models\SessionSolo;
use App\Models\User;
use App\Models\UserBadge;
use App\Services\PreTestService;
use App\Services\SoloBattleService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /** Test accounts that should never appear in a research export. */
    private array $excludedEmails = [
        'usertest@gmail.com',
    ];

    /** Pretest score → level_adaptif (Easy/Medium/Hard). */
    private const ADAPTIVE_RANGES = [
        'Easy'   => [0, 40],
        'Medium' => [41, 70],
        'Hard'   => [71, 100],
    ];

    /** Label kelompok untuk responden yang belum sempat menyelesaikan Pre-Test. */
    private const NO_PRETEST_KEY = 'NoPretest';

    /**
     * Tentukan level_adaptif dari pretest_score user. Mengikuti ambang yang
     * sama dengan PreTestService::PLACEMENT_THRESHOLDS supaya konsisten.
     */
    private function classifyAdaptive(?int $pretestScore): string
    {
        if ($pretestScore === null) {
            return self::NO_PRETEST_KEY;
        }

        foreach (self::ADAPTIVE_RANGES as $label => [$min, $max]) {
            if ($pretestScore >= $min && $pretestScore <= $max) {
                return $label;
            }
        }

        return self::NO_PRETEST_KEY;
    }

    public function index()
    {
        // Hitung responden Pre-Test per kelas (untuk label di dropdown).
        $pretestStats = SessionSolo::query()
            ->where('is_pretest', true)
            ->whereNotNull('waktu_selesai')
            ->join('users', 'users.id', '=', 'session_solo.user_id')
            ->whereNotIn('users.email', $this->excludedEmails)
            ->selectRaw('users.kelas as kelas, COUNT(DISTINCT users.id) as total')
            ->groupBy('users.kelas')
            ->pluck('total', 'kelas');

        // Hitung sesi Boss Battle per kelompok level_adaptif (pretest-derived).
        // Klasifikasi dilakukan di PHP supaya responden tanpa pretest_score
        // tetap masuk ke kelompok "NoPretest" — sama dengan halaman monitoring
        // yang juga tidak menyaring berdasarkan pre-test.
        $bossStats = ['Easy' => 0, 'Medium' => 0, 'Hard' => 0, self::NO_PRETEST_KEY => 0];
        $finishedBossSessions = $this->bossSessionsBaseQuery()->get();
        $countedUsersPerGroup = [
            'Easy' => [], 'Medium' => [], 'Hard' => [], self::NO_PRETEST_KEY => [],
        ];
        foreach ($finishedBossSessions as $session) {
            $group = $this->classifyAdaptive(
                $session->user->pretest_score !== null ? (int) $session->user->pretest_score : null
            );
            // unique by user_id supaya stats menghitung responden, bukan attempt
            if (!isset($countedUsersPerGroup[$group][$session->user_id])) {
                $countedUsersPerGroup[$group][$session->user_id] = true;
                $bossStats[$group] = ($bossStats[$group] ?? 0) + 1;
            }
        }

        // Event multiplayer (opsional, tetap diekspos kalau ada datanya).
        $events = Event::orderBy('created_at', 'desc')->get();

        return view('admin.reports.index', compact('pretestStats', 'bossStats', 'events'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'report_source' => 'required|string',
            // "pretest:all|TI-2D|TI-2E" | "boss:Easy|Medium|Hard" | "event:{id}"
        ]);

        [$type, $id] = explode(':', $request->report_source, 2);

        return match ($type) {
            'pretest' => $this->exportPretest($id),
            'boss'    => $this->exportBossBattleBySection($id),
            'event'   => $this->exportBossBattleEvent((int) $id),
            default   => abort(400, 'Unknown report source'),
        };
    }

    // ===================================================================
    // BOSS BATTLE — auto-grouped by adaptive level
    // ===================================================================

    /**
     * Base query untuk semua sesi Boss Battle yang sudah selesai.
     * TIDAK menyaring berdasarkan pretest_score — kelompok level_adaptif
     * dihitung di PHP supaya konsisten dengan halaman /monitoring yang juga
     * menampilkan semua responden, termasuk yang belum sempat pre-test.
     */
    private function bossSessionsBaseQuery()
    {
        return SessionSolo::query()
            ->with(['user', 'soloRaid'])
            ->whereNotNull('waktu_selesai')
            ->where(function ($q) {
                $q->whereNull('is_pretest')->orWhere('is_pretest', false);
            })
            ->whereHas('soloRaid', function ($q) {
                $q->where('type', 'boss');
            })
            ->whereHas('user', function ($q) {
                $q->whereNotIn('email', $this->excludedEmails);
            });
    }

    private function exportBossBattleBySection(string $adaptiveLevel)
    {
        $adaptiveLevel = $adaptiveLevel === self::NO_PRETEST_KEY
            ? self::NO_PRETEST_KEY
            : ucfirst(strtolower($adaptiveLevel));

        abort_unless(
            isset(self::ADAPTIVE_RANGES[$adaptiveLevel]) || $adaptiveLevel === self::NO_PRETEST_KEY,
            400,
            'Invalid level'
        );

        // Ambil semua sesi Boss Battle yang sudah selesai, lalu klasifikasi
        // di PHP berdasarkan pretest_score user. Mahasiswa pretest=Easy yang
        // sudah progresi ke Boss Medium tetap masuk ke kelompok Easy.
        // Responden yang belum punya pretest_score masuk ke "NoPretest".
        $items = $this->bossSessionsBaseQuery()
            ->orderBy('user_id')
            ->orderBy('waktu_selesai')
            ->get()
            ->filter(function ($session) use ($adaptiveLevel) {
                $score = $session->user->pretest_score;
                $score = $score !== null ? (int) $score : null;
                return $this->classifyAdaptive($score) === $adaptiveLevel;
            })
            // Per responden ambil sesi Boss Battle finish PERTAMA
            ->groupBy('user_id')
            ->map(fn ($group) => $group->first())
            ->values();

        $filenameLabel = $adaptiveLevel === self::NO_PRETEST_KEY ? 'no-pretest' : strtolower($adaptiveLevel);
        $filename = 'boss-battle-' . $filenameLabel . '-' . date('Y-m-d-His') . '.csv';

        return $this->streamCsv($filename, function ($file) use ($items) {
            fputcsv($file, $this->bossBattleHeader(), ';');
            foreach ($items as $session) {
                fputcsv(
                    $file,
                    $this->bossBattleRow($session, $session->soloRaid->nama ?? '-', 'Solo Raid'),
                    ';'
                );
            }
        });
    }

    // ===================================================================
    // BOSS BATTLE — Event (multiplayer)
    // ===================================================================

    private function exportBossBattleEvent(int $eventId)
    {
        $event = Event::findOrFail($eventId);

        $items = EventParticipant::with(['user'])
            ->where('event_id', $eventId)
            ->whereNotNull('waktu_selesai')
            ->whereHas('user', function ($q) {
                $q->whereNotIn('email', $this->excludedEmails);
            })
            ->orderBy('waktu_selesai')
            ->get();

        $filename = 'boss-battle-event-' . $eventId . '-' . date('Y-m-d-His') . '.csv';

        return $this->streamCsv($filename, function ($file) use ($items, $event) {
            fputcsv($file, $this->bossBattleHeader(), ';');
            foreach ($items as $participant) {
                fputcsv($file, $this->bossBattleRow($participant, $event->title, 'Event'), ';');
            }
        });
    }

    // ===================================================================
    // PRE-TEST
    // ===================================================================

    private function exportPretest(string $scope)
    {
        $query = SessionSolo::with('user')
            ->where('is_pretest', true)
            ->whereNotNull('waktu_selesai')
            ->whereHas('user', function ($q) {
                $q->whereNotIn('email', $this->excludedEmails);
            });

        if ($scope !== 'all') {
            $query->whereHas('user', fn ($q) => $q->where('kelas', $scope));
        }

        // Satu baris per responden — pakai sesi finish PERTAMA (yang menentukan level_adaptif).
        $sessions = $query->orderBy('waktu_selesai')->get()
            ->groupBy('user_id')
            ->map(fn ($group) => $group->first())
            ->values();

        $filename = 'pretest-' . $scope . '-' . date('Y-m-d-His') . '.csv';

        return $this->streamCsv($filename, function ($file) use ($sessions) {
            fputcsv($file, $this->pretestHeader(), ';');
            foreach ($sessions as $session) {
                fputcsv($file, $this->pretestRow($session), ';');
            }
        });
    }

    // ===================================================================
    // CSV HEADERS
    // ===================================================================

    private function bossBattleHeader(): array
    {
        return [
            // Identitas & segmentasi
            'nim',
            'nama',
            'email',
            'kelas',
            'level_adaptif',          // hasil pre-test (Easy/Medium/Hard)
            'skor_pretest',           // 0..100
            'level_sesi',             // level boss battle yang dimainkan
            'sumber_sesi',            // nama raid / event title
            'tipe_sesi',              // Solo Raid / Event

            // Boss Battle log
            'waktu_mulai',
            'waktu_selesai',
            'durasi_pengerjaan_detik',
            'waktu_tersisa_detik',
            'jumlah_soal_total',
            'jumlah_soal_benar',
            'jumlah_soal_salah',
            'akurasi_persen',
            'skor_akhir_boss',
            'boss_hp_awal',
            'boss_hp_akhir',
            'player_hp_awal',
            'player_hp_akhir',
            'status_boss',            // Menang / Kalah / Timeout

            // Gamifikasi
            'xp_diperoleh',
            'xp_total_sebelum',
            'xp_total_sesudah',
            'badge_diperoleh',
            'jumlah_badge_total',
            'peringkat_leaderboard_sebelum',
            'peringkat_leaderboard_sesudah',

            // NASA-TLX placeholder
            'TLX_MD',
            'TLX_PD',
            'TLX_TD',
            'TLX_OP',
            'TLX_EF',
            'TLX_FR',
            'TLX_Score',
        ];
    }

    private function pretestHeader(): array
    {
        return [
            'nim',
            'nama',
            'email',
            'kelas',
            'waktu_mulai',
            'waktu_selesai',
            'waktu_pretest_detik',
            'jumlah_soal_total',
            'skor_pretest_raw',          // jumlah benar
            'persentase_pretest',        // 0..100
            'level_adaptif',             // hasil placement
            'jumlah_benar_easy',
            'jumlah_benar_medium',
            'jumlah_benar_hard',
            'jumlah_salah_total',

            // NASA-TLX placeholder (fase 1)
            'TLX_MD',
            'TLX_PD',
            'TLX_TD',
            'TLX_OP',
            'TLX_EF',
            'TLX_FR',
            'TLX_Score',
        ];
    }

    // ===================================================================
    // ROW BUILDERS
    // ===================================================================

    /** @param SessionSolo|EventParticipant $session */
    private function bossBattleRow($session, string $sourceTitle, string $tipeSesi): array
    {
        $user = $session->user;
        $isSolo = $session instanceof SessionSolo;

        $jumlahSoal  = (int) ($session->jumlah_soal ?? 0);
        $jumlahBenar = (int) ($session->jumlah_benar ?? 0);
        $jumlahSalah = (int) ($session->jumlah_salah ?? 0);
        $akurasi     = $jumlahSoal > 0 ? round(($jumlahBenar / $jumlahSoal) * 100, 2) : 0;

        $status       = $this->resolveBossStatus($session, $isSolo);
        $waktuTersisa = $isSolo ? $this->resolveSoloTimeRemaining($session) : null;

        $xpDiperoleh = (int) ($session->xp_diperoleh ?? 0);
        $xpBefore    = $this->cumulativeXpBefore($user->id, $session->waktu_selesai, $session);
        $xpAfter     = $xpBefore + $xpDiperoleh;

        $badgesInSession = $this->badgesUnlockedInWindow(
            $user->id,
            $session->waktu_mulai,
            $session->waktu_selesai
        );
        $totalBadges = UserBadge::where('user_id', $user->id)
            ->where('unlock_date', '<=', $session->waktu_selesai)
            ->count();

        $rankBefore = $this->rankAt($user, $xpBefore);
        $rankAfter  = $this->rankAt($user, $xpAfter);

        // level_adaptif diturunkan dari pretest_score — stabil walau current_section
        // sudah naik karena menang boss. Untuk responden tanpa pretest_score,
        // ditandai 'NoPretest' supaya analisis tahu data ini tidak punya baseline.
        $levelAdaptif = $user->pretest_score !== null
            ? $this->classifyAdaptive((int) $user->pretest_score)
            : self::NO_PRETEST_KEY;

        return [
            $user->nim ?? '-',
            $user->nama ?? '-',
            $user->email ?? '-',
            $user->kelas ?? '-',
            $levelAdaptif,
            $user->pretest_score ?? '-',
            $isSolo ? ($session->level ?? '-') : '-',
            $sourceTitle,
            $tipeSesi,

            $session->waktu_mulai ? $session->waktu_mulai->format('Y-m-d H:i:s') : '-',
            $session->waktu_selesai ? $session->waktu_selesai->format('Y-m-d H:i:s') : '-',
            (int) ($session->durasi_detik ?? 0),
            $waktuTersisa !== null ? $waktuTersisa : '-',
            $jumlahSoal,
            $jumlahBenar,
            $jumlahSalah,
            $akurasi,
            $session->skor_akhir !== null ? (float) $session->skor_akhir : 0,
            $session->boss_hp_awal ?? '-',
            $session->boss_hp_akhir ?? '-',
            $isSolo ? ($session->player_hp_awal ?? '-') : '-',
            $isSolo ? ($session->player_hp_akhir ?? '-') : '-',
            $status,

            $xpDiperoleh,
            $xpBefore,
            $xpAfter,
            $badgesInSession === '' ? '-' : $badgesInSession,
            $totalBadges,
            $rankBefore ?? '-',
            $rankAfter ?? '-',

            // NASA-TLX placeholders
            '', '', '', '', '', '', '',
        ];
    }

    private function pretestRow(SessionSolo $session): array
    {
        $user = $session->user;

        $jumlahSoal  = (int) ($session->jumlah_soal ?? 0);
        $jumlahBenar = (int) ($session->jumlah_benar ?? 0);
        $jumlahSalah = (int) ($session->jumlah_salah ?? max(0, $jumlahSoal - $jumlahBenar));
        $persentase  = $session->skor_akhir !== null
            ? (float) $session->skor_akhir
            : ($jumlahSoal > 0 ? round(($jumlahBenar / $jumlahSoal) * 100, 2) : 0);

        $perLevel     = $this->countCorrectByLevel($session->id);
        $levelAdaptif = app(PreTestService::class)->determineSection($persentase);

        return [
            $user->nim ?? '-',
            $user->nama ?? '-',
            $user->email ?? '-',
            $user->kelas ?? '-',
            $session->waktu_mulai ? $session->waktu_mulai->format('Y-m-d H:i:s') : '-',
            $session->waktu_selesai ? $session->waktu_selesai->format('Y-m-d H:i:s') : '-',
            (int) ($session->durasi_detik ?? 0),
            $jumlahSoal,
            $jumlahBenar,
            $persentase,
            $levelAdaptif,
            $perLevel['Easy'] ?? 0,
            $perLevel['Medium'] ?? 0,
            $perLevel['Hard'] ?? 0,
            $jumlahSalah,

            // NASA-TLX placeholders
            '', '', '', '', '', '', '',
        ];
    }

    // ===================================================================
    // HELPERS
    // ===================================================================

    private function streamCsv(string $filename, \Closure $writer)
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($writer) {
            $file = fopen('php://output', 'w');
            // BOM untuk Excel UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            $writer($file);
            fclose($file);
        }, 200, $headers);
    }

    /** @param SessionSolo|EventParticipant $session */
    private function resolveBossStatus($session, bool $isSolo): string
    {
        if ($session->boss_kalah) {
            return 'Menang';
        }

        if ($isSolo) {
            $config   = SoloBattleService::LEVEL_CONFIG[$session->level] ?? null;
            $deadline = $config && $session->waktu_mulai
                ? $session->waktu_mulai->copy()->addMinutes($config['timer_minutes'])
                : null;

            $endedAtDeadline = $deadline && $session->waktu_selesai
                && $session->waktu_selesai->greaterThanOrEqualTo($deadline->copy()->subSeconds(2));

            $playerDead = $session->player_hp_akhir !== null && $session->player_hp_akhir <= 0;

            if ($playerDead) {
                return 'Kalah';
            }
            if ($endedAtDeadline) {
                return 'Timeout';
            }
            return 'Kalah';
        }

        $status = $session->status ?? '';
        if ($status === 'timeout') return 'Timeout';
        if ($status === 'finished') return 'Kalah';
        return ucfirst(str_replace('_', ' ', (string) $status)) ?: 'Kalah';
    }

    private function resolveSoloTimeRemaining(SessionSolo $session): ?int
    {
        if (!$session->waktu_mulai || !$session->waktu_selesai) {
            return null;
        }

        $config = SoloBattleService::LEVEL_CONFIG[$session->level] ?? null;
        if (!$config) {
            return null;
        }

        $deadline  = $session->waktu_mulai->copy()->addMinutes($config['timer_minutes']);
        $remaining = $deadline->getTimestamp() - $session->waktu_selesai->getTimestamp();
        return max(0, (int) $remaining);
    }

    /**
     * Total XP yang sudah dikumpulkan user sebelum sesi ini selesai.
     * Tidak termasuk pre-test (XP-nya 0) dan tidak termasuk sesi current itu sendiri.
     */
    private function cumulativeXpBefore(int $userId, $beforeTime, $current): int
    {
        if (!$beforeTime) {
            return 0;
        }

        $soloXp = SessionSolo::where('user_id', $userId)
            ->whereNotNull('waktu_selesai')
            ->where('waktu_selesai', '<', $beforeTime)
            ->where(function ($q) {
                $q->whereNull('is_pretest')->orWhere('is_pretest', false);
            })
            ->when($current instanceof SessionSolo, fn ($q) => $q->where('id', '!=', $current->id))
            ->sum('xp_diperoleh');

        $eventXp = EventParticipant::where('user_id', $userId)
            ->whereNotNull('waktu_selesai')
            ->where('waktu_selesai', '<', $beforeTime)
            ->when($current instanceof EventParticipant, fn ($q) => $q->where('event_participant_id', '!=', $current->event_participant_id))
            ->sum('xp_diperoleh');

        return (int) ($soloXp + $eventXp);
    }

    private function rankAt(User $user, int $xpSnapshot): ?int
    {
        if (!$user->kelas) {
            return null;
        }

        $higher = User::where('role', 'student')
            ->where('kelas', $user->kelas)
            ->whereNotIn('email', $this->excludedEmails)
            ->where('id', '!=', $user->id)
            ->where('total_xp', '>', $xpSnapshot)
            ->count();

        return $higher + 1;
    }

    private function badgesUnlockedInWindow(int $userId, $start, $end): string
    {
        if (!$start || !$end) {
            return '';
        }

        $slugs = UserBadge::with('badge')
            ->where('user_id', $userId)
            ->whereBetween('unlock_date', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->get()
            ->pluck('badge.slug')
            ->filter()
            ->values()
            ->all();

        return implode('|', $slugs);
    }

    private function countCorrectByLevel(int $sessionId): array
    {
        $rows = SessionAnswer::query()
            ->join('question_bank', 'question_bank.id', '=', 'session_answer.question_id')
            ->where('session_answer.session_id', $sessionId)
            ->where('session_answer.session_type', 'solo')
            ->where('session_answer.is_correct', true)
            ->selectRaw('question_bank.level as lvl, COUNT(*) as total')
            ->groupBy('question_bank.level')
            ->pluck('total', 'lvl')
            ->all();

        return [
            'Easy'   => (int) ($rows['Easy'] ?? 0),
            'Medium' => (int) ($rows['Medium'] ?? 0),
            'Hard'   => (int) ($rows['Hard'] ?? 0),
        ];
    }
}
