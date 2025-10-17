<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi untuk OperatingHour API.
 */
class OperatingHourRequest extends FormRequest
{
    /**
     * Otorisasi ditangani policy.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi data operating hour.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'open_time' => ['nullable', 'date_format:H:i', 'required_without:is_closed'],
            'close_time' => ['nullable', 'date_format:H:i', 'required_without:is_closed', 'after:open_time'],
            'is_closed' => ['sometimes', 'boolean'],
            'attraction_id' => ['nullable', 'exists:attractions,id'],
        ];

        if ($this->isMethod('patch') || $this->isMethod('put')) {
            foreach ($rules as $key => $rule) {
                if (! in_array('sometimes', $rule, true)) {
                    array_unshift($rules[$key], 'sometimes');
                }
            }
        }

        return $rules;
    }
}
