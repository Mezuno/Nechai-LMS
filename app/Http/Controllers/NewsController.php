<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsCreateRequest;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('news_id', '>', 0)
            ->with('author')
            ->orderByDesc('news_id')
            ->paginate(8);
        return view('pages.news.index', compact('news'));
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
}
