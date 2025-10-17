<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi form kontak publik.
 */
class PublicInquiryRequest extends FormRequest
{
    /**
     * Semua pengunjung boleh mengirim.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk inquiry publik.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'type' => ['required', 'in:PERTANYAAN,SARAN,PENGADUAN'],
            'message' => ['required', 'string'],
        ];
    }
}
