<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        "recipient", "message", "trigger",
        "units", "char_count",
        "status", "gateway_ref",
    ];

    protected $casts = [
        "units"      => "integer",
        "char_count" => "integer",
    ];

    public function markSent(?string $ref = null): void
    {
        $this->update([
            "status"      => "sent",
            "gateway_ref" => $ref,
        ]);
    }

    public function markFailed(): void
    {
        $this->update(["status" => "failed"]);
    }
}