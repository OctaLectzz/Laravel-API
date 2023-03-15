<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Tag extends Model
{
    use HasApiTokens, HasFactory;
    

    protected $guarded = [
        'id'
    ];

    protected $attributes =[
        'created_by' => ''
    ];

    protected $appends = [
        'created_at_format',
        'updated_at_format'
    ];


    // Relasi
    public function posts()
    {
        return $this->belongsToMany(Post::class, "post_tag", "tag_id", "post_id");
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
