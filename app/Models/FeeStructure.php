<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model {
    use HasFactory, BelongsToSchool;

    protected $fillable = [
        "school_id","level","term","year",
        "tuition_fee","transport_fee","meal_fee","development_fee","exam_fee","other_fee","notes",
    ];

    public function total(): float {
        return (float) ($this->tuition_fee + $this->transport_fee + $this->meal_fee
            + $this->development_fee + $this->exam_fee + $this->other_fee);
    }
}