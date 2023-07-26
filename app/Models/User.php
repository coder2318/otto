<?php

namespace App\Models;

use App\Data\User\Details;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Billable;

    protected $fillable = [
        'email',
        'password',
        'details',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'details' => Details::class,
    ];

    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    public function chapters()
    {
        return $this->hasManyThrough(Chapter::class, Story::class);
    }
}
