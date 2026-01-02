<?php

namespace App\Jobs;

use App\Mail\LowStockAlert;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LowStockNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Product $product,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // For now, let's just log the email content instead of sending it.
        $mailable = new LowStockAlert($this->product);
        $renderedMail = $mailable->render(); // Render the email content

        Log::channel('daily')->info('Low Stock Email Content for Product: ' . $this->product->name, [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'stock_quantity' => $this->product->stock_quantity,
            'email_content' => $renderedMail,
        ]);
    }
}
