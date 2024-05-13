<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateValidatorFields extends Model
{
    use HasFactory;

    protected $table = 'template_validator_fields';

    protected $guarded = [
        'id'
    ];
}
