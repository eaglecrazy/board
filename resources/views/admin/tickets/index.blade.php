@php($pageTitle = 'Управление заявками')
@extends('layouts.app')

@section('content')
    @include ('admin._nav', ['page' => 'tickets'])

    <div class="card mb-3">
        <div class="card-header">Фтльтр</div>
        <div class="card-body">
            <form action="?" method="GET">
                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group">
                            <label for="id" class="col-form-label">ID</label>
                            <input id="id" class="form-control" name="id" value="{{ request('id') }}">
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-group">
                            <label for="user" class="col-form-label">Пользователь</label>
                            <input id="user" class="form-control" name="user" value="{{ request('user') }}">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="status" class="col-form-label">Статус</label>
                            <select id="status" class="form-control" name="status">
                                <option value=""></option>
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}"{{ $value === request('status') ? ' selected' : '' }}>{{ $label }}</option>
                                @endforeach;
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="col-form-label">&nbsp;</label><br />
                            <button type="submit" class="btn btn-primary">Поиск</button>
                            <a href="?" class="btn btn-outline-secondary">Сброс</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Создана</th>
            <th>Обновлена</th>
            <th>Тема</th>
            <th>Польозватель</th>
            <th>Статус</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($tickets as $ticket)
            <tr>
                <td>{{ $ticket->id }}</td>
                <td>{{ $ticket->created_at }}</td>
                <td>{{ $ticket->updated_at }}</td>
                <td><a href="{{ route('admin.tickets.show', $ticket) }}" target="_blank">{{ $ticket->subject }}</a></td>
                <td>{{ $ticket->user->id }} - {{ $ticket->user->name }}</td>
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
        @endforeach

        </tbody>
    </table>

    {{ $tickets->links() }}
@endsection
