<?php

namespace App\Support;

use Illuminate\Support\Collection;

/**
 * Read-only access to config/tools.php.
 *
 * Every place that lists tools - the homepage, the /tools directory, the
 * navigation, the footer and the sitemap - goes through here, so adding a tool
 * to the config makes it appear everywhere at once.
 */
class ToolRegistry
{
    /** @return Collection<int, array> every tool, in config order */
    public static function all(): Collection
    {
        return collect(config('tools.tools', []));
    }

    /** @return Collection<string, array> category key => category definition */
    public static function categories(): Collection
    {
        return collect(config('tools.categories', []));
    }

    /** Categories that actually contain at least one tool, with their tools attached. */
    public static function categoriesWithTools(): Collection
    {
        $byCategory = self::all()->groupBy('category');

        return self::categories()
            ->filter(fn ($category, $key) => $byCategory->has($key) && $byCategory[$key]->isNotEmpty())
            ->map(function ($category, $key) use ($byCategory) {
                $category['key'] = $key;
                $category['tools'] = $byCategory[$key]->values();
                $category['count'] = $byCategory[$key]->count();

                return $category;
            });
    }

    public static function find(string $slug): ?array
    {
        return self::all()->firstWhere('slug', $slug);
    }

    /** Look a tool up by its route path, e.g. "/pdf-toolkit". */
    public static function findByPath(string $path): ?array
    {
        $path = '/' . ltrim($path, '/');

        return self::all()->first(fn ($tool) => $tool['path'] === $path);
    }

    /** Tools flagged as featured, for the homepage. */
    public static function featured(): Collection
    {
        return self::all()->where('featured', true)->values();
    }

    /** Tools in one category. */
    public static function inCategory(string $key): Collection
    {
        return self::all()->where('category', $key)->values();
    }

    public static function category(string $key): ?array
    {
        $category = self::categories()->get($key);

        if ($category === null) {
            return null;
        }

        $category['key'] = $key;

        return $category;
    }

    /** Human-readable category name for a tool, or null. */
    public static function categoryName(array $tool): ?string
    {
        return self::categories()->get($tool['category'] ?? '')['name'] ?? null;
    }

    public static function count(): int
    {
        return self::all()->count();
    }
}
