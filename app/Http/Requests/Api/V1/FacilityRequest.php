<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi data Facility via API.
 */
class FacilityRequest extends FormRequest
{
    /**
     * Policy menangani izin.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi fasilitas.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:150'],
            'icon' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'is_available' => ['sometimes', 'boolean'],
        ];

        if ($this->isMethod('patch') || $this->isMethod('put')) {
            foreach ($rules as $key => $rule) {
                if (! in_array('sometimes', $rule, true)) {
                    array_unshift($rules[$key], 'sometimes');
                }
            }

            $rules['name'][1] = 'required';
        }

        return $rules;
    }
}
