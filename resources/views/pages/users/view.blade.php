@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">

                <div class="card-header d-flex justify-content-between">
                    <p class="h3 mb-0">{{ $user->name }}</p>
                    <a href="{{ route('users.edit', ['id' => $user->id]) }}" class="btn btn-outline-dark">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>

                <div class="card-body d-flex">
                    <div class="d-flex flex-column">
                        @if(mb_substr($user->avatar_filename,0,4) == 'http')
                            <img src="{{ URL::asset($user->avatar_filename) }}" width="200" height="200" alt="avatar" class="rounded-circle mb-2">
                        @elseif($user->avatar_filename && file_exists('img/avatars/'.$user->avatar_filename))
                            <img src="{{ URL::asset('img/avatars/'.$user->avatar_filename) }}" width="200" height="200" alt="avatar" class="rounded-circle mb-2">
                        @else
                            <img src="{{ URL::asset('img/default-avatar.png') }}" width="200" height="200" alt="avatar" class="rounded-circle mb-2">
                        @endif

                        @if (auth()->id() == $user->id)
                            @if ($errors->has('avatar'))
                                <div class="alert alert-danger w-100">
                                    <ul>@foreach($errors->get('avatar') as $message)<li>{{$message}}</li>@endforeach</ul>
                                </div>
                            @endif

                            <form enctype="multipart/form-data" method="post" action="{{ route('users.update.avatar', ['id' => $user->id]) }}" class="d-flex flex-column">
                                @csrf
                                @method('patch')
                                <input class="mb-2" type="file" name="avatar" id="avatar" accept=".jpg, .jpeg, .png">
                                <button type="submit" class="btn btn-danger mb-2">Изменить аватар</button>
                            </form>
                        @endif
                    </div>
                    <p class="ms-2 mb-0">Почта: {{ $user->email }}</p>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
