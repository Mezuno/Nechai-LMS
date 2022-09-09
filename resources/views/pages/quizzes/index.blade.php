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
                    <div class="card-header d-flex justify-content-between flex-wrap align-items-center h2">
                        <div class="d-flex align-items-end">
                            <p class="mb-0">{{ __('main.quizzes') }}</p>
                            <a href="{{ route('quizzes.create') }}" class="btn ms-2 mb-0 btn-success">Добавить</a>
                        </div>
                        <div class="d-flex align-items-end">

                            <form action="{{ route('quizzes') }}" method="get" class="d-flex">
                                <input type="text" name="search" value="" hidden>
                                @if (app('request')->input('search'))
                                    <button class="btn text-secondary"><i class="fas fa-x"></i></button>
                                @endif
                            </form>
                            <form action="{{ route('quizzes') }}" method="get" class="d-flex">
                                <input type="text" name="search" value="{{ app('request')->input('search') }}" placeholder="Поиск" class="ms-2 mb-0 p-2 rounded-2 border h6">
                                <button class="btn text-secondary ms-2"><i class="fas fa-search"></i></button>
                            </form>

                        </div>
                    </div>

                    @forelse($quizzes as $quiz)
                        <div class="card-body border-top d-flex align-items-center justify-content-between">
                            <p class="mb-0 w-50">{{ $quiz->title }}</p>
                            @if ($quiz->deleted_at == NULL)
                            <div class="">
                                <a href="{{ route('quizzes.edit', ['id' => $quiz->quiz_id]) }}" class="btn btn-outline-primary">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button class="deleteButton btn btn-outline-danger"
                                        onclick="document.getElementById('deleteModal{{ $quiz->quiz_id }}').style.display = 'flex'">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                            @else
                                <form method="post" action="{{ route('quizzes.restore', ['id' => $quiz->quiz_id]) }}">
                                    @method('patch')
                                    @csrf
                                    <button type="submit" class="btn btn-outline-warning">
                                        <i class="fa-solid fa-trash-arrow-up"></i>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div id="deleteModal{{ $quiz->quiz_id }}" class="modal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Подтверждение удаления</h5>
                                    </div>
                                    <div class="modal-body">
                                        <p>Вы действительно хотите удалить тест {{ $quiz->title }}?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="post" action="{{ route('quizzes.delete', ['id' => $quiz->quiz_id]) }}">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Удалить</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                onclick="document.getElementById('deleteModal{{ $quiz->quiz_id }}').style.display = 'none'">
                                                Отмена
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card-body border-top d-flex justify-content-between">
                            Тесты не найдены ;с
                        </div>
                    @endforelse
                </div>
                <div class="mt-3">{{ $quizzes->withQueryString()->links() }}</div>
            </div>
        </div>
    </div>
@endsection
