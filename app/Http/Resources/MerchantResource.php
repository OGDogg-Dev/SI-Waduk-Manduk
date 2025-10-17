<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;

/**
 * Resource JSON untuk Merchant.
 */
class MerchantResource extends JsonResource
{
    /**
     * Konversi ke array JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $images = [];
        if ($this->resource instanceof HasMedia) {
            $images = $this->getMedia('images')->map(fn ($media) => $media->getUrl())->all();
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'location' => $this->location,
            'excerpt' => $this->location ? Str::limit(strip_tags($this->location), 160) : null,
            'is_verified' => (bool) $this->is_verified,
            'images' => $images,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
