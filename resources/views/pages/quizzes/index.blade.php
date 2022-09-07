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
                    <div class="card-header d-flex justify-content-between h2">{{ __('main.quizzes') }}
                        <a href="{{ route('quizzes.create') }}" class="btn btn-success"><i class="fa-solid fa-plus"></i></a>
                    </div>

                    @foreach($quizzes as $quiz)
                        <div class="card-body border-top d-flex justify-content-between">
                            <a class="btn">{{ $quiz->title }}</a>
                            @if ($quiz->deleted_at == NULL)
                            <div class="">
                                <a href="{{ route('quizzes.edit', ['id' => $quiz->quiz_id]) }}" class="btn btn-primary">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button class="deleteButton btn btn-danger"
                                        onclick="document.getElementById('deleteModal{{ $quiz->quiz_id }}').style.display = 'flex'">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                            @else
                                <form method="post" action="{{ route('quizzes.restore', ['id' => $quiz->quiz_id]) }}">
                                    @method('patch')
                                    @csrf
                                    <button type="submit" class="btn btn-warning">
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
                    @endforeach


                </div>

                <div class="mt-3">{{ $quizzes->links() }}</div>

            </div>
        </div>
    </div>
@endsection
