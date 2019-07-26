<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function __invoke(Request $request): View
    {
        $categories = Category::orderBy('name')
            ->get();

        $number = new \NumberFormatter('id_ID', \NumberFormatter::DECIMAL);
        $wordCount = $number->format(Word::count());

        return \view('index.index', compact('categories', 'wordCount'))
            ->with('title', __('Cari :count padanan kata asing dalam bahasa Indonesia', ['count' => $wordCount]));
    }
}
