<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\PublicInquiryRequest;
use App\Http\Resources\InquiryResource;
use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;

/**
 * Controller untuk formulir kontak publik.
 */
class InquiryController
{
    /**
     * Simpan inquiry dari pengunjung.
     */
    public function store(PublicInquiryRequest $request): JsonResponse
    {
        $inquiry = Inquiry::create($request->validated());

        return (new InquiryResource($inquiry))
            ->response()
            ->setStatusCode(201);
    }
}
