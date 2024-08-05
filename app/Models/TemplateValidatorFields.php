<?php

namespace App\Models;

use App\Enums\FieldType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property FieldType $type
 * @property string $name
 * @property string $error_message
 * @property ?int $max_length
 * @property ?int $min_length
 * @property ?string $pattern
 * @property bool $required
 * @property bool $unique
 * @property mixed $default_value
 * @property int $refers_to
 * @property TemplateValidatorFields $reference
 * @property TemplateValidator $template
 */
class TemplateValidatorFields extends Model
{
    use HasFactory;

    protected $table = 'template_validator_fields';

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'type' => FieldType::class
    ];

    public function reference() {
        return $this->hasOne(TemplateValidatorFields::class, 'id', 'refers_to');
    }

    public function template() {
        return $this->belongsTo(TemplateValidator::class, 'validator_id', 'id');
    }
}
