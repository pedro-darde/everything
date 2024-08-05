<?php

namespace App\Services\Validator;

use App\Models\TemplateValidator;

trait RuleCreator
{

    private static function defineRulesForTemplate(TemplateValidator $template): void
    {
        $fields = $template->fields;
        foreach ($fields as $field) {
            $fieldRules = [
                $field->required ? 'required' : 'nullable',
                $field->type
            ];

            if ($field->min_length) {
                $fieldRules[] = "min_length:{$field->min_length}";
            }

            if ($field->max_length) {
                $fieldRules[] = "max_length:{$field->max_length}";
            }

            if ($field->refers_to) {
                $referenceField = $field->reference;
                $fieldRules[] = "exists:{$field->refers_to}";
            }

            static::$RULES[$field->name] = implode("|", $fieldRules);
        }
    }
}
