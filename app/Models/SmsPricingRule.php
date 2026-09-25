<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsPricingRule extends Model {
 use HasFactory, BelongsToSchool;

 protected $fillable = [
 "school_id","name","unit_price","currency",
 "effective_from","effective_to","is_active","notes",
 ];

 protected $casts = [
 "unit_price" => "decimal:4",
 "effective_from" => "date",
 "effective_to" => "date",
 "is_active" => "boolean",
 ];

 public static function current(): ?self {
 return self::where("is_active", true)
 ->where("effective_from", "<=", now()->toDateString())
 ->where(function ($q) {
 $q->whereNull("effective_to")->orWhere("effective_to", ">=", now()->toDateString());
 })
 ->latest("effective_from")
 ->first();
 }
}