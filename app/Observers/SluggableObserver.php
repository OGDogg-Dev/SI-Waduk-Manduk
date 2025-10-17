<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Observer untuk mengisi slug unik otomatis pada model konten.
 */
class SluggableObserver
{
    /**
     * Tangani event creating.
     */
    public function creating(Model $model): void
    {
        $this->prepareSlug($model);
    }

    /**
     * Tangani event updating.
     */
    public function updating(Model $model): void
    {
        $this->prepareSlug($model);
    }

    /**
     * Siapkan slug unik berdasarkan atribut name/title.
     */
    protected function prepareSlug(Model $model): void
    {
        $currentSlug = (string) ($model->slug ?? '');

        if ($currentSlug !== '') {
            $model->slug = $this->uniqueSlug($model, Str::slug($currentSlug));

            return;
        }

        $source = $model->name ?? $model->title ?? null;

        if (empty($source)) {
            return;
        }

        $baseSlug = Str::slug($source);

        if ($baseSlug === '') {
            return;
        }

        $model->slug = $this->uniqueSlug($model, $baseSlug);
    }

    /**
     * Pastikan slug tidak bentrok di basis data.
     */
    protected function uniqueSlug(Model $model, string $baseSlug): string
    {
        $slug = $baseSlug;
        $suffix = 1;

        while ($this->slugExists($model, $slug)) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    /**
     * Cek apakah slug sudah digunakan entitas lain.
     */
    protected function slugExists(Model $model, string $slug): bool
    {
        $query = $model->newQuery()->where('slug', $slug);

        if ($model->exists) {
            $query->whereKeyNot($model->getKey());
        }

        return $query->exists();
    }
}
