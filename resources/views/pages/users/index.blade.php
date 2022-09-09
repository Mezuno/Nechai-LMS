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

                @if(!empty(session()->get('failed')))
                    <div class="alert alert-danger" role="alert">
                        {{ session()->get('failed')  }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between h2">
                        <div class="d-flex align-items-end">
                            <p class="mb-0 fw-normal">{{ __('main.users') }}</p>
                            <a href="{{ route('users.create') }}" class="ms-2 btn btn-success">Добавить</a>
                        </div>

                        <div class="d-flex align-items-end">
                            <form action="{{ route('users') }}" method="get" class="d-flex">
                                <input type="text" name="search" value="" hidden>
                                @if (app('request')->input('search'))
                                    <button class="btn text-secondary"><i class="fas fa-x"></i></button>
                                @endif
                            </form>
                            <form action="{{ route('users') }}" method="get" class="d-flex">
                                <input type="text" name="search" value="{{ app('request')->input('search') }}" placeholder="Поиск" class="ms-2 mb-0 p-2 rounded-2 border border-secondary h6">
                                <button class="btn text-secondary ms-2"><i class="fas fa-search"></i></button>
                            </form>
                        </div>
                    </div>

                    @foreach($users as $user)
                        <div class="card-body border-top d-flex align-items-center justify-content-between">
                            <p class="mb-0">{{ $user->name }}</p>
                            @if ($user->deleted_at == NULL)
                                <div class="">
                                    <a href="{{ route('users.edit', ['id' => $user->id]) }}" class="btn btn-outline-primary">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button class="deleteButton btn btn-outline-danger"
                                            onclick="document.getElementById('deleteModal{{ $user->id }}').style.display = 'flex'">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            @else
                                <form method="post" action="{{ route('users.restore', ['id' => $user->id]) }}">
                                    @method('patch')
                                    @csrf
                                    <button type="submit" class="btn btn-outline-warning">
                                        <i class="fa-solid fa-trash-arrow-up"></i>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div id="deleteModal{{ $user->id }}" class="modal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-name">Подтверждение удаления</h5>
                                    </div>
                                    <div class="modal-body">
                                        <p>Вы действительно хотите удалить студента {{ $user->name }}?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form method="post" action="{{ route('users.delete', ['id' => $user->id]) }}">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Удалить</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                onclick="document.getElementById('deleteModal{{ $user->id }}').style.display = 'none'">
                                            Отмена
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>

                <div class="mt-3">{{ $users->withQueryString()->links() }}</div>

            </div>
        </div>
    </div>

@endsection
