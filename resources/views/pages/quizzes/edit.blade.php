@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="d-flex justify-content-between">
                    <a class="btn btn-outline-dark mb-3" href="{{ route('quizzes') }}"><i class="fa-solid fa-arrow-left"></i></a>
                    @can ('play', [$quiz])
                    <div>
                        <a class="btn btn-success mb-3" href="{{ route('quizzes.play', ['id' => $quiz->quiz_id]) }}"><i class="fa-solid fa-play"></i></a>
                    </div>
                    @endcan
                </div>

                @if(!empty(session()->get('success')))
                    <div class="alert alert-success" role="alert">
                        {{ session()->get('success')  }}
                    </div>
                @endif
                <div class="card">
                    <form action="{{ route('quizzes.update', ['id' => $quiz->quiz_id]) }}" method="post">
                        @method('patch')
                        @csrf
                        <div class="card-header d-flex flex-column justify-content-between align-items-center">
                            <div class="d-flex justify-content-between mt-2 h3 mb-3 w-100">
                                Заголовок
                                <a href="{{ route('quizzes.assign', ['id' => $quiz->quiz_id]) }}" class="btn btn-outline-primary">Назначить студентов</a>
                            </div>

                            @if ($errors->has('title'))
                                <div class="alert alert-danger w-100 pb-0">
                                    <ul>@foreach($errors->get('title') as $message)<li>{{$message}}</li>@endforeach</ul>
                                </div>
                            @endif
                            <input class="rounded-2 h3 p-2 w-100" name="title" type="text" value="{{ old('title') ?? $quiz->title }}">

                        </div>
                        <div class="card-body">
                            <p class="h5 mb-3">Описание</p>

                            @if ($errors->has('description'))
                                <div class="alert alert-danger w-100">
                                    <ul>@foreach($errors->get('description') as $message)<li>{{$message}}</li>@endforeach</ul>
                                </div>
                            @endif
                            <textarea placeholder="Введите описание теста здесь" name="description" type="text" class="rounded-2 p-2 h5 w-100">{{ old('description') ?? $quiz->description }}</textarea>

                            <button type="submit" class="btn btn-success">Сохранить изменения</button>
                        </div>
                    </form>

                    <div class="card-body d-flex align-items-baseline w-100">
                        <p class="mb-0 h3">Вопросы</p>
                        <a href="{{ route('quizzes.create.questions', ['id' => $quiz->quiz_id]) }}" class="btn btn-outline-success ms-2"><i class="fa-solid fa-plus"></i></a>
                    </div>

                    @foreach($questions as $question)
                        <div class="bg-opacity-10 rounded-2 border border-black m-3 card-body">
                            <div class="d-flex h5 justify-content-between align-items-center mb-3">
                                <b class="w-75">{{ $question->body }}</b>
                                <div class="d-flex justify-content-around align-items-center">
                                    <a href="{{ route('quizzes.edit.questions', ['id' => $quiz->quiz_id, $question->question_id]) }}" class="btn btn-outline-primary">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('quizzes.delete.questions', ['id' => $quiz->quiz_id, 'question_id' => $question->question_id]) }}" method="post">
                                        @method('delete')
                                        @csrf
                                        <button class="btn ms-1 btn-outline-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p class=""><b>Тип ответа:</b> {{ $question->type->title }}</p>
                            @foreach($question->options as $option)
                                <p class="@if ($option->is_correct) text-success @endif"><b>Ответ ID{{ $option->option_id }}:</b> {{ $option->body }}</p>
                            @endforeach
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endsection
