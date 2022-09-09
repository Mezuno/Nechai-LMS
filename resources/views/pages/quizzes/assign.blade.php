@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a class="btn btn-outline-dark mb-3" href="{{ route('quizzes.edit', ['id' => $quiz_id]) }}"><i class="fa-solid fa-arrow-left"></i></a>

                @if(!empty(session()->get('success')))
                    <div class="alert alert-success" role="alert">
                        {{ session()->get('success')  }}
                    </div>
                @endif

                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between h2">
                        {{ __('main.assignments') }}
                        <a href="{{ route('quizzes.assign.all', ['id' => $quiz_id]) }}" class="btn btn-success"><i class="fas fa-plus"></i></a>
                    </div>

                    @forelse($assignments as $assignment)
                        <div class="card-body border-top d-flex justify-content-between">
                            <a class="btn">{{ $assignment->students->first()->name }}</a>
                            <div class="">
                                <button class="deleteButton btn btn-outline-danger"
                                        onclick="document.getElementById('deleteModal{{ $assignment->students->first()->id }}').style.display = 'flex'">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div id="deleteModal{{ $assignment->students->first()->id }}" class="modal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-name">Подтверждение действия</h5>
                                    </div>
                                    <div class="modal-body">
                                        <p>Вы действительно хотите снять начначение со студента {{ $assignment->students->first()->name }}?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="post" action="{{ route('quizzes.assign.delete', ['id' => $assignment->quiz_id,'assignment_id' => $assignment->assignment_id]) }}">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Снять назначение</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                onclick="document.getElementById('deleteModal{{ $assignment->students->first()->id }}').style.display = 'none'">
                                            Отмена
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card-body d-flex justify-content-between">
                            На этот тест пока никто не назначен ;с
                        </div>
                    @endforelse


                </div>
                {{ $assignments->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
