<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieGender extends Model
{
    use HasFactory;

    protected $table = 'movie_gender';

    protected $guarded = ['id'];

    public $timestamps = false;
}
