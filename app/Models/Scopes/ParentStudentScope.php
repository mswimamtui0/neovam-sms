<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ParentStudentScope implements Scope
{
 public function apply(Builder $builder, Model $model): void
 {
 if (!auth()->check()) return;

 $user = auth()->user();

 // Parent — only their own children
 if ($user->hasRole("parent")) {
 $builder->where("parent_phone", $user->phone);
 return;
 }

 // Student — only themselves
 if ($user->hasRole("student")) {
 $builder->where("user_id", $user->id);
 return;
 }

 // Teacher — only their assigned classes
 if ($user->hasRole("teacher") || $user->hasRole("teacher_on_duty")) {
 $staff = \App\Models\Staff::where("user_id", $user->id)->first();
 if ($staff && $staff->assigned_classrooms) {
 $ids = array_filter(explode(",", $staff->assigned_classrooms));
 $builder->whereIn("classroom_id", $ids);
 } else {
 // No classes assigned — no students visible
 $builder->whereRaw("1 = 0");
 }
 return;
 }
 }
}