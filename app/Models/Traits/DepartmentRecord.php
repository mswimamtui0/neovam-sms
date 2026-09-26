<?php

namespace App\Models\Traits;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait DepartmentRecord
{
    public static function bootDepartmentRecord(): void
    {
        static::creating(function ($model) {
            if (auth()->check() && empty($model->staff_id)) {
                $staff = Staff::where("user_id", auth()->id())->first();
                if ($staff) {
                    $model->staff_id    = $staff->id;
                    $model->recorded_by = $staff->full_name;
                }
            }
        });
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function recorder()
    {
        return $this->belongsTo(Staff::class, "staff_id");
    }

    public function scopeForDepartment(Builder $q, $departmentCode)
    {
        $model = $q->getModel();
        if (!$model->departmentCode) {
            return $q;
        }

        return $q->whereHas("staff", function ($s) use ($departmentCode) {
            $s->where("department", "LIKE", "%{$departmentCode}%");
        });
    }
}