<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Post extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $guarded = [
        'id'
    ];
    protected $attributes = [
        'views' => 0
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
