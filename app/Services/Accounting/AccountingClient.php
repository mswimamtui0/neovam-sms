<?php

namespace App\Services\Accounting;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Placeholder client for the EXTERNAL accounting service.
 * Full integration will be done later.
 */
class AccountingClient
{
    protected ?string $url;
    protected ?string $key;

    public function __construct()
    {
        $this->url = config('services.accounting.url');
        $this->key = config('services.accounting.key');
    }

    public function recordPayment(array $payload): array
    {
        if (!$this->url) {
            Log::info('Accounting API not configured. Skipping.', $payload);
            return ['success' => false, 'reason' => 'not_configured'];
        }

        try {
            $response = Http::withToken($this->key)
                ->timeout(15)
                ->post($this->url . '/payments', $payload);

            return [
                'success' => $response->successful(),
                'body'    => $response->json(),
            ];
        } catch (\Throwable $e) {
            Log::error('Accounting API failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'reason' => $e->getMessage()];
        }
    }
}