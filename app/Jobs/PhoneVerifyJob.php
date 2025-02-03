<?php

namespace App\Jobs;

use App\Models\Verification;
use App\Services\TwilioSdkService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PhoneVerifyJob implements ShouldQueue
{
    use Queueable;

    public TwilioSdkService $twilioSdkService;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Verification $verification
    ) {
        $this->twilioSdkService = new TwilioSdkService();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->twilioSdkService->sendMessage(
            $this->verification->code,
            $this->verification->user->phone
        );
    }
}
