<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {
        $this->order->load(['user', 'items.product']);
    }

    public function build()
    {
        return $this->subject('Order Confirmation #' . $this->order->id . ' – Lumina Jewelry')
            ->view('emails.order_placed');
    }
}
