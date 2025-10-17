<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;

/**
 * Resource untuk serialisasi Attraction ke JSON.
 */
class AttractionResource extends JsonResource
{
    /**
     * Konversi resource ke array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $images = [];
        $cover = null;

        if ($this->resource instanceof HasMedia) {
            $images = $this->getMedia('images')->map(fn ($media) => $media->getUrl())->all();
            $coverMedia = $this->getFirstMedia('cover');
            $cover = $coverMedia?->getUrl();
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
            'description' => $this->description,
            'excerpt' => $this->description ? Str::limit(strip_tags($this->description), 160) : null,
            'base_price' => $this->base_price,
            'is_active' => (bool) $this->is_active,
            'geo' => [
                'lat' => $this->latitude,
                'lng' => $this->longitude,
            ],
            'images' => $images,
            'cover' => $cover,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
