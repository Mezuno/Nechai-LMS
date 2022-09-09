@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <a class="btn btn-dark mb-3" href="{{ route('users') }}"><i class="fa-solid fa-arrow-left"></i></a>

                @if(!empty(session()->get('success')))
                    <div class="alert alert-success" role="alert">
                        {{ session()->get('success')  }}
                    </div>
                @endif
                <div class="card">
                    <form action="{{ route('users.update', ['id' => $user->id]) }}" method="post">
                        @method('patch')
                        @csrf
                        <div class="card-header d-flex justify-content-between h2">
                            {{ __('main.user') }} {{ $user->name }}
                        </div>
                        <div class="card-body d-flex flex-column">

                            @if ($errors->has('name'))
                                <div class="alert alert-danger w-100">
                                    <ul>@foreach($errors->get('name') as $message)<li>{{$message}}</li>@endforeach</ul>
                                </div>
                            @endif
                            <div class="mb-3">ФИО:
                                <input type="text" class="rounded-2 p-2 w-50" name="name" placeholder="ФИО" value="{{ old('name') ?? $user->name }}" required>
                            </div>

                            @if ($errors->has('email'))
                                <div class="alert alert-danger w-100">
                                    <ul>@foreach($errors->get('email') as $message)<li>{{$message}}</li>@endforeach</ul>
                                </div>
                            @endif
                            <div class="mb-3">Почта:
                                <input type="email" class="p-2 rounded-2" name="email" placeholder="Электронная почта" value="{{ old('email') ?? $user->email }}" required>
                            </div>
                            @if (auth()->id() == $user->id)
                                @if ($errors->has('password'))
                                    <div class="alert alert-danger w-100">
                                        <ul>@foreach($errors->get('password') as $message)<li>{{$message}}</li>@endforeach</ul>
                                    </div>
                                @endif
                                <input placeholder="Пароль" class="w-25 mb-2 p-2 rounded-2" type="password" name="password" autocomplete="new-password">
                                <input placeholder="Подтвердите пароль" class="w-25 mb-2 p-2 rounded-2" type="password" name="password_confirmation">
                            @endif

                                <p class="">Создан: {{ $user->created_at }}</p>
                                <p class="">Изменен: {{ $user->updated_at }}</p>
                            <button class="btn btn-success w-25">Сохранить изменения</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
