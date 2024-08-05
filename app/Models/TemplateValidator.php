<?php

namespace App\Models;

use App\Enums\FieldType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read TemplateValidatorFields[] $fields
 * @property string $name
 */
class TemplateValidator extends Model
{
    use HasFactory;

    protected $table = 'template_validator';

    protected $fillable = [
        'name'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function fields(): HasMany
    {
        return $this
            ->hasMany(TemplateValidatorFields::class, 'validator_id', 'id')
            ->orderBy('position', 'asc');
    }
}
