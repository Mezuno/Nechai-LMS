@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                @if (auth()->user()->is_teacher == 1)
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <p class="h4 mb-0">Добавить новость</p>
                        <button class="btn rounded-circle" data-bs-toggle="collapse" href="#collapseExample">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <div class="collapse" id="collapseExample">
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
                                <button class="btn btn-success">Добавить</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                @if(!empty(session()->get('success')))
                    <div class="alert alert-success" role="alert">
                        {{ session()->get('success')  }}
                    </div>
                @endif

                <div class="card">

                    <div class="card-header">
                        <p class="h4 mb-0">Новости</p>
                    </div>

                    <div class="card-body">
                        @forelse($news as $new)
                            <div class="alert alert-secondary d-flex flex-column">
                                <div class="d-flex justify-content-between mb-4">
                                    <p class="h5">{{ $new->title }}</p>
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
                                    <iframe width="560" height="315"
                                            src="https://www.youtube.com/embed/{{ $new->video_link }}"
                                            title="YouTube video player"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen
                                            class="mb-4">
                                    </iframe>
                                @endif
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0 text-secondary">Автор записи: {{ $new->author->name }}</p>
                                    <p class="mb-0 text-secondary">{{ $new->created_at }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="mb-0">Новостей пока нету ;с</p>
                        @endforelse
                        {{ $news->withQueryString()->links() }}
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
