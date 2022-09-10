<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsCreateRequest;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->input('category');
        $news = News::where('news_id', '>', 0)
            ->with('author')
            ->with('category')
            ->orderByDesc('news_id')
            ->search($category)
            ->paginate(8);
        $categories = NewsCategory::all();
        return view('pages.news.index', compact('news', 'categories'));
    }

    public function store(NewsCreateRequest $request)
    {
        $validated = $request->validated();
        $validated['author_id'] = auth()->id();
        News::create($validated);
        return redirect()->route('news')
            ->with(['success' => __('success.'. __FUNCTION__ .'News')]);
    }

    public function update()
    {

    }

    public function destroy(int $id)
    {
        News::where('news_id', $id)->delete();
        return redirect()->route('news')
            ->with(['success' => __('success.'. __FUNCTION__ .'News')]);
    }

    public function restore()
    {

    }

    public function storeCategory(Request $request)
    {
        $validated = $request->all();
        NewsCategory::create($validated);
        return redirect()->route('news')
            ->with(['success' => __('success.'. __FUNCTION__ .'News')]);
    }
}
