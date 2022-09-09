@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a class="btn btn-outline-dark mb-3" href="{{ route('quizzes') }}"><i class="fa-solid fa-arrow-left"></i></a>
                <div class="card">
                    <div class="card-header h1">Создание теста</div>
                    <div class="card-body">
                        <form action="{{ route('quizzes.store') }}" method="post" class="form d-flex flex-column">
                            @method('post')
                            @csrf

                            @if ($errors->has('title'))
                                <div class="alert alert-danger p-2 w-100">
                                    <ul class="mb-0">@foreach($errors->get('title') as $message)<li>{{$message}}</li>@endforeach</ul>
                                </div>
                            @endif
                            <input type="text" name="title" class="rounded-2 border p-2 mb-3" placeholder="Название теста">


                            @if ($errors->has('description'))
                                <div class="alert alert-danger w-100">
                                    <ul>@foreach($errors->get('description') as $message)<li>{{$message}}</li>@endforeach</ul>
                                </div>
                            @endif
                            <textarea name="description" id="" cols="30" rows="10" class="rounded-2 border p-2 mb-4" placeholder="Описание теста"></textarea>
                            <button class="btn btn-success">Создать</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
