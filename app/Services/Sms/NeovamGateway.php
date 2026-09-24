<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NeovamGateway
{
    protected ?string $url;
    protected ?string $key;
    protected string $sender;

    public function __construct()
    {
        $this->url    = config("services.neovam_sms.url");
        $this->key    = config("services.neovam_sms.key");
        $this->sender = config("services.neovam_sms.sender", "NEOVAM");
    }

    /**
     * Send an SMS through the NEOVAM gateway.
     */
    public function send(string $to, string $message): array
    {
        // 1. Validate phone
        $to = preg_replace("/[^0-9]/", "", $to);
        if (strlen($to) < 9) {
            Log::warning("Invalid phone number", ["to" => $to]);
            return ["success" => false, "reference" => null];
        }

        // 2. Sanitize message
        $message = strip_tags($message);
        $message = mb_substr($message, 0, 500);

        // 3. If gateway not configured, log and exit gracefully
        if (!$this->url || !$this->key) {
            Log::info("NEOVAM SMS gateway not configured. Message would be sent.", [
                "to"      => $to,
                "message" => $message,
            ]);
            return ["success" => false, "reference" => "not_configured"];
        }

        // 4. Sign the request (HMAC)
        $timestamp = time();
        $signature = hash_hmac("sha256", $to . $message . $timestamp, $this->key);

        try {
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $this->key,
                "X-Signature"   => $signature,
                "X-Timestamp"   => $timestamp,
                "Accept"        => "application/json",
            ])->timeout(15)->post($this->url, [
                "sender"  => $this->sender,
                "to"      => $to,
                "message" => $message,
            ]);

            if ($response->successful()) {
                return [
                    "success"   => true,
                    "reference" => $response->json("id") ?? $response->json("reference"),
                ];
            }

            Log::warning("NEOVAM SMS non-2xx", [
                "to"     => $to,
                "status" => $response->status(),
            ]);

            return ["success" => false, "reference" => null];

        } catch (\Throwable $e) {
            Log::error("NEOVAM SMS exception", [
                "to"      => $to,
                "message" => $e->getMessage(),
            ]);

            return ["success" => false, "reference" => null];
        }
    }
}