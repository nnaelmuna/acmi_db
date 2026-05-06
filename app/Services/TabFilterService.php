<?php

namespace App\Services;

class TabFilterService
{
    /**
     * Generate tabs dengan hitungan untuk model apapun.
     * Model WAJIB menggunakan trait SoftDeletes.
     *
     * @param  string  $modelClass  — misal: Post::class, Page::class
     * @return array
     */
    public static function getTabs(string $modelClass): array
    {
        return [
            [
                'label' => 'Published',
                'count' => $modelClass::where('status', 'published')->count(),
            ],
            [
                'label' => 'Draft',
                'count' => $modelClass::where('status', 'draft')->count(),
            ],
            [
                'label' => 'Archived',
                'count' => $modelClass::where('status', 'archived')->count(),
            ],
            [
                'label' => 'Trash',
                // onlyTrashed() butuh SoftDeletes di model
                'count' => $modelClass::onlyTrashed()->count(),
            ],
        ];
    }
}