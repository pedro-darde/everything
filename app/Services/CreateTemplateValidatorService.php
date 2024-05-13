<?php

namespace App\Services;

use App\Models\TemplateValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreateTemplateValidatorService
{
    private static array $RULES = [
        'name' => 'required:string',
        'fields' => 'required:array',
        'fields.*.name' => 'required:string',
        'fields.*.label' => 'required:string',
        'fields.*.type' => 'required:string',
        'fields.*.position' => 'required:integer',
        'fields.*.error_message' => 'required:string',
        'fields.*.required' => 'boolean',
        'fields.*.default_value' => 'nullable|string',
        'fields.*.min_length' => 'nullable|integer',
        'fields.*.max_length' => 'nullable|integer',
        'fields.*.pattern' => 'nullable|string',
        'fields.*.unique' => 'nullable|boolean',
        'fields.*.refers_to' => 'nullable|integer',
    ];

    public function create(array $values)
    {
        $dataValidated = Validator::validate($values, self::$RULES);
        try {
            DB::beginTransaction();
            /** @var TemplateValidator $template */
            $template = TemplateValidator::create([
                'name' => $dataValidated['name']
            ]);
            $fields = $dataValidated['fields'];
            $template->fields()->createMany($fields);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

    }
}
