@php($pageTitle = 'Сообщения ' . $advert->title)
@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'dialogs'])

    <div class="card card-default mb-2">
        <div class="card-header h4">
            <a href="{{ route('adverts.show', $advert) }}" class="font-weight-bold">{{ $advert->title }}</a>
        </div>
        <div class="card-header h4">
            {{ $messages->newMessages->count() ? 'Новых сообщений: ' . $messages->newMessages->count() : 'Сообщения:'}}
        </div>
        <div class="card-body p-0">
            @if($messages->hasMessages())
                @foreach($messages->oldMessages as $message)
                    @if($message->user_id === Auth::id())
                        <div class="message p-2 text-primary">{{ dtFormat($message->created_at) }}
                            <br>Вы: {{ $message->message }}
                        </div>
                    @else
                        <div
                            class="message other-user-message p-2 text-danger">{{ dtFormat($message->created_at) }}
                            <br>{{ $otherUser->name }}: {{ $message->message }}
                        </div>
                    @endif
                @endforeach
                @foreach($messages->newMessages as $message)
                    <div
                        class="message other-user-message p-2 text-danger">{{ dtFormat($message->created_at) }}
                        <span class="font-weight-bold">новое сообщение</span><br>{{ $otherUser->name }}
                        : {{ $message->message }}
                    </div>
                @endforeach
            @else
                <div class="message p-2 text-primary">Сообщений нет.</div>
            @endif
        </div>
    </div>
    <div class="card card-default">
        <div class="card-header h4">Написать сообщение</div>
        <div class="card-body mv0">
            <form method="POST" action="{{ route('cabinet.dialogs.write', $dialog) }}">
                @csrf
                <div class="form-group">
                    <textarea id="message" class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }}"
                              name="message"
                              rows="3" required>{{ old('message') }}</textarea>
                    @if ($errors->has('message'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('message') }}</strong></span>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
        </div>
    </div>
@endsection
