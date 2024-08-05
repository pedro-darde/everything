<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trending extends Model
{
    use HasFactory;

    protected $table = 'trending';

    protected $guarded = ['id'];

    public function movie() {
        return $this->belongsTo(Movie::class, 'movie_id', 'id');
    }

}
