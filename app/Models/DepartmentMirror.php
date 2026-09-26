<?php
namespace App\Models;
use App\Models\Traits\BelongsToSchool;
use Illuminate\Database\Eloquent\Model;

class DepartmentMirror extends Model {
    use BelongsToSchool;
    protected $fillable = ["school_id","source_table","source_id","target_department","summary","read_only"];
    protected $casts = ["read_only" => "boolean"];
}