<?php

namespace App\Http\Controllers;

use App\Events\Term\TermViewed;
use App\Events\Word\SearchEvent;
use App\Events\Word\StoredEvent;
use App\Http\Requests\Word\ReportRequest;
use App\Http\Requests\Word\StoreRequest;
use App\Models\Category;
use App\Models\Like;
use App\Models\Report;
use App\Models\Term;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class TermController extends Controller
{
    /**
     * @param Request $request
     * @return View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function search(Request $request): View
    {
        $this->validate($request, [
            'katakunci' => ['nullable', 'string'],
            'e' => ['nullable', 'string'], // now primary
        ]);

        $keyword = $request->e ?? $request->katakunci;

        $terms = Term::selectRaw('*, MATCH(origin, locale) AGAINST (\'' . $keyword . '\' IN BOOLEAN MODE) as score')
            ->where(function ($query) use ($keyword) {
                return $query->search($keyword ?? '');
            })
            ->when($request->kategori, function ($query) use ($request){
                return $query->whereHas('category', function ($category) use ($request){
                    return $category->whereSlug($request->kategori);
                });
            })
            ->orderByDesc('score')
            ->orderByRaw('LENGTH(origin) ASC')
            ->withCount('reports')
            ->paginate(25);
        $terms->appends($request->all());

        // fire term search event
        if (!empty($request->katakunci)) {
            event(new SearchEvent($request->katakunci, $terms));
        }

        return \view('term.search', compact('terms', 'keyword'))
            ->with('title', $keyword)
            ->with('description', __('Pencarian untuk padanan kata ":origin"', [
                'origin' => $keyword,
            ]));
    }

    /**
     * @param Request $request
     * @param Category $category
     * @return View
     */
    public function category(Request $request, Category $category): View
    {
        $terms = Term::where('category_id', $category->id)
            ->orderBy('origin')
            ->orderBy('locale')
            ->withCount('reports')
            ->paginate();
        $terms->appends($request->all());

        $number = new \NumberFormatter('id_ID', \NumberFormatter::DECIMAL);
        $total = $number->format($terms->total());

        return \view('term.category', compact('terms', 'category', 'total'))
            ->with('title', __('Padanan dalam bidang :category', ['category' => $category->name]))
            ->with('description', $category->description);
    }


    /**
     * @return View
     */
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')
            ->get();

        $terms = Term::orderBy('origin')
            ->paginate();
        $terms->appends($request->all());

        $number = new \NumberFormatter('id_ID', \NumberFormatter::DECIMAL);

        return \view('term.index', compact('categories', 'terms'))
            ->with('title', __('Daftar lengkap padanan istilah dalam berbagai bidang'))
            ->with('description', __('Cari :count padanan istilah asing dalam :count_category bidang dalam bahasa Indonesia', [
                'count' => $number->format($terms->total()),
                'count_category' => $number->format($categories->count()),
            ]));
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $categories = Cache::remember('category.term.index', now()->addDay(), function () {
            return Category::orderBy('name')
                ->whereIsPublished(true)
                ->get();
        });

        $category = Cache::remember('category.default', now()->addMinutes(30), function () {
            return Category::whereIsDefault(true)->first();
        });

        return \view('term.create', compact('categories', 'category'))
            ->with('title', __('Tambah Istilah'))
            ->with('description', __('Tambah istilah bahasa asing dan padanan dalam bahasa Indonesia untuk memperkaya kosakata'));
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        if (empty($request->category)) {
            $category = Category::whereIsDefault(true)->first();
        } else {
            $category = Category::whereSlug($request->category)->firstOrFail();
        }

        $request->merge([
            'user_id' => Auth::id(),
            'category_id' => $category->id,
        ]);

        $term = Term::create($request->all());

        if (Auth::check() and $request->has('tweet')) {
            event(new StoredEvent($term));
        }

        return redirect()->route('term.create')
            ->with('term', $term);
    }

    /**
     * @param Term $term
     * @return View
     */
    public function show(Term $term): View
    {
        $term->loadCount('reports');

        event(new TermViewed($term));

        return \view('term.show', compact('term'))
            ->with('title', __('Padanan istilah :origin adalah :locale', [
                'origin' => $term->origin,
                'locale' => $term->locale,
            ]))
            ->with('description', __('Padanan istilah :origin adalah :locale dalam bidang :category', [
                'origin' => $term->origin,
                'locale' => $term->locale,
                'category' => strtolower($term->category->name),
            ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param Term $term
     * @return JsonResponse
     */
    public function love(Term $term): JsonResponse
    {
        $term->increment('total_likes');
        $term->save();

        if (Auth::check()) {
            $term->likes()->save(new Like([
                'user_id' => Auth::id(),
                'metadata' => [],
            ]));
        }

        $term->loadCount('likes');

        return response()->json($term);
    }

    /**
     * @param ReportRequest $request
     * @param Term $term
     * @return JsonResponse
     */
    public function report(ReportRequest $request, Term $term): JsonResponse
    {
        $term->reports()->save(new Report([
            'user_id' => Auth::id(),
            'description' => $request->description,
        ]));

        $term->loadCount('reports');

        return response()->json($term);
    }
}
