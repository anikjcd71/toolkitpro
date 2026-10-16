<?php
// Load curated catalogue entries not yet present in the database.
$catalogPath = __DIR__ . '/../config/tool-catalog.json';
if (is_file($catalogPath)) {
    $catalog = json_decode((string) file_get_contents($catalogPath), true);
    if (is_array($catalog) && is_array($catalog['categories'] ?? null)) {
        $knownSlugs = [];
        foreach ($tools as $tool) {
            $knownSlugs[(string) ($tool['slug'] ?? '')] = true;
        }
        $knownCategories = [];
        foreach ($categories as $category) {
            $knownCategories[(string) ($category['slug'] ?? '')] = true;
        }
        foreach ($catalog['categories'] as $catalogCategory) {
            $categoryName = (string) ($catalogCategory['name'] ?? 'Other Tools');
            $categorySlug = (string) ($catalogCategory['slug'] ?? '');
            if ($categorySlug === '') {
                continue;
            }
            if (!isset($knownCategories[$categorySlug])) {
                $categories[] = ['name' => $categoryName, 'slug' => $categorySlug];
                $knownCategories[$categorySlug] = true;
            }
            foreach (($catalogCategory['tools'] ?? []) as $catalogTool) {
                $slug = (string) ($catalogTool['slug'] ?? '');
                if ($slug === '' || isset($knownSlugs[$slug])) {
                    continue;
                }
                if ($cat !== '' && $cat !== $categorySlug) { continue; }
                $toolName = (string) ($catalogTool['name'] ?? $slug);
                if ($q !== '' && stripos($toolName, $q) === false && stripos($categoryName, $q) === false) { continue; }
                $status = (string) ($catalogTool['status'] ?? 'upcoming');
                $tools[] = [
                    'name' => $toolName,
                    'slug' => $slug,
                    'description' => $status === 'live'
                        ? 'আপনার কাজ দ্রুত ও সহজে সম্পন্ন করুন।'
                        : 'এই টুলটি তৈরি হচ্ছে। প্রস্তুত হলে এখানে ব্যবহার করতে পারবেন।',
                    'category_name' => $categoryName,
                    'category_slug' => $categorySlug,
                    'tool_url' => $status === 'live' ? (string) ($catalogTool['url'] ?? '') : '',
                    'status' => $status,
                ];
                $knownSlugs[$slug] = true;
            }
        }
    }
}
