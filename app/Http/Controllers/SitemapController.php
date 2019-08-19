<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Word;
use Illuminate\Support\Facades\App;

class SitemapController extends Controller
{
    /**
     * Generate sitemap index by categories.
     *
     * @return mixed
     */
    public function index()
    {
        $sitemap = App::make('sitemap');
        $sitemap->setCache('sitemap.index', 3600 * 24);

        $categories = Category::orderBy('name')
            ->whereHas('words')
            ->whereIsPublished(true)
            ->get();

        foreach ($categories as $category) {
            $sitemap->addSitemap(route('sitemap.word', $category), $category->updated_at);
        }

        return $sitemap->render('sitemapindex');
    }

    /**
     * Build word sitemap filtered by category.
     *
     * @param Category $category
     * @return mixed
     */
    public function word(Category $category)
    {
        $words = Word::whereCategoryId($category->id)
            ->orderByDesc('total_likes')
            ->orderBy('origin')
            ->orderBy('locale')
            ->get();

        $sitemap = App::make('sitemap');
        $sitemap->setCache('sitemap.word.' . $category->slug, 3600 * 24);

        foreach ($words as $word) {
            $sitemap->add(route('word.show', $word), $word->updated_at);
        }

        return $sitemap->render('xml');
    }
}
