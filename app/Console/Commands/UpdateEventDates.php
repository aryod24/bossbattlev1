<?php

namespace App\Console\Commands;

use App\Models\SoloRaid;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateEventDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:update-dates 
                            {action : Action to perform (close|open|custom)}
                            {--date= : Custom date for action (format: Y-m-d, e.g., 2026-05-19)}
                            {--start= : Custom start date (format: Y-m-d H:i:s)}
                            {--end= : Custom end date (format: Y-m-d H:i:s)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Solo Raid event dates for testing purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'close':
                $this->closeEvents();
                break;
            case 'open':
                $this->openEvents();
                break;
            case 'custom':
                $this->customDates();
                break;
            default:
                $this->error("Invalid action. Use: close, open, or custom");
                return 1;
        }

        return 0;
    }

    /**
     * Close all events on specified date (default: May 19, 2026 23:59:59)
     */
    private function closeEvents()
    {
        $closeDate = $this->option('date') 
            ? Carbon::parse($this->option('date'))->endOfDay()
            : Carbon::create(2026, 5, 19, 23, 59, 59);

        $this->info("Closing all events on: {$closeDate->format('Y-m-d H:i:s')}");

        $events = SoloRaid::all();
        $updated = 0;

        foreach ($events as $event) {
            $event->update([
                'tanggal_mulai' => Carbon::now()->subDays(7), // Started 7 days ago
                'tanggal_selesai' => $closeDate,
                'status' => 'selesai'
            ]);
            $updated++;
            $this->line("✓ {$event->nama} - Closed on {$closeDate->format('Y-m-d H:i:s')}");
        }

        $this->info("\n✅ Successfully closed {$updated} events!");
        $this->comment("All events will end on: {$closeDate->format('Y-m-d H:i:s')}");
    }

    /**
     * Open all events on specified date (default: May 20, 2026 00:00:00)
     */
    private function openEvents()
    {
        $openDate = $this->option('date')
            ? Carbon::parse($this->option('date'))->startOfDay()
            : Carbon::create(2026, 5, 20, 0, 0, 0);

        $endDate = $openDate->copy()->addDays(30);

        $this->info("Opening all events from: {$openDate->format('Y-m-d H:i:s')}");
        $this->info("Events will end on: {$endDate->format('Y-m-d H:i:s')}");

        $events = SoloRaid::all();
        $updated = 0;

        foreach ($events as $event) {
            $event->update([
                'tanggal_mulai' => $openDate,
                'tanggal_selesai' => $endDate,
                'status' => 'active'
            ]);
            $updated++;
            $this->line("✓ {$event->nama} - Opens on {$openDate->format('Y-m-d H:i:s')}");
        }

        $this->info("\n✅ Successfully opened {$updated} events!");
        $this->comment("All events active from {$openDate->format('Y-m-d H:i:s')} to {$endDate->format('Y-m-d H:i:s')}");
    }

    /**
     * Set custom start and end dates for all events
     */
    private function customDates()
    {
        if (!$this->option('start') || !$this->option('end')) {
            $this->error("Custom action requires --start and --end options");
            $this->comment("Example: php artisan events:update-dates custom --start='2026-05-20 00:00:00' --end='2026-06-20 23:59:59'");
            return 1;
        }

        try {
            $startDate = Carbon::parse($this->option('start'));
            $endDate = Carbon::parse($this->option('end'));

            if ($endDate->lte($startDate)) {
                $this->error("End date must be after start date!");
                return 1;
            }

            $this->info("Setting custom dates:");
            $this->info("Start: {$startDate->format('Y-m-d H:i:s')}");
            $this->info("End: {$endDate->format('Y-m-d H:i:s')}");

            $events = SoloRaid::all();
            $updated = 0;

            foreach ($events as $event) {
                $event->update([
                    'tanggal_mulai' => $startDate,
                    'tanggal_selesai' => $endDate,
                    'status' => 'active'
                ]);
                $updated++;
                $this->line("✓ {$event->nama}");
            }

            $this->info("\n✅ Successfully updated {$updated} events with custom dates!");

        } catch (\Exception $e) {
            $this->error("Invalid date format: {$e->getMessage()}");
            $this->comment("Use format: Y-m-d H:i:s (e.g., 2026-05-20 00:00:00)");
            return 1;
        }
    }
}
