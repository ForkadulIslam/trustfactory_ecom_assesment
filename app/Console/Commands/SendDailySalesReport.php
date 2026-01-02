<?php

namespace App\Console\Commands;

use App\Jobs\SendDailySalesReport as SendDailySalesReportJob;
use Illuminate\Console\Command;

class SendDailySalesReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:send-daily-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches a job to send the daily sales report.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SendDailySalesReportJob::dispatch();
        $this->info('The daily sales report job has been dispatched.');
    }
}