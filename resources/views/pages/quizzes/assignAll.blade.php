@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a class="btn btn-outline-dark mb-3" href="{{ route('quizzes.assign', ['id' => $quiz_id]) }}"><i class="fa-solid fa-arrow-left"></i></a>

                @if(!empty(session()->get('success')))
                    <div class="alert alert-success" role="alert">
                        {{ session()->get('success')  }}
                    </div>
                @endif

                <div class="card mb-3">
                    <div class="card-header">
                        <p class="h4 mb-0">Множественное назначение</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('quizzes.assign.mult', ['id' => $quiz_id]) }}" method="post">

                            @method('post')
                            @csrf
                            @if ($errors->has('emails'))
                                <div class="alert alert-danger w-100 pb-0">
                                    <ul>@foreach($errors->get('emails') as $message)<li>{{$message}}</li>@endforeach</ul>
                                </div>
                            @endif

                            <textarea name="emails" id="" cols="30" rows="10"
                                      placeholder="Введите сюда почты всех учеников, которых хотите назначить (через перенос строки)"
                                      class="rounded-2 p-2 border mb-2 w-100"
                            ></textarea>
                            <button class="btn btn-success">Назначить</button>

                        </form>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between h2">
                        {{ __('main.allUsers') }}
                        <a href="{{ route('quizzes.assign', ['id' => $quiz_id]) }}" class="btn btn-primary"><i class="fas fa-list"></i></a>
                    </div>

                    @forelse($assignments as $assignment)
                        <div class="card-body align-items-center border-top d-flex justify-content-between">
                            <p class="w-50 pb-0 mb-0">{{ $assignment->name }}</p>
                            <p class="pb-0 mb-0">{{ $assignment->email }}</p>
                            <div class="">
                                <button class="deleteButton btn btn-outline-success"
                                        onclick="document.getElementById('deleteModal{{ $assignment->id }}').style.display = 'flex'">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div id="deleteModal{{ $assignment->id }}" class="modal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-name">Подтверждение действия</h5>
                                    </div>
                                    <div class="modal-body">
                                        <p>Вы действительно хотите начначить студента {{ $assignment->name }}?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="post" action="{{ route('quizzes.assign.create', ['id' => $quiz_id,'user_id' => $assignment->id]) }}">
                                            @method('post')
                                            @csrf
                                            <button type="submit" class="btn btn-success">Назначить</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                onclick="document.getElementById('deleteModal{{ $assignment->id }}').style.display = 'none'">
                                            Отмена
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card-body d-flex justify-content-between">
                            Все имеющиеся студенты назначены на этот тест
                        </div>
                    @endforelse

                </div>
                {{ $assignments->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
