<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearRequestFormCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request-form:clear-cache {--all : Clear all request form caches}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cached data for Request Forms (types, statuses, employees)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('all')) {
            // Clear all request form related caches
            $caches = [
                'request_form_types_statuses',
                'authorizable_employees',
            ];

            foreach ($caches as $cacheKey) {
                Cache::forget($cacheKey);
            }

            $this->info('✅ All Request Form caches cleared successfully!');
            $this->table(
                ['Cache Key', 'Status'],
                array_map(fn ($key) => [$key, 'Cleared'], $caches)
            );
        } else {
            // Interactive menu
            $choice = $this->choice(
                'Which cache do you want to clear?',
                [
                    'request_form_types_statuses' => 'Form Types & Statuses (24h TTL)',
                    'authorizable_employees' => 'Authorizable Employees List (1h TTL)',
                    'all' => 'All of the above',
                ],
                'all'
            );

            if ($choice === 'all') {
                Cache::forget('request_form_types_statuses');
                Cache::forget('authorizable_employees');
                $this->info('✅ All caches cleared!');
            } else {
                Cache::forget($choice);
                $this->info("✅ Cache '{$choice}' cleared successfully!");
            }
        }

        $this->newLine();
        $this->comment('💡 Tip: Caches will be automatically regenerated on next API call.');

        return Command::SUCCESS;
    }
}
