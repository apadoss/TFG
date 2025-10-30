<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PriceDropNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $component;
    public $oldPrice;
    public $newPrice;
    public $priceDrop;
    public $percentageDrop;
    public $componentUrl;
    public $vendor;

    public function __construct($component, $oldPrice, $newPrice, $componentUrl, $vendor)
    {
        $this->component = $component;
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
        $this->priceDrop = $oldPrice - $newPrice;
        $this->percentageDrop = round((($oldPrice - $newPrice) / $oldPrice) * 100, 2);
        $this->componentUrl = $componentUrl;
        $this->vendor = $vendor;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bajada de precio en el componente: ' . $this->component->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.price-drop-notification',
        );
    }
}
