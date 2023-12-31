<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\Rule;

class UpdateSiteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $siteId = $this->input('site_id');
        return [
            'domain_name' => [
                'required',
                Rule::unique('sites', 'fqdn')->ignore($siteId),
                function ($attribute, $value, $fail) {
                    if (!filter_var($value, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
                        $fail("The $attribute is not a valid domain.");
                    }
                },
            ],
            'timezone' => [
                'required',
                Rule::in(timezone_identifiers_list()),
            ],
        ];
    }
}
