<?php

namespace App\Jobs;

use App\Mail\DailySalesReport;
use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendDailySalesReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $today = now();
        $sales = OrderItem::whereDate('created_at', $today)
            ->with('product')
            ->selectRaw('product_id, price, SUM(quantity) as total_quantity')
            ->groupBy('product_id', 'price')
            ->get();

        $totalRevenue = $sales->reduce(function ($carry, $sale) {
            return $carry + ($sale->total_quantity * $sale->price);
        }, 0);

        $mailable = new DailySalesReport($sales, $totalRevenue);
        $renderedMail = $mailable->render();

        Log::channel('daily')->info('Daily Sales Report Email Content for ' . $today->format('Y-m-d'), [
            'sales_data' => $sales->toArray(),
            'total_revenue' => $totalRevenue,
            'email_content' => $renderedMail,
        ]);
    }
}
