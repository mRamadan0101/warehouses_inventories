<?php

namespace App\Listeners;

use App\Events\LowStockDetected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendLowStockNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(LowStockDetected $event): void
    {

        Log::info('Low stock detected');
    }
}

