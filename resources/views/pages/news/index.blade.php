@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @if (auth()->user()->is_teacher == 1)
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <p class="h4 mb-0">Добавить новость</p>
                        <button class="btn rounded-circle" data-bs-toggle="collapse" href="#collapseCreateNews">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <div class="collapse" id="collapseCreateNews">
                        <div class="card-body d-flex flex-column">
                            <form action="{{ route('news.store') }}" class="d-flex flex-column" method="post">
                                @method('post')
                                @csrf
                                @if ($errors->has('title'))
                                    <div class="alert alert-danger w-100">
                                        <ul>@foreach($errors->get('title') as $message)<li>{{$message}}</li>@endforeach</ul>
                                    </div>
                                @endif
                                <input type="text" name="title" placeholder="Заголовок новости"
                                    class="mb-3 p-2 rounded-2 border">
                                @if ($errors->has('body'))
                                    <div class="alert alert-danger w-100">
                                        <ul>@foreach($errors->get('body') as $message)<li>{{$message}}</li>@endforeach</ul>
                                    </div>
                                @endif
                                <textarea name="body" placeholder="Текст новости" id="" cols="30" rows="10"
                                          class="mb-3 p-2 rounded-2 border"></textarea>
                                @if ($errors->has('video_link'))
                                    <div class="alert alert-danger w-100">
                                        <ul>@foreach($errors->get('video_link') as $message)<li>{{$message}}</li>@endforeach</ul>
                                    </div>
                                @endif
                                <input type="text" name="video_link" placeholder="YouTube video link"
                                       class="mb-3 p-2 rounded-2 border">
                                <div class="d-flex justify-content-between align-items-center">

                                    @if ($errors->has('news_category_id'))
                                        <div class="alert alert-danger w-100"><ul>@foreach($errors->get('news_category_id') as $message)<li>{{$message}}</li>@endforeach</ul></div>
                                    @endif
                                    <select name="news_category_id" id="" class="p-2 rounded-2 border mb-2 w-25">
                                        @forelse($categories as $category)
                                            <option value="{{ $category->news_category_id }}">{{ $category->title }}</option>
                                        @empty
                                            Категорий нет
                                        @endforelse
                                    </select>


                                    <div class="d-flex mb-3">
                                        <input class="p-2 rounded-2 border" form="create-news-category" placeholder="Введите название категории" type="text" name="title">
                                        <button form="create-news-category" class="btn ms-2 btn-success">Добавить</button>
                                    </div>

                                </div>
                                <button class="btn btn-success">Добавить</button>
                            </form>
                        </div>
                    </div>
                </div>
                <form method="post" action="{{ route('news.category.store') }}" class="d-flex" id="create-news-category">
                    @method('post')
                    @csrf
                </form>
                @endif


                @if(!empty(session()->get('success')))
                    <div class="alert alert-success" role="alert">
                        {{ session()->get('success')  }}
                    </div>
                @endif

                <div class="d-flex">
                    <form action="{{ route('news') }}">
                        <input name="category" value="" type="text" hidden>
                        <button class="alert alert-danger p-1">Все</button>
                    </form>
                    @foreach($categories as $category)
                        <form action="{{ route('news') }}">
                            <input name="category" value="{{ $category->news_category_id }}" type="text" hidden>
                            <button class="ms-2 alert alert-primary p-1">{{ $category->title }}</button>
                        </form>
                    @endforeach
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <p class="h4 mb-0">Новости</p>
                    </div>

                    <div class="card-body">
                        @forelse($news as $new)
                            <div class="alert alert-secondary d-flex flex-column">
                                <div class="d-flex justify-content-between mb-4">
                                    <div>
                                        <span class="h5">{{ $new->title }}</span>
                                        @if (!empty($new->category))
                                            <span class="alert alert-primary p-1 ms-2">{{ $new->category->title }}</span>
                                        @endif
                                    </div>
                                    @if (auth()->user()->is_teacher == 1)
                                        <form method="post" action="{{ route('news.delete', ['id' => $new->news_id]) }}">
                                            @method('delete')
                                            @csrf
                                            <button class="btn btn-outline-danger mb-0">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <p class="mb-4">{{ $new->body }}</p>
                                @if (!empty($new->video_link))
                                    @if((new \Jenssegers\Agent\Agent())->isDesktop())
                                        <iframe width="560" height="315"
                                                src="https://www.youtube.com/embed/{{ $new->video_link }}"
                                                title="YouTube video player"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen
                                                class="mb-4">
                                        </iframe>
                                    @endif
                                    @if((new \Jenssegers\Agent\Agent())->isMobile())
                                            <iframe height="200"
                                                    src="https://www.youtube.com/embed/{{ $new->video_link }}"
                                                    title="YouTube video player"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen
                                                    class="mb-4 w-100">
                                            </iframe>
                                    @endif
                                @endif
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0 text-secondary">Автор записи: {{ $new->author->name }}</p>
                                    <p class="mb-0 text-secondary">{{ $new->created_at }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="mb-0">Новостей пока нету ;с</p>
                        @endforelse
                    </div>
                </div>
                    {{ $news->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
