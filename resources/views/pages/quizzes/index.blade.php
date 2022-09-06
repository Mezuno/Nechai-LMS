@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header h2">{{ __('main.quizzes') }}</div>

                    @foreach($quizzes as $quiz)
                        <div class="card-body">
                            <a href="{{ route('quizzes.edit', ['id' => $quiz->quiz_id]) }}">{{ $quiz->title }}</a>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endsection
