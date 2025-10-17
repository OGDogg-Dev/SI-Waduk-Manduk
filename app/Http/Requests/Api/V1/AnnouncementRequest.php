<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi pengelolaan Announcement via API.
 */
class AnnouncementRequest extends FormRequest
{
    /**
     * Policy menangani otorisasi.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi pengumuman.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $announcementId = $this->route('announcement')?->id ?? $this->route('announcement');

        $rules = [
            'title' => ['required', 'string', 'max:180'],
            'slug' => [
                'nullable',
                'string',
                'max:190',
                Rule::unique('announcements', 'slug')->ignore($announcementId),
            ],
            'content' => ['required', 'string'],
            'severity' => ['required', 'in:INFO,WARNING,ALERT'],
            'published_at' => ['nullable', 'date'],
            'expired_at' => ['nullable', 'date', 'after_or_equal:published_at'],
        ];

        if ($this->isMethod('patch') || $this->isMethod('put')) {
            foreach ($rules as $key => $rule) {
                if (! in_array('sometimes', $rule, true)) {
                    array_unshift($rules[$key], 'sometimes');
                }
            }

            foreach (['title', 'content', 'severity'] as $key) {
                $rules[$key][1] = 'required';
            }
        }

        return $rules;
    }
}
