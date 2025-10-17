<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi untuk pembuatan dan pembaruan Attraction via API.
 */
class AttractionRequest extends FormRequest
{
    /**
     * Selalu izinkan, otorisasi diatur oleh policy.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi input attraction.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $attractionId = $this->route('attraction')?->id ?? $this->route('attraction');

        $baseRules = [
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'nullable',
                'string',
                'max:160',
                Rule::unique('attractions', 'slug')->ignore($attractionId),
            ],
            'type' => ['required', 'in:WAHANA,SPOT,GENERAL'],
            'description' => ['nullable', 'string'],
            'base_price' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];

        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $baseRules['name'][0] = 'sometimes';
            array_splice($baseRules['name'], 1, 0, 'required');

            $baseRules['type'][0] = 'sometimes';
            array_splice($baseRules['type'], 1, 0, 'required');

            foreach ($baseRules as $key => $rules) {
                if (! in_array('sometimes', $rules, true)) {
                    array_unshift($baseRules[$key], 'sometimes');
                }
            }
        }

        return $baseRules;
    }
}
