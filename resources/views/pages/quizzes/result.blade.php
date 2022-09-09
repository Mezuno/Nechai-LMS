@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <p class="fw-bold mt-2 h3 w-100">Результат прохождения теста {{ $quiz->title }}</p>
                    </div>
                    <div class="card-body">
                        @if(!empty(session()->get('result')))
                            <div class="alert alert-success mb-3 w-100" role="alert">
                                {{ session()->get('result')  }}
                            </div>
                        @endif
                        <a class="btn btn-dark mb-0" href="{{ route('quizzes.assigned') }}"><i class="fa-solid fa-arrow-left"></i> К назначенным тестам</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
