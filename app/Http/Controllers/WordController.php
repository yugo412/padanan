<?php

namespace App\Http\Controllers;

use App\Http\Requests\Word\StoreRequest;
use App\Models\Category;
use App\Models\Like;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $words = Word::orderBy('origin')
            ->where(function ($query) use ($request){
                return $query->where('origin', 'LIKE', "%{$request->katakunci}%")
                    ->orWhere('locale', 'LIKE', "%{$request->katakunci}%");
            })
            ->when($request->kategori, function ($query) use ($request){
                return $query->whereHas('category', function ($category) use ($request){
                    return $category->whereSlug($request->kategori);
                });
            })
            ->withCount('likes')
            ->paginate(25);
        $words->appends($request->all());

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
            ->withCount('likes')
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
        $categories = Category::orderBy('name')
            ->whereIsPublished(true)
            ->get();

        return \view('word.create', compact('categories'))
            ->with('title', __('Tambah kata'));
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $category = Category::whereSlug($request->category)->firstOrFail();
        $request->merge([
            'category_id' => $category->id,
        ]);

        $word = Word::create($request->all());

        return redirect()->route('word.create')
            ->with('success', true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category, Word $word): View
    {
        $word->loadCount('likes');

        return \view('word.show', compact('category', 'word'))
            ->with('title', __(':origin - :Locale', [
                'origin' => $word->origin,
                'locale' => $word->locale,
            ]))
            ->with('description', __('Padanan kata :origin adalah :locale dalam bidang :category', [
                'origin' => $word->origin,
                'locale' => $word->locale,
                'category' => $category->name,
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
        $word->likes()->save(new Like([
            'user_id' => Auth::id(),
            'metadata' => [],
        ]));

        $word->loadCount('likes');

        return response()->json($word);
    }
}
