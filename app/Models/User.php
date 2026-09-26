<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
 use HasFactory, Notifiable, HasRoles;

 protected $fillable = [
 'name',
 'email',
 'password',
 'phone',
 'role_title',
 'school_id',
 ];

 protected $hidden = [
 'password',
 'remember_token',
 ];

 protected function casts(): array
 {
        return [
            "email_verified_at"      => "datetime",
            "password"               => "hashed",
            "must_change_password"   => "boolean",
            "credentials_sent_at"    => "datetime",
            "last_login_at"          => "datetime",
        ];
    }

    protected function oldCasts(): array
    {
 return [
 'email_verified_at' => 'datetime',
 'password' => 'hashed',
 ];
 }

 public function school()
 {
 return $this->belongsTo(School::class);
 }
}