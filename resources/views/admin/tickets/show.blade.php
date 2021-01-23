@php($pageTitle = 'Завяка "' . $ticket->subject . '"')
@extends('layouts.app')

@section('content')
    @include ('admin._nav', ['page' => 'tickets'])



    <div class="card card-default mb-2">
        <div class="card-header h4">Панель модератора</div>
        <div class="card-body d-flex flex-row mv0">
            <a href="{{ route('admin.tickets.edit', $ticket) }}" class="btn btn-primary mr-1">Редактировать</a>

            @if ($ticket->isOpen())
                <form method="POST" action="{{ route('admin.tickets.approve', $ticket) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Принять в обработку</button>
                </form>
            @endif

            @if (!$ticket->isClosed())
                <form method="POST" action="{{ route('admin.tickets.close', $ticket) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Закрыть</button>
                </form>
            @endif

            @if ($ticket->isClosed())
                <form method="POST" action="{{ route('admin.tickets.reopen', $ticket) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Открыть заново</button>
                </form>
            @endif

            <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Удалить</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>ID</th>
                    <td>{{ $ticket->id }}</td>
                </tr>
                <tr>
                    <th>Создана</th>
                    <td>{{ dtFormat($ticket->created_at) }}</td>
                </tr>
                <tr>
                    <th>Обновлена</th>
                    <td>{{ dtFormat($ticket->updated_at) }}</td>
                </tr>
                <tr>
                    <th>Пользователь</th>
                    <td><a href="{{ route('admin.users.show', $ticket->user) }}" target="_blank">{{ $ticket->user->name }}</a></td>
                </tr>
                <tr>
                    <th>Статус</th>
                    <td>
                        @if ($ticket->isOpen())
                            <span class="badge badge-danger">Открыта</span>
                        @elseif ($ticket->isApproved())
                            <span class="badge badge-primary">В обработке</span>
                        @elseif ($ticket->isClosed())
                            <span class="badge badge-secondary">Закрыта</span>
                        @endif
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-5">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Пользователь</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($ticket->statuses()->orderBy('id')->with('user')->get() as $status)
                    <tr>
                        <td>{{ dtFormat($status->created_at) }}</td>
                        <td>{{ $status->user->name }}</td>
                        <td>
                            @if ($status->isOpen())
                                <span class="badge badge-danger">Открыта</span>
                            @elseif ($status->isApproved())
                                <span class="badge badge-primary">В обработке</span>
                            @elseif ($status->isClosed())
                                <span class="badge badge-secondary">Закрыта</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header font-weight-bold">
            Тема обращения: {{ $ticket->subject }}
        </div>
        <div class="card-body">
            {!! nl2br(e($ticket->content)) !!}
        </div>
    </div>

    @foreach ($ticket->messages()->orderBy('id')->with('user')->get() as $message)
        <div class="card mb-3">
            <div class="card-header">
                {{ $message->created_at }} от {{ $message->user->name }}
            </div>
            <div class="card-body">
                {!! nl2br(e($message->message)) !!}
            </div>
        </div>
    @endforeach

    @if ($ticket->allowsMessages())
        <form method="POST" action="{{ route('admin.tickets.message', $ticket) }}">
            @csrf

            <div class="form-group">
                <textarea class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }}" name="message" rows="3" required>{{ old('message') }}</textarea>
                @if ($errors->has('message'))
                    <span class="invalid-feedback"><strong>{{ $errors->first('message') }}</strong></span>
                @endif
            </div>

            <div class="form-group mb-0">
                <button type="submit" class="btn btn-primary">Отправить сообщение</button>
            </div>
        </form>
    @endif
@endsection
