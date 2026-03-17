<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $previousStatus
    ) {
        $this->order->loadMissing(['user', 'items.product']);
    }

    public function build()
    {
        $status = strtolower((string) $this->order->status);
        $orderNumber = $this->order->display_order_number;

        $subjects = [
            'pending' => 'We Received Your Lumina Order - #' . $orderNumber,
            'confirmed' => 'Your Lumina Order Is Confirmed - #' . $orderNumber,
            'processing' => 'Update: We Are Preparing Your Lumina Order - #' . $orderNumber,
            'shipped' => 'Update: Your Lumina Order Is On Its Way! - #' . $orderNumber,
            'delivered' => 'Delivered: Your Lumina Order Has Arrived - #' . $orderNumber,
            'cancelled' => 'Update: Your Lumina Order Was Cancelled - #' . $orderNumber,
        ];

        $subject = $subjects[$status] ?? ('Order #' . $orderNumber . ' Status Update from Lumina');

        return $this->subject($subject)
            ->view('emails.order_status_updated')
            ->with([
                'supportEmail' => config('mail.from.address'),
                'websiteUrl' => config('app.url'),
                'orderStatusUrl' => url('/dashboard'),
                'trackingUrl' => $this->order->tracking_url,
            ]);
    }
}
