<?php

namespace App\Jobs;

use App\Mail\ClientCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendClientCreatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $client;
    public $clientEstimate;

    public function __construct($client, $clientEstimate)
    {
        $this->client = $client;
        $this->clientEstimate = $clientEstimate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->client->phone = $this->formatPhoneNumber($this->client->phone);
        $this->clientEstimate->final_value_discount = $this->formatCurrency($this->clientEstimate->final_value_discount);
        $this->clientEstimate->fatura_copel = $this->formatCurrency($this->clientEstimate->fatura_copel);

        Mail::to("igordanbo@gmail.com")->send(new ClientCreated($this->client, $this->clientEstimate));
    }

    private function formatPhoneNumber($phone)
    {
        if (strlen($phone) == 11) {
            return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
        }
        return $phone;
    }

    private function formatCurrency($value)
    {
        return number_format($value, 2, ',', '.');
    }
}
