<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi khusus perubahan status inquiry.
 */
class InquiryStatusRequest extends FormRequest
{
    /**
     * Policy sudah menangani otorisasi.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi status inquiry.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'in:BARU,DIPROSES,SELESAI'],
        ];
    }
}
