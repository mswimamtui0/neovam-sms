<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsBalanceTransaction extends Model {
    use HasFactory, BelongsToSchool;

    protected $fillable = [
        "school_id","type","units","amount","currency",
        "reference","description","created_by",
    ];

    protected $casts = [
        "units"  => "integer",
        "amount" => "decimal:2",
    ];

    public function creator() { return $this->belongsTo(User::class, "created_by"); }

    /**
     * Current balance in units.
     */
    public static function balanceUnits(): int {
        return (int) self::sum("units");
    }

    /**
     * Current balance in money.
     */
    public static function balanceMoney(): float {
        return (float) self::sum("amount");
    }
}