@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">

                <div class="card-header d-flex justify-content-between">
                    <p class="h3 mb-0">{{ auth()->user()->name }}</p>
                    <a href="{{ route('users.edit', ['id' => auth()->user()->id]) }}" class="btn btn-outline-dark">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>

                <div class="card-body">
                    <p class="mb-0">Ваша почта: {{ auth()->user()->email }}</p>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
