<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollRun extends Model {
    use HasFactory;

    protected $fillable = [
        "school_id","period","label","period_start","period_end",
        "staff_count","total_gross","total_deductions","total_net",
        "status","created_by","approved_by","approved_at","paid_at","notes",
    ];

    protected $casts = [
        "period_start"     => "date",
        "period_end"       => "date",
        "total_gross"      => "decimal:2",
        "total_deductions" => "decimal:2",
        "total_net"        => "decimal:2",
        "approved_at"      => "datetime",
        "paid_at"          => "datetime",
    ];

    public function items()    { return $this->hasMany(PayrollItem::class); }
    public function creator()  { return $this->belongsTo(User::class, "created_by"); }
    public function approver() { return $this->belongsTo(User::class, "approved_by"); }

    public function statusColor(): string
    {
        return match ($this->status) {
            "draft"     => "gray",
            "approved"  => "blue",
            "paid"      => "green",
            "cancelled" => "red",
            default     => "gray",
        };
    }

    /**
     * Recalculate totals from items.
     */
    public function recalculate(): void
    {
        $this->update([
            "staff_count"       => $this->items()->count(),
            "total_gross"       => $this->items()->sum("gross_pay"),
            "total_deductions"  => $this->items()->sum("deductions"),
            "total_net"         => $this->items()->sum("net_pay"),
        ]);
    }
}