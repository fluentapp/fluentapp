<?php

namespace App\Domain\Event\Validator;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FqdnValidation implements ValidationRule {

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if (!preg_match('/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i', $value)) {
            $fail('The domain format is invalid.');
        }
    }
}
