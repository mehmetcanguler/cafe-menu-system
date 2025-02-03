<?php

namespace App\Services;

use Twilio\Rest\Client;


class TwilioSdkService
{
    public function __construct()
    {
    }

    public function sendMessage(string $phoneNumber, string $message)
    {
        try {
            $client = new Client(
                env('TWILIO_ACCOUNT_SID'),
                env('TWILIO_AUTH_TOKEN')
            );

            $client->messages->create(
                // The number you'd like to send the message to
                $phoneNumber,
                [
                    // A Twilio phone number you purchased at https://console.twilio.com
                    'from' => env('TWILIO_PHONE_NUMBER'),
                    // The body of the text message you'd like to send
                    'body' => $message
                ]
            );
            return true;
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return false;
        }
    }
}