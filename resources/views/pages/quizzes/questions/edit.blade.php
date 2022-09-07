@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a class="btn btn-dark mb-3" href="{{ route('quizzes.edit', [$question->quiz_id]) }}"><i class="fa-solid fa-arrow-left"></i></a>

                @if(!empty(session()->get('success')))
                    <div class="alert alert-success w-100" role="alert">
                        {{ session()->get('success')  }}
                    </div>
                @endif

                <form id="update-question" method="post" action="{{ route('quizzes.update.questions', ['id' => $question->quiz_id, 'question_id' => $question->question_id]) }}">
                    <div class="card">
                        <div class="card-header d-flex flex-column justify-content-between align-items-center">
                            <p class="fw-bold mt-2 h3 mb-3 w-100">Вопрос</p>

                            @if ($errors->has('body'))
                                <div class="alert alert-danger w-100">
                                    <ul>@foreach($errors->get('body') as $message)<li>{{$message}}</li>@endforeach</ul>
                                </div>
                            @endif
                            <textarea class="h3 p-2 w-100" name="body" type="text" placeholder="Введите вопрос сюда">{{ old('body') ?? $question->body }}</textarea>

                        </div>

                        <div class="card-body d-flex">
                            <div class="w-50">
                                <p class="h4">Тип ответов</p>
                                <select onchange='findOption(this)' class="rounded-2 p-2" name="type" id="">
                                    @foreach($types as $type)
                                        <option class="type-option" @if ($question->option_type_id == $type->option_type_id) selected @endif value="{{ $type->option_type_id }}">
                                            {{ $type->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-75">
                                <p class="h4">Правильный ответ</p>
                                <select @if ($question->option_type_id == 3) multiple @endif class="rounded-2 w-50 p-2" name="isCorrect[]">
                                    @foreach($question->options as $option)
                                        <option @if($option->is_correct == 1) selected @endif class="w-100" value="{{ $option->option_id }}">{{ Str::limit($option->body, 40) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column">

                            <div class="d-flex align-items-center mb-4">
                                <p class="h4 mb-0">Ответы</p>
                                <a href="{{ route('quizzes.create.options', ['id' => $question->quiz_id, 'question_id' => $question->question_id]) }}" class="btn btn-success ms-2"><i class="fa-solid fa-plus"></i></a>
                            </div>

                            @foreach($question->options as $option)
                                @if ($errors->has('option'.$option->option_id))
                                    <div class="alert alert-danger w-100">
                                        <ul>@foreach($errors->get('option'.$option->option_id) as $message)<li>{{$message}}</li>@endforeach</ul>
                                    </div>
                                @endif
                            <div class="w-100 d-flex justify-content-between align-items-center">
                                <textarea name="option{{ $option->option_id }}" class="rounded-2 w-100 p-2 mb-3">{{ old('option'.$option->option_id) ?? $option->body }}</textarea>

                                <form id="delete-option" method="post" action="{{ route('quizzes.delete.options', ['id' => $question->quiz_id, 'question_id' => $question->question_id, 'option_id' => $option->option_id]) }}">
                                    @method('delete')
                                    @csrf
                                    <button form="delete-option" class="btn btn-danger ms-2"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                            @endforeach

                            @method('patch')
                            @csrf
                            <button form="update-question" type="submit" class="btn btn-success">Сохранить изменения</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function findOption(select) {
            const option = select.querySelector(`option[value="${select.value}"]`)

            if (option.value == 1) {
                document.querySelector(`select[name="isCorrect[]"]`).removeAttribute('multiple')
                console.log(option.innerText);
            }
            if (option.value == 2) {
                console.log(option.innerText);
            }
            if (option.value == 3) {
                document.querySelector(`select[name="isCorrect[]"]`).setAttribute('multiple', 'true')
            }
        }

    </script>
@endsection
