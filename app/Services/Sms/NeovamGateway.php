<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NeovamGateway
{
    protected ?string $baseUrl;
    protected ?string $clientId;
    protected ?string $secret;
    protected string  $sender;
    protected string  $environment;
    protected bool    $testMode;
    protected int     $timeout;

    public function __construct()
    {
        $this->baseUrl     = rtrim(config("services.neovam_sms.url", ""), "/");
        $this->clientId    = config("services.neovam_sms.client_id");
        $this->secret      = config("services.neovam_sms.key");
        $this->sender      = config("services.neovam_sms.sender", "NEOVAM");
        $this->environment = config("services.neovam_sms.environment", "production");
        $this->testMode    = (bool) config("services.neovam_sms.test_mode", false);
        $this->timeout     = (int) config("services.neovam_sms.timeout", 8);
    }

    /**
     * Send an SMS via NEOVAM gateway.
     *
     * Signature formula (VERIFIED WORKING):
     *   material  = timestamp + "." + body
     *   signature = HMAC-SHA256(secret, material) as lowercase hex
     */
    public function send(string $to, string $message, string $eventType = "GENERAL"): array
    {
        // 1. Normalize phone
        $originalPhone = $to;
        $to = $this->normalizePhone($to);
        if (!$to) {
            Log::warning("SMS skipped — invalid phone", ["original" => $originalPhone]);
            return ["success" => false, "reference" => null, "error" => "invalid_phone"];
        }

        // 2. Sanitize
        $message = trim(strip_tags($message));
        $message = mb_substr($message, 0, 500);

        // 3. Test mode
        if ($this->testMode) {
            Log::info("SMS TEST MODE (not sent)", [
                "to"    => $to,
                "event" => $eventType,
                "chars" => mb_strlen($message),
            ]);
            return [
                "success"   => true,
                "reference" => "TEST-" . strtoupper(uniqid()),
                "error"     => null,
                "status"    => "SENT",
            ];
        }

        // 4. Config check
        if (!$this->baseUrl || !$this->clientId || !$this->secret) {
            Log::warning("SMS gateway not configured", [
                "url_set"    => (bool) $this->baseUrl,
                "client_set" => (bool) $this->clientId,
                "secret_set" => (bool) $this->secret,
            ]);
            return ["success" => false, "reference" => null, "error" => "not_configured"];
        }

        // 5. Build payload
        $payload = [
            "to"         => $to,
            "message"    => $message,
            "event_type" => $eventType,
        ];

        // 6. Compact JSON — sign exact bytes
        $body = json_encode($payload);

        // 7. Sign: HMAC-SHA256(secret, timestamp + "." + body)
        $timestamp = (string) time();
        $signature = hash_hmac("sha256", $timestamp . "." . $body, $this->secret);

        // 8. Idempotency key
        $idempotencyKey = $eventType . ":" . $to . ":" . now()->format("YmdHis");

        // 9. Endpoint
        $endpoint = $this->baseUrl;
        if (!str_ends_with($endpoint, "/v1/messages")) {
            $endpoint .= "/v1/messages";
        }

        // 10. Fire
        try {
            $response = Http::withHeaders([
                "Content-Type"    => "application/json",
                "Accept"          => "application/json",
                "X-Client-ID"     => $this->clientId,
                "X-Timestamp"     => $timestamp,
                "X-Signature"     => $signature,
                "Idempotency-Key" => $idempotencyKey,
            ])
            ->timeout($this->timeout)
            ->withBody($body, "application/json")
            ->post($endpoint);

            if ($response->successful()) {
                $json = $response->json() ?? [];

                return [
                    "success"   => true,
                    "reference" => $json["request_id"] ?? $json["provider_message_id"] ?? $json["message_id"] ?? null,
                    "error"     => null,
                    "status"    => $json["status"] ?? "SENT",
                    "raw"       => $json,
                    "network"   => $json["network"] ?? null,
                ];
            }

            Log::warning("NEOVAM SMS non-2xx", [
                "to"     => $to,
                "status" => $response->status(),
                "body"   => substr($response->body(), 0, 500),
            ]);

            return [
                "success"   => false,
                "reference" => null,
                "error"     => "http_" . $response->status(),
            ];

        } catch (\Throwable $e) {
            Log::error("NEOVAM SMS exception", [
                "to"      => $to,
                "message" => $e->getMessage(),
            ]);

            return ["success" => false, "reference" => null, "error" => "exception"];
        }
    }

    public function balance(): array
    {
        if ($this->testMode) {
            return ["success" => true, "balance" => 999999, "currency" => "TZS"];
        }
        if (!$this->baseUrl || !$this->clientId || !$this->secret) {
            return ["success" => false, "balance" => null, "currency" => null];
        }

        try {
            $timestamp = (string) time();
            $body      = "";
            $signature = hash_hmac("sha256", $timestamp . "." . $body, $this->secret);

            $response = Http::withHeaders([
                "Accept"      => "application/json",
                "X-Client-ID" => $this->clientId,
                "X-Timestamp" => $timestamp,
                "X-Signature" => $signature,
            ])->timeout($this->timeout)->get($this->baseUrl . "/v1/balance");

            if ($response->successful()) {
                return [
                    "success"  => true,
                    "balance"  => $response->json("balance") ?? 0,
                    "currency" => $response->json("currency") ?? "TZS",
                ];
            }
        } catch (\Throwable $e) {
            Log::warning("Balance check failed", ["error" => $e->getMessage()]);
        }

        return ["success" => false, "balance" => null, "currency" => null];
    }

    protected function normalizePhone(string $phone): ?string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($digits) < 9) return null;

        if (strlen($digits) === 10 && $digits[0] === "0") {
            $digits = "255" . substr($digits, 1);
        }
        if (strlen($digits) === 9) {
            return "255" . $digits;
        }
        if (strlen($digits) === 12 && str_starts_with($digits, "255")) {
            return $digits;
        }
        if (strlen($digits) >= 10 && strlen($digits) <= 15) {
            return $digits;
        }

        return null;
    }

    public function isTestMode(): bool
    {
        return $this->testMode;
    }

    public function environment(): string
    {
        return $this->environment;
    }
}