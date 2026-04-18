<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Collection;

class PortalSettings
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return static::all()->get($key, $default);
    }

    public static function putMany(array $settings, string $group = 'portal'): void
    {
        foreach ($settings as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                [
                    'group' => $group,
                    'value' => blank($value) ? null : $value,
                    'type' => is_string($value) && strlen($value) > 120 ? 'textarea' : 'text',
                ],
            );
        }
    }

    public static function all(): Collection
    {
        return Setting::query()
            ->where('group', 'portal')
            ->pluck('value', 'key');
    }

    public static function youtubeVideoId(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        preg_match('/(?:youtu\.be\/|v=|embed\/)([\w-]{11})/', $url, $matches);

        return $matches[1] ?? null;
    }
}
