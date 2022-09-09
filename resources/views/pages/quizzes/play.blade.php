@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="d-flex justify-content-between">
                    <a class="btn btn-dark mb-3" href="{{ route('quizzes.assigned') }}"><i class="fa-solid fa-arrow-left"></i></a>
                    @if (auth()->user()->is_teacher)
                        <a class="btn btn-primary mb-3" href="{{ route('quizzes.edit', ['id' => $quiz->quiz_id]) }}"><i class="fa-solid fa-pen"></i></a>
                    @endif
                </div>

                @if(!empty(session()->get('result')))
                    <div class="alert alert-success w-100" role="alert">
                        {{ session()->get('result')  }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <p class="fw-bold mt-2 h3 w-100">{{ $quiz->title }}</p>
                    </div>
                    <div class="card-body">

                        <form method="post" action="{{ route('quizzes.check', ['id' => $quiz->quiz_id]) }}">

                        @method('post')
                        @csrf

                        @foreach($questions as $question)
                            <p class="fw-bold mt-3 mb-0">{{ $question->body }}</p>
                            @if ($question->option_type_id == 1)
                                @foreach($question->options as $option)
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="{{ $question->question_id }}" value="{{ $option->option_id }}"><span class="ms-2">{{ $option->body }}</span>
                                    </div>
                                @endforeach
                            @endif
                            @if ($question->option_type_id == 3)
                                @foreach($question->options as $option)
                                    <div class="d-flex align-items-center">
                                        <input name="{{ $question->question_id }}[]" value="{{ $option->option_id }}" type="checkbox"><span class="ms-2">{{ $option->body }}</span>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach

                            <button class="btn mt-4 btn-success">Завершить</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
