<?php

namespace App\Http\Controllers;

use App\Http\Requests\Word\StoreRequest;
use App\Models\Category;
use App\Models\Word;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            ->paginate(25);
        $words->appends($request->all());

        return \view('word.search', compact('words'))
            ->with('title', $request->katakunci);
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
            ->paginate();
        $words->appends($request->all());

        $number = new \NumberFormatter('id_ID', \NumberFormatter::DECIMAL);
        $total = $number->format($words->total());

        return \view('word.category', compact('words', 'category', 'total'))
            ->with('title', $category->name);
    }


    /**
     * @return View
     */
    public function index(): View
    {

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
    public function show($id)
    {
        //
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
}
