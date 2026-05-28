<?php

namespace App\Http\Middleware;

use App\Models\SessionSolo;
use App\Services\PreTestService;
use App\Services\SoloBattleService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reconcile sesi-sesi expired milik user yang sedang request.
 *
 * Dipasang sebelum route play/pretest/map agar:
 *  - GET handler tidak perlu lagi memanggil `finishSession()` sendiri
 *    (menghindari side-effect mutasi pada GET).
 *  - Buka tab kedua / refresh / back button tetap konsisten karena
 *    finalisasi dijaga lewat row lock di SoloBattleService::finishSession().
 *
 * Penjadwalan masal tetap dilakukan oleh cron `sessions:finish-expired`
 * (lihat bootstrap/app.php). Middleware ini hanya menutup celah real-time
 * untuk user yang sedang aktif.
 */
class FinishExpiredUserSessions
{
    public function __construct(
        private readonly SoloBattleService $battleService,
        private readonly PreTestService $preTestService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user) {
            $this->reconcile((int) $user->id);
        }

        return $next($request);
    }

    private function reconcile(int $userId): void
    {
        $sessions = SessionSolo::where('user_id', $userId)
            ->whereNull('waktu_selesai')
            ->get();

        foreach ($sessions as $session) {
            $deadline = $this->resolveDeadline($session);
            if ($deadline === null || now()->lessThanOrEqualTo($deadline)) {
                continue;
            }

            if ($session->is_pretest) {
                $this->preTestService->finishPreTest($session);
            } else {
                $this->battleService->finishSession($session->id, $deadline);
            }
        }
    }

    private function resolveDeadline(SessionSolo $session): ?\Carbon\Carbon
    {
        if (!$session->waktu_mulai) {
            return null;
        }

        if ($session->is_pretest) {
            // Pre-test: 30 menit untuk semua soal (mengikuti SoloBattleService).
            return $session->waktu_mulai->copy()->addMinutes(30);
        }

        $config = SoloBattleService::LEVEL_CONFIG[$session->level]
            ?? SoloBattleService::LEVEL_CONFIG['Easy'];

        return $session->waktu_mulai->copy()->addMinutes($config['timer_minutes']);
    }
}
