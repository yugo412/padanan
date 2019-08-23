<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function __invoke(Request $request): View
    {
        $expiration = app()->environment('local') ? now()->addSecond() : now()->addDay();

        $categories = Cache::remember('category.index', $expiration, function () {
            return Category::orderBy('name')
                ->whereIsPublished(true)
                ->withCount('terms')
                ->whereHas('terms')
                ->get();
        });

        $number = new \NumberFormatter('id_ID', \NumberFormatter::DECIMAL);
        $termCount = $number->format(
            Cache::remember('terms.count', $expiration, function () {
                return Term::count();
            })
        );

        return \view('index', compact('categories', 'termCount', 'number'))
            ->with('title', __('Cari :count padanan istilah asing dalam bahasa Indonesia', ['count' => $termCount]));
    }
}
