<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSkippedMessage extends Model {
 use HasFactory;
 protected $fillable = ["recipient","message","trigger","skip_reason","would_have_sent_at"];
 protected $casts = ["would_have_sent_at" => "datetime"];
}