@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between h2 p-lg-3">
                        {{ __('main.statistics') }}
                        <div class="d-flex">
                            <div class="d-flex align-items-end">
                                <form action="{{ route('quizzes.statistics') }}" method="get" class="d-flex">
                                    <input type="text" name="searchByName" value="" hidden>
                                    @if (app('request')->input('searchByName'))
                                        <button class="btn text-secondary"><i class="fas fa-x"></i></button>
                                    @endif
                                </form>
                                <form action="{{ route('quizzes.statistics') }}" method="get" class="d-flex">
                                    <input type="text" name="searchByName" value="{{ app('request')->input('searchByName') }}" placeholder="Поиск по имени"
                                           class="ms-2 mb-0 p-2 border-secondary rounded-2 border h6">
                                    <button class="btn text-secondary ms-2"><i class="fas fa-search"></i></button>
                                </form>
                            </div>

                            <div class="d-flex ms-2 align-items-end">
                                <form action="{{ route('quizzes.statistics') }}" method="get" class="d-flex">
                                    <input type="text" name="searchByTest" value="" hidden>
                                    @if (app('request')->input('searchByTest'))
                                        <button class="btn text-secondary"><i class="fas fa-x"></i></button>
                                    @endif
                                </form>
                                <form action="{{ route('quizzes.statistics') }}" method="get" class="d-flex">
                                    <input type="text" name="searchByTest" value="{{ app('request')->input('searchByTest') }}" placeholder="Поиск по тестам"
                                           class="ms-2 mb-0 p-2 border-secondary rounded-2 border h6">
                                    <button class="btn text-secondary ms-2"><i class="fas fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            @if (!empty($statistics->first()))
                                <thead class="">
                                    <tr>
                                        <th scope="col" class="fw-normal mb-0 w-25">ФИО</th>
                                        <th scope="col" class="fw-normal mb-0 w-25">Тест</th>
                                        <th scope="col" class="fw-normal mb-0 w-25">Время</th>
                                        <th scope="col" class="fw-normal mb-0 w-25">Баллы</th>
                                    </tr>
                                </thead>
                            @endif
                            <tbody class="">
                                @forelse($statistics as $statistic)
                                    <tr>
                                        <th scope="row" class="align-items-center mb-0 w-25">
                                            <a class="btn fw-lighter"
                                               href="{{ route('users.view', ['id' => $statistic->student->id]) }}">
                                                {{ $statistic->student->name }}
                                            </a>
                                        </th>
                                        <th scope="row" class="align-items-center mb-0 w-25">
                                            <a class="btn fw-lighter"
                                               href="{{ route('quizzes.edit', ['id' => $statistic->quiz->quiz_id]) }}">
                                                {{ $statistic->quiz->title }}
                                            </a>
                                        </th>
                                        <th scope="row" class="align-items-center mb-0 fw-light w-25">{{ $statistic->created_at }}</th>
                                        <th scope="row" class="align-items-center mb-0 fw-light w-25">{{ $statistic->result }} из {{ $statistic->quiz->max_points }}</th>
                                    </tr>
                                @empty
                                    <p class="p-2 mb-0">Статистики пока нету ;с</p>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $statistics->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
