@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a class="btn btn-dark mb-3" href="{{ route('quizzes') }}"><i class="fa-solid fa-arrow-left"></i></a>
                <div class="card">
                    <div class="card-header h1">Создание теста</div>
                    <div class="card-body">
                        <form action="{{ route('quizzes.store') }}" method="post" class="form d-flex flex-column">
                            @method('post')
                            @csrf
                            <input type="text" name="title" class="p-2 mb-4" placeholder="Название теста">
                            <textarea name="description" id="" cols="30" rows="10" class="p-2 mb-4" placeholder="Описание теста"></textarea>
                            <button class="btn btn-success">Создать</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
