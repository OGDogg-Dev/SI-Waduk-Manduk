<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi untuk TicketType API.
 */
class TicketTypeRequest extends FormRequest
{
    /**
     * Policy menangani otorisasi.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi ticket type.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $ticketTypeId = $this->route('ticket_type')?->id ?? $this->route('ticket_type');

        $rules = [
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'nullable',
                'string',
                'max:160',
                Rule::unique('ticket_types', 'slug')->ignore($ticketTypeId),
            ],
            'weekday_price' => ['required', 'numeric', 'min:0'],
            'weekend_price' => ['required', 'numeric', 'min:0'],
            'holiday_price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];

        if ($this->isMethod('patch') || $this->isMethod('put')) {
            foreach ($rules as $key => $rule) {
                if (! in_array('sometimes', $rule, true)) {
                    array_unshift($rules[$key], 'sometimes');
                }
            }

            foreach (['name', 'weekday_price', 'weekend_price', 'holiday_price'] as $requiredKey) {
                $rules[$requiredKey][1] = 'required';
            }
        }

        return $rules;
    }
}
