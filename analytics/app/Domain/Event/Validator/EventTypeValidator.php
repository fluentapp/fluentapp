<?php

namespace App\Domain\Event\Validator;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EventTypeValidator implements ValidationRule {

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if ($value !== 'pageview') {
            $fail('The event format is invalid.');
        }
    }
}
