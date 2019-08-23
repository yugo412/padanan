<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Term;
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
            ->whereHas('terms')
            ->whereIsPublished(true)
            ->get();

        foreach ($categories as $category) {
            $sitemap->addSitemap(route('sitemap.term', $category), $category->updated_at);
        }

        return $sitemap->render('sitemapindex');
    }

    /**
     * Build word sitemap filtered by category.
     *
     * @param Category $category
     * @return mixed
     */
    public function term(Category $category)
    {
        $terms = Term::whereCategoryId($category->id)
            ->orderByDesc('total_likes')
            ->orderBy('origin')
            ->orderBy('locale')
            ->get();

        $sitemap = App::make('sitemap');
        $sitemap->setCache('sitemap.term.' . $category->slug, 3600 * 24);

        foreach ($terms as $term) {
            $sitemap->add(route('term.show', $term), $term->updated_at);
        }

        return $sitemap->render('xml');
    }
}
