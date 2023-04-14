<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Comment extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $guarded = [
        'id'
    ];

    protected $appends = [
        'created_at_format',
        'updated_at_format'
    ];


    // Relasi
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    
    public function toArray()
    {
        $array = parent::toArray();
        $array['name'] = $this->user->name;
        return $array;
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
