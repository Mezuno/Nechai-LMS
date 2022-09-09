@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a class="btn btn-outline-dark mb-3" href="{{ route('quizzes.edit', [$quiz_id]) }}"><i class="fa-solid fa-arrow-left"></i></a>
                <div class="card">
                    <div class="card-header h1">Создание вопроса</div>
                    <div class="card-body">
                        <form action="{{ route('quizzes.store.questions', ['id' => $quiz_id]) }}" method="post" class="form d-flex flex-column">
                            @method('post')
                            @csrf
                            <input type="text" name="quiz_id" value="{{ $quiz_id }}" hidden>

                            @if ($errors->has('body'))
                                <div class="alert alert-danger p-2 w-100">
                                    <ul class="mb-0">@foreach($errors->get('body') as $message)<li>{{$message}}</li>@endforeach</ul>
                                </div>
                            @endif
                            <textarea type="text" name="body" class="rounded-2 p-2 mb-4" placeholder="Вопрос"></textarea>
                            <button class="btn btn-success">Создать</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
