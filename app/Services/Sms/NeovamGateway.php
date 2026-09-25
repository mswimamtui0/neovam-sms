<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NeovamGateway
{
    protected ?string $url;
    protected ?string $key;
    protected ?string $secret;
    protected string  $sender;
    protected string  $environment;
    protected bool    $testMode;

    public function __construct()
    {
        $this->url         = config("services.neovam_sms.url");
        $this->key         = config("services.neovam_sms.key");
        $this->secret      = config("services.neovam_sms.secret");
        $this->sender      = config("services.neovam_sms.sender", "NEOVAM");
        $this->environment = config("services.neovam_sms.environment", "sandbox");
        $this->testMode    = (bool) config("services.neovam_sms.test_mode", true);
    }

    /**
     * Send an SMS. Returns [success (bool), reference (string|null), error (string|null)]
     */
    public function send(string $to, string $message): array
    {
        // 1. Normalize phone number to international format
        $to = $this->normalizePhone($to);
        if (!$to) {
            Log::warning("SMS skipped — invalid phone", ["original" => func_get_args()[0] ?? null]);
            return ["success" => false, "reference" => null, "error" => "invalid_phone"];
        }

        // 2. Sanitize message
        $message = trim(strip_tags($message));
        $message = mb_substr($message, 0, 500);

        // 3. Test mode — simulate without hitting real gateway
        if ($this->testMode) {
            Log::info("SMS TEST MODE (not sent)", [
                "to"      => $to,
                "sender"  => $this->sender,
                "message" => $message,
                "chars"   => mb_strlen($message),
            ]);
            return [
                "success"   => true,
                "reference" => "TEST-" . strtoupper(uniqid()),
                "error"     => null,
            ];
        }

        // 4. If not configured, log and exit gracefully
        if (!$this->url || !$this->key) {
            Log::warning("SMS gateway not configured — message not sent", [
                "to" => $to, "message" => $message,
            ]);
            return ["success" => false, "reference" => null, "error" => "not_configured"];
        }

        // 5. Build the payload
        $payload = [
            "sender"  => $this->sender,
            "to"      => $to,
            "message" => $message,
        ];

        // 6. Optional HMAC signature
        $headers = [
            "Authorization" => "Bearer " . $this->key,
            "Accept"        => "application/json",
            "Content-Type"  => "application/json",
        ];

        if ($this->secret) {
            $timestamp = time();
            $signature = hash_hmac("sha256", $to . $message . $timestamp, $this->secret);
            $headers["X-Signature"] = $signature;
            $headers["X-Timestamp"] = $timestamp;
        }

        // 7. Fire request
        try {
            $response = Http::withHeaders($headers)
                ->timeout(20)
                ->post($this->url, $payload);

            if ($response->successful()) {
                $reference = $response->json("id")
                    ?? $response->json("reference")
                    ?? $response->json("message_id")
                    ?? null;

                return [
                    "success"    => true,
                    "reference"  => $reference,
                    "error"      => null,
                    "delivered"  => (bool) ($response->json("delivered") ?? false),
                    "network"    => $response->json("network") ?? null,
                ];
            }

            Log::warning("SMS gateway non-2xx", [
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
            Log::error("SMS gateway exception", [
                "to"      => $to,
                "message" => $e->getMessage(),
            ]);

            return ["success" => false, "reference" => null, "error" => "exception"];
        }
    }

    /**
     * Check gateway balance (if your provider supports it).
     * Returns [success, balance, currency] or [false, null, null].
     */
    public function balance(): array
    {
        if ($this->testMode) {
            return ["success" => true, "balance" => 999999, "currency" => "TZS"];
        }

        if (!$this->url || !$this->key) {
            return ["success" => false, "balance" => null, "currency" => null];
        }

        try {
            $base = rtrim($this->url, "/");
            $base = preg_replace('/\/send$/', '', $base);

            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $this->key,
                "Accept"        => "application/json",
            ])->timeout(15)->get($base . "/balance");

            if ($response->successful()) {
                return [
                    "success"  => true,
                    "balance"  => $response->json("balance") ?? 0,
                    "currency" => $response->json("currency") ?? "TZS",
                ];
            }
        } catch (\Throwable $e) {
            Log::warning("SMS balance check failed", ["error" => $e->getMessage()]);
        }

        return ["success" => false, "balance" => null, "currency" => null];
    }

    /**
     * Normalize a phone number to international format (255XXXXXXXXX).
     */
    protected function normalizePhone(string $phone): ?string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($digits) < 9) return null;

        // Convert 0XXXXXXXXX → 255XXXXXXXXX (Tanzania)
        if (strlen($digits) === 10 && $digits[0] === "0") {
            $digits = "255" . substr($digits, 1);
        }

        // Already international (255...)
        if (strlen($digits) === 12 && str_starts_with($digits, "255")) {
            return $digits;
        }

        // Any other length — accept as-is if 10-15 digits
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