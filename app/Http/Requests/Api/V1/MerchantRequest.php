<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi data Merchant via API.
 */
class MerchantRequest extends FormRequest
{
    /**
     * Policy menangani otorisasi.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi merchant.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:150'],
            'category' => ['required', 'in:KULINER,SOUVENIR,SEWA_PERALATAN,LAINNYA'],
            'phone' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'location' => ['nullable', 'string'],
            'is_verified' => ['sometimes', 'boolean'],
        ];

        if ($this->isMethod('patch') || $this->isMethod('put')) {
            foreach ($rules as $key => $rule) {
                if (! in_array('sometimes', $rule, true)) {
                    array_unshift($rules[$key], 'sometimes');
                }
            }

            $rules['name'][1] = 'required';
            $rules['category'][1] = 'required';
        }

        return $rules;
    }
}
