<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffTransfer extends Model {
    use HasFactory;

    protected $fillable = [
        "staff_id","transfer_type",
        "from_position","to_position",
        "from_department","to_department",
        "from_school","to_school",
        "effective_date","reason","notes",
        "status","requested_by","approved_by","approved_at","approval_notes",
    ];

    protected $casts = [
        "effective_date" => "date",
        "approved_at"    => "datetime",
    ];

    public function staff()       { return $this->belongsTo(Staff::class); }
    public function requester()   { return $this->belongsTo(User::class, "requested_by"); }
    public function approver()    { return $this->belongsTo(User::class, "approved_by"); }

    public function typeLabel(): string
    {
        return match ($this->transfer_type) {
            "transfer_in"   => "Transfer In",
            "transfer_out"  => "Transfer Out",
            "internal_move" => "Internal Move",
            "promotion"     => "Promotion",
            "demotion"      => "Demotion",
            default         => ucfirst($this->transfer_type),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            "approved"  => "green",
            "rejected"  => "red",
            "completed" => "blue",
            "pending"   => "yellow",
            default     => "gray",
        };
    }

    /**
     * Approve the transfer and apply changes to the staff record.
     */
    public function approve(?string $notes = null): void
    {
        $this->update([
            "status"         => "approved",
            "approved_by"    => auth()->id(),
            "approved_at"    => now(),
            "approval_notes" => $notes,
        ]);

        // Apply the changes
        $staff = $this->staff;
        if (!$staff) return;

        $updates = [];
        if ($this->to_position)   $updates["role_title"] = $this->to_position;
        if ($this->to_department) $updates["department"] = $this->to_department;

        if (!empty($updates)) {
            $staff->update($updates);
        }

        // For transfer_out, deactivate the staff
        if ($this->transfer_type === "transfer_out") {
            $staff->update(["status" => "transferred"]);
        }
    }

    public function reject(?string $notes = null): void
    {
        $this->update([
            "status"         => "rejected",
            "approved_by"    => auth()->id(),
            "approved_at"    => now(),
            "approval_notes" => $notes,
        ]);
    }
}