<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SoloBattleService;

class FinishExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:finish-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finish all expired battle sessions';

    /**
     * Execute the console command.
     */
    public function handle(SoloBattleService $service)
    {
        $count = $service->autoFinishExpiredSessions();
        $this->info("Finished {$count} expired sessions.");
        return 0;
    }
}
