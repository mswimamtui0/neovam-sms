<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model {
    use HasFactory;

    protected $fillable = [
        "school_id","key","name","language","body","description","is_active",
    ];

    protected $casts = ["is_active" => "boolean"];

    /**
     * Fetch a rendered template by key + language.
     * Replaces {placeholders} with the given array values.
     */
    public static function render(string $key, array $data = [], string $language = "en"): ?string
    {
        $template = self::where("key", $key)
            ->where("language", $language)
            ->where("is_active", true)
            ->first();

        // Fallback to English if language missing
        if (!$template) {
            $template = self::where("key", $key)
                ->where("language", "en")
                ->where("is_active", true)
                ->first();
        }

        if (!$template) return null;

        $body = $template->body;

        foreach ($data as $placeholder => $value) {
            $body = str_replace("{" . $placeholder . "}", (string) $value, $body);
        }

        return $body;
    }
}