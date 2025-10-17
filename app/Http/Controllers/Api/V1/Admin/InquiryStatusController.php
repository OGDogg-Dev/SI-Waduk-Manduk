<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\InquiryStatusRequest;
use App\Http\Resources\InquiryResource;
use App\Models\Inquiry;

/**
 * Controller khusus perubahan status inquiry.
 */
class InquiryStatusController extends Controller
{
    /**
     * Perbarui status inquiry.
     */
    public function update(InquiryStatusRequest $request, Inquiry $inquiry): InquiryResource
    {
        $this->authorize('update', $inquiry);

        $inquiry->update([
            'status' => $request->string('status')->toString(),
            'handled_by' => auth()->id(),
        ]);

        return new InquiryResource($inquiry->refresh()->load('handler'));
    }
}
