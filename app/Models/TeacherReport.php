<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherReport extends Model {
    use HasFactory;

    protected $fillable = [
        "staff_id","report_type","week_start","week_end",
        "summary","challenges","next_plan","status",
        "review_status","reviewed_by","reviewed_at","head_comment","rating",
        "periods_taught","students_absent",
    ];

    protected $casts = [
        "week_start"      => "date",
        "week_end"        => "date",
        "reviewed_at"     => "datetime",
        "rating"          => "integer",
        "periods_taught"  => "integer",
        "students_absent" => "integer",
    ];

    public function staff()    { return $this->belongsTo(Staff::class); }
    public function reviewer() { return $this->belongsTo(User::class, "reviewed_by"); }

    public function reviewColor(): string
    {
        return match ($this->review_status) {
            "approved"       => "green",
            "rejected"       => "red",
            "needs_revision" => "yellow",
            "under_review"   => "blue",
            default          => "gray",
        };
    }

    public function approve(int $rating, ?string $comment = null): void
    {
        $this->update([
            "review_status" => "approved",
            "reviewed_by"   => auth()->id(),
            "reviewed_at"   => now(),
            "rating"        => $rating,
            "head_comment"  => $comment,
            "status"        => "reviewed",
        ]);
    }

    public function reject(?string $comment = null): void
    {
        $this->update([
            "review_status" => "rejected",
            "reviewed_by"   => auth()->id(),
            "reviewed_at"   => now(),
            "head_comment"  => $comment,
            "status"        => "reviewed",
        ]);
    }

    public function requestRevision(?string $comment = null): void
    {
        $this->update([
            "review_status" => "needs_revision",
            "reviewed_by"   => auth()->id(),
            "reviewed_at"   => now(),
            "head_comment"  => $comment,
        ]);
    }
}