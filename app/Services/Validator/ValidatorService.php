<?php

namespace App\Services\Validator;

use App\Models\TemplateValidator;

class ValidatorService
{
    use RuleCreator;

    protected static array $RULES = [];
    public function __construct(
        private readonly TemplateValidator $templateValidator
    )
    {
        static::defineRulesForTemplate($this->templateValidator);
    }

    public function validate(array $file)
    {

    }
}
