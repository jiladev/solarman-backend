<?php

namespace App\Mail;

use App\Models\Client;
use App\Models\ClientEstimate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $clientEstimate;

    public function __construct(Client $client, ClientEstimate $clientEstimate)
    {
        $this->client = $client;
        $this->clientEstimate = $clientEstimate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config("mail.from.address"), config("mail.from.name"))
            ->view('emails.client_created')
            ->subject('Um cliente acabou de fazer um orçamento')
            ->with([
                'client' => $this->client,
                'clientEstimate' => $this->clientEstimate,
            ]);
    }
}
