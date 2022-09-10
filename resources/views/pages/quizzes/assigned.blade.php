@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(!empty(session()->get('success')))
                <div class="alert alert-success" role="alert">
                    {{ session()->get('success')  }}
                </div>
            @endif
            <div class="card">
                <div class="card-header d-flex justify-content-between h2">{{ __('main.assignedQuizzes') }}</div>

                @forelse($quizzes as $quiz)
                    <div class="card-body border-top align-items-center d-flex justify-content-between">
                        <a class="btn">{{ $quiz->title }}</a>

                        @can ('play', [$quiz])
                        <a href="{{ route('quizzes.play', ['id' => $quiz->quiz_id]) }}" class="btn btn-success">
                            <i class="fa-solid fa-play"></i>
                        </a>
                        @else
                            <div class="d-flex align-items-center">
{{--                                <p class="mb-0 text-success">--}}
{{--                                    Результат: {{ $quiz->status->result }} из {{ $quiz->max_points }} баллов--}}
{{--                                </p>--}}
                                <div class="btn btn-secondary ms-3">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                            </div>
                        @endcan

                    </div>

                @empty
                    <div class="card-body">У вас пока нету назначенных тестов</div>
                @endforelse

            </div>

            <div class="mt-3">{{ $quizzes->withQueryString()->links() }}</div>

        </div>
    </div>
</div>
@endsection
