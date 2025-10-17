<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi data Closure via API.
 */
class ClosureRequest extends FormRequest
{
    /**
     * Policy yang mengatur otorisasi.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi penutupan area.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'reason' => ['required', 'string'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'attraction_id' => ['nullable', 'exists:attractions,id'],
        ];

        if ($this->isMethod('patch') || $this->isMethod('put')) {
            foreach ($rules as $key => $rule) {
                if (! in_array('sometimes', $rule, true)) {
                    array_unshift($rules[$key], 'sometimes');
                }
            }

            $rules['reason'][1] = 'required';
            $rules['start_at'][1] = 'required';
        }

        return $rules;
    }
}
