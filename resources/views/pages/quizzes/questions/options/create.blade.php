@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a class="btn btn-outline-dark mb-3" href="{{ route('quizzes.edit', [$quiz_id]) }}"><i class="fa-solid fa-arrow-left"></i></a>
                <div class="card">
                    <div class="card-header h1">Создание опции</div>
                    <div class="card-body">
                        @if(!empty(session()->get('success')))
                            <div class="alert alert-success" role="alert">
                                {{ session()->get('success')  }}
                            </div>
                        @endif
                        <form action="{{ route('quizzes.store.options', ['id' => $quiz_id, 'question_id' => $question_id]) }}" method="post" class="form d-flex flex-column">
                            @method('post')
                            @csrf
                            <input type="text" name="question_id" value="{{ $question_id }}" hidden>
                            <textarea type="text" name="body" class="rounded-2 p-2 mb-4" placeholder="Текст опции"></textarea>
                            <button class="btn btn-success">Создать</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
