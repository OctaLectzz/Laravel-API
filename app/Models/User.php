<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\PasswordResetNotification;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id'
    ];
    protected $attributes = [
        'role' => 'Member'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'created_at_format',
        'updated_at_format'
    ];


    // Relasi
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function saves()
    {
        return $this->belongsToMany(Post::class, 'post_saves');
    }


    // Reset Password
    public function sendPasswordResetNotification($token)
    {
        $url = 'http://localhost:8000/password/reset?token=' . $token;
        $this->notify(new PasswordResetNotification($url, $token));
    }


    // Mutators
    protected function createdAtFormat(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return Carbon::parse($value)->translatedFormat('d F Y');
            },
        );
    }
    
    protected function updatedAtFormat(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return Carbon::parse($value)->translatedFormat('d F Y');
            },
        );
    }
}
