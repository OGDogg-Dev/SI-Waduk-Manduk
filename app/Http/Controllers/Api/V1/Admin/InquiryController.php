<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\InquiryRequest;
use App\Http\Resources\InquiryResource;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controller admin untuk Inquiry.
 */
class InquiryController extends Controller
{
    /**
     * Konstruktor otorisasi.
     */
    public function __construct()
    {
        $this->authorizeResource(Inquiry::class, 'inquiry');
    }

    /**
     * Daftar inquiry.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('per_page', 15), 100);

        $paginator = Inquiry::query()
            ->with('handler')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->input('type')))
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return InquiryResource::collection($paginator);
    }

    /**
     * Buat inquiry internal.
     */
    public function store(InquiryRequest $request)
    {
        $data = $request->validated();
        if (isset($data['status'])) {
            $data['handled_by'] = auth()->id();
        }

        $inquiry = Inquiry::create($data);

        return (new InquiryResource($inquiry->load('handler')))->response()->setStatusCode(201);
    }

    /**
     * Detail inquiry.
     */
    public function show(Inquiry $inquiry): InquiryResource
    {
        return new InquiryResource($inquiry->load('handler'));
    }

    /**
     * Perbarui inquiry.
     */
    public function update(InquiryRequest $request, Inquiry $inquiry): InquiryResource
    {
        $data = $request->validated();
        if (isset($data['status'])) {
            $data['handled_by'] = auth()->id();
        }

        $inquiry->update($data);

        return new InquiryResource($inquiry->refresh()->load('handler'));
    }

    /**
     * Hapus inquiry.
     */
    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();

        return response()->noContent();
    }
}
