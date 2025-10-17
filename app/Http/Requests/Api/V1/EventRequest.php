<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi data Event via API.
 */
class EventRequest extends FormRequest
{
    /**
     * Otorisasi ditangani policy.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi event.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $eventId = $this->route('event')?->id ?? $this->route('event');

        $rules = [
            'title' => ['required', 'string', 'max:180'],
            'slug' => [
                'nullable',
                'string',
                'max:190',
                Rule::unique('events', 'slug')->ignore($eventId),
            ],
            'description' => ['required', 'string'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'venue' => ['nullable', 'string', 'max:150'],
            'is_published' => ['sometimes', 'boolean'],
        ];

        if ($this->isMethod('patch') || $this->isMethod('put')) {
            foreach ($rules as $key => $rule) {
                if (! in_array('sometimes', $rule, true)) {
                    array_unshift($rules[$key], 'sometimes');
                }
            }

            foreach (['title', 'description', 'start_at'] as $key) {
                $rules[$key][1] = 'required';
            }
        }

        return $rules;
    }
}
