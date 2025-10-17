<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi pengelolaan Inquiry oleh petugas.
 */
class InquiryRequest extends FormRequest
{
    /**
     * Policy menangani otorisasi.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi inquiry internal.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:150'],
            'email' => ['sometimes', 'nullable', 'email', 'max:150'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'type' => ['sometimes', 'required', 'in:PERTANYAAN,SARAN,PENGADUAN'],
            'message' => ['sometimes', 'required', 'string'],
            'status' => ['sometimes', 'required', 'in:BARU,DIPROSES,SELESAI'],
            'handled_by' => ['sometimes', 'nullable', 'exists:users,id'],
        ];
    }
}
