<?php

namespace App\Services;

use App\Models\TemplateValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EditTemplateValidatorService
{
    private static $RULES = [
        'id' => 'required:exists:template_validators,id',
        'name' => 'required:string',
        'fields' => 'required:array',
        'fields.*.id' => 'nullable:integer',
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
        'ids_fields_delete' => 'nullable|array',
    ];

    public function edit(array $values): void
    {
        $dataValidated = Validator::validate($values, self::$RULES);
        try {
            DB::beginTransaction();
            /** @var TemplateValidator $template */
            $template = TemplateValidator::find($dataValidated['id']);
            $template->update([
                'name' => $dataValidated['name']
            ]);
            $fields = $dataValidated['fields'];

            $fieldsToCreate = array_filter($fields, fn ($field) => !isset($field['id']));
            $fieldsToUpdate = array_filter($fields, fn ($field) => isset($field['id']));

            $template->fields()->createMany($fieldsToCreate);

            foreach ($fieldsToUpdate as $field) {
                $template->fields()->find($field['id'])->update($field);
            }

            if (isset($dataValidated['ids_fields_delete'])) {
                $template->fields()->whereIn('id', $dataValidated['ids_fields_delete'])->delete();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
