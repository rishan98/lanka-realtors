<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StoredFile
{
    public static function normalizePath(?string $path): ?string
    {
        if (! is_string($path) || trim($path) === '') {
            return null;
        }

        $path = str_replace('\\', '/', trim($path));

        if (str_contains($path, '://')) {
            $path = parse_url($path, PHP_URL_PATH) ?? '';
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return $path !== '' ? $path : null;
    }

    public static function delete(?string $path): void
    {
        $normalized = self::normalizePath($path);

        if ($normalized) {
            Storage::disk('public')->delete($normalized);
        }
    }

    public static function deleteMany(array $paths): void
    {
        foreach ($paths as $path) {
            self::delete($path);
        }
    }

    /**
     * @param  array<int, string>  $fields
     * @return array<int, string|null>
     */
    public static function originalPathsForDirtyFields(Model $model, array $fields): array
    {
        $paths = [];

        foreach ($fields as $field) {
            if ($model->isDirty($field)) {
                $paths[] = $model->getOriginal($field);
            }
        }

        return $paths;
    }

    public static function purgeModelFiles(Model $model, array $fields): void
    {
        $paths = [];

        foreach ($fields as $field) {
            $paths[] = $model->{$field};
        }

        self::deleteMany($paths);
    }
}
