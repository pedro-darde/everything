<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Watchlist extends Model
{
    use HasFactory;

    protected $table = 'watchlist';
    protected $guarded = ['id'];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
