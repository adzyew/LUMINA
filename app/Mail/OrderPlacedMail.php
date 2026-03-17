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
        return $this->subject('Your Lumina Order Confirmation - #' . $this->order->display_order_number)
            ->view('emails.order_placed')
            ->with([
                'supportEmail' => config('mail.from.address'),
                'websiteUrl' => config('app.url'),
                'orderStatusUrl' => url('/dashboard'),
            ]);
    }
}
