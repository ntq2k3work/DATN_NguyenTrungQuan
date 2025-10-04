<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ProcessOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process {--timeout=60 : Timeout in seconds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process order queue jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timeout = $this->option('timeout');
        
        $this->info("Starting order processing queue worker...");
        $this->info("Timeout: {$timeout} seconds");
        
        // Chạy queue worker với timeout
        Artisan::call('queue:work', [
            '--timeout' => $timeout,
            '--tries' => 3,
            '--queue' => 'default'
        ]);
        
        $this->info("Order processing completed.");
    }
}
