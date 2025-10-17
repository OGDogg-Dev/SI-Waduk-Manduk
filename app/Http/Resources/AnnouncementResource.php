<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;

/**
 * Resource JSON untuk Announcement.
 */
class AnnouncementResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'excerpt' => Str::limit(strip_tags($this->content), 160),
            'severity' => $this->severity,
            'published_at' => optional($this->published_at)->toIso8601String(),
            'expired_at' => optional($this->expired_at)->toIso8601String(),
            'images' => $images,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
