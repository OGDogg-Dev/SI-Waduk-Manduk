<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

/**
 * Controller admin untuk Setting.
 */
class SettingController extends Controller
{
    /**
     * Konstruktor otorisasi.
     */
    public function __construct()
    {
        $this->authorizeResource(Setting::class, 'setting');
    }

    /**
     * Daftar pengaturan.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min($request->integer('per_page', 15), 100);

        $paginator = Setting::query()
            ->when($request->filled('q'), fn ($query) => $query->where('key', 'like', '%'.$request->input('q').'%'))
            ->orderBy('key')
            ->paginate($perPage)
            ->withQueryString();

        return SettingResource::collection($paginator);
    }

    /**
     * Simpan pengaturan baru.
     */
    public function store(SettingRequest $request)
    {
        $setting = Setting::create($request->validated());

        Cache::tags(['settings'])->flush();

        return (new SettingResource($setting))->response()->setStatusCode(201);
    }

    /**
     * Detail pengaturan.
     */
    public function show(Setting $setting): SettingResource
    {
        return new SettingResource($setting);
    }

    /**
     * Perbarui pengaturan.
     */
    public function update(SettingRequest $request, Setting $setting): SettingResource
    {
        $setting->update($request->validated());

        Cache::tags(['settings'])->flush();

        return new SettingResource($setting->refresh());
    }

    /**
     * Hapus pengaturan.
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();

        Cache::tags(['settings'])->flush();

        return response()->noContent();
    }
}
