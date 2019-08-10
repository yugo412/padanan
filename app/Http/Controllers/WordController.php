<?php

namespace App\Http\Controllers;

use App\Events\Word\SearchEvent;
use App\Events\Word\StoredEvent;
use App\Http\Requests\Word\ReportRequest;
use App\Http\Requests\Word\StoreRequest;
use App\Models\Category;
use App\Models\Like;
use App\Models\Report;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class WordController extends Controller
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
        ]);

        $words = Word::selectRaw('*, MATCH(origin, locale) AGAINST (\''.$request->katakunci.'\' IN BOOLEAN MODE) as score')
            ->where(function ($query) use ($request){
                return $query->search($request->katakunci ?? '');
            })
            ->when($request->kategori, function ($query) use ($request){
                return $query->whereHas('category', function ($category) use ($request){
                    return $category->whereSlug($request->kategori);
                });
            })
            ->orderByDesc('score')
            ->orderByRaw('LENGTH(origin) ASC')
//            ->withCount('likes')
            ->withCount('reports')
            ->paginate(25);
        $words->appends($request->all());

        // fire word search event
        if (!empty($request->katakunci)) {
            event(new SearchEvent($request->katakunci, $words));
        }

        return \view('word.search', compact('words'))
            ->with('title', $request->katakunci)
            ->with('description', __('Pencarian untuk padanan kata ":origin"', [
                'origin' => $request->katakunci,
            ]));
    }

    /**
     * @param Request $request
     * @param Category $category
     * @return View
     */
    public function category(Request $request, Category $category): View
    {
        $words = Word::where('category_id', $category->id)
            ->orderBy('origin')
            ->withCount('reports')
            ->paginate();
        $words->appends($request->all());

        $number = new \NumberFormatter('id_ID', \NumberFormatter::DECIMAL);
        $total = $number->format($words->total());

        return \view('word.category', compact('words', 'category', 'total'))
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

        $words = Word::orderBy('origin')
            ->paginate();
        $words->appends($request->all());

        $number = new \NumberFormatter('id_ID', \NumberFormatter::DECIMAL);

        return \view('word.index', compact('categories', 'words'))
            ->with('title', __('Daftar lengkap padanan kata dalam berbagai bidang'))
            ->with('description', __('Cari :count padanan kata asing dalam :count_category bidang dalam bahasa Indonesia', [
                'count' => $number->format($words->total()),
                'count_category' => $number->format($categories->count()),
            ]));
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $categories = Cache::remember('category.word.index', now()->addDay(), function (){
            return Category::orderBy('name')
                ->whereIsPublished(true)
                ->get();
        });

        $category = Cache::remember('category.default', now()->addMinutes(30), function () {
            return Category::whereIsDefault(true)->first();
        });

        return \view('word.create', compact('categories', 'category'))
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

        $word = Word::create($request->all());

        if (Auth::check() and $request->has('tweet')) {
            event(new StoredEvent($word));
        }

        return redirect()->route('word.create')
            ->with('success', true);
    }

    /**
     * @param Word $word
     * @return View
     */
    public function show(Word $word): View
    {
        $word->loadCount('reports');

        return \view('word.show', compact('word'))
            ->with('title', __('Padanan istilah :origin adalah :locale', [
                'origin' => $word->origin,
                'locale' => $word->locale,
            ]))
            ->with('description', __('Padanan istilah :origin adalah :locale dalam bidang :category', [
                'origin' => $word->origin,
                'locale' => $word->locale,
                'category' => strtolower($word->category->name),
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
     * @param Word $word
     * @return JsonResponse
     */
    public function love(Word $word): JsonResponse
    {
        $word->increment('total_likes');
        $word->save();

        if (Auth::check()) {
            $word->likes()->save(new Like([
                'user_id' => Auth::id(),
                'metadata' => [],
            ]));
        }

        $word->loadCount('likes');

        return response()->json($word);
    }

    /**
     * @param ReportRequest $request
     * @param Word $word
     * @return JsonResponse
     */
    public function report(ReportRequest $request, Word $word): JsonResponse
    {
        $word->reports()->save(new Report([
            'user_id' => Auth::id(),
            'description' => $request->description,
        ]));

        $word->loadCount('reports');

        return response()->json($word);
    }
}
