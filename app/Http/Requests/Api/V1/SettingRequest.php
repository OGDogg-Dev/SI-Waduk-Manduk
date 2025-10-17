<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi pengaturan sistem via API.
 */
class SettingRequest extends FormRequest
{
    /**
     * Policy akan menangani otorisasi.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk setting.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $settingId = $this->route('setting')?->id ?? $this->route('setting');

        $rules = [
            'key' => [
                'required',
                'string',
                'max:100',
                Rule::unique('settings', 'key')->ignore($settingId),
            ],
            'value' => ['nullable', 'array'],
        ];

        if ($this->isMethod('patch') || $this->isMethod('put')) {
            foreach ($rules as $key => $rule) {
                if (! in_array('sometimes', $rule, true)) {
                    array_unshift($rules[$key], 'sometimes');
                }
            }

            $rules['key'][1] = 'required';
        }

        return $rules;
    }
}
