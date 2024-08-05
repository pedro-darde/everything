<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $table = 'movie';

    protected $guarded = ['id'];

    protected $appends = ['poster_url', 'backdrop_url'];

    protected $casts = [
        'extra_info' => 'array'
    ];

    public function genders()
    {
        return $this->hasManyThrough(
            Gender::class,
            MovieGender::class,
            'movie_id',
            'id',
            'id',
            'gender_id'
        );
    }

    public function getPosterUrlAttribute()
    {
        return 'https://image.tmdb.org/t/p/w500' . $this->poster_path;
    }

    public function getBackdropUrlAttribute()
    {
        return 'https://image.tmdb.org/t/p/original' . $this->backdrop_path;
    }
}
