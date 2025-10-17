<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;

/**
 * Resource JSON untuk Event.
 */
class EventResource extends JsonResource
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
        $cover = null;

        if ($this->resource instanceof HasMedia) {
            $images = $this->getMedia('images')->map(fn ($media) => $media->getUrl())->all();
            $cover = $this->getFirstMedia('cover')?->getUrl();
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'excerpt' => Str::limit(strip_tags($this->description), 160),
            'start_at' => optional($this->start_at)->toIso8601String(),
            'end_at' => optional($this->end_at)->toIso8601String(),
            'venue' => $this->venue,
            'is_published' => (bool) $this->is_published,
            'images' => $images,
            'cover' => $cover,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
