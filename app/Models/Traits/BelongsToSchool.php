<?php

namespace App\Models\Traits;

use App\Models\School;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToSchool
{
 protected static function bootBelongsToSchool(): void
 {
 static::creating(function ($model) {
 if (auth()->check() && auth()->user()->school_id && !$model->school_id) {
 $model->school_id = auth()->user()->school_id;
 }
 });

 static::addGlobalScope('school', function (Builder $builder) {
 if (auth()->check() && auth()->user()->school_id) {
 $builder->where('school_id', auth()->user()->school_id);
 }
 });
 }

 public function school()
 {
 return $this->belongsTo(School::class);
 }
}