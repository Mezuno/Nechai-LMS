@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @foreach($quiz as $item)
                    <div class="card-header h1">{{ $item->title }}</div>
                        <div class="card-body h3">
                            Course ID: {{ $item->quiz_id }} Title: {{ $item->title }}
                        </div>
                        <div class="card-body">
                            <b>Description: {{ $item->description }}</b>
                        </div>
                    @endforeach

                    @foreach($questions as $question)
                        <div class="card-body">
                            <b>Question ID:</b> {{ $question->question_id }}<br>
                            <b>Body:</b> {{ $question->body }}<br>
                            <b>Options type:</b> {{ $question->type->title }}<br>
                                <b>Options:</b>
                            <div class="card-body">
                                @foreach($question->options as $option)
                                    <b>Option ID {{ $option->option_id }}:</b> {{ $option->body }}<br>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endsection
