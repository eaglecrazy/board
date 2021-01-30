@php($pageTitle = 'Мои заявки')
@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'tickets'])

    <p><a href="{{ route('cabinet.tickets.create') }}" class="btn btn-success">Добавить заявку</a></p>
    <div class="table-responsive">
        <table class="table table-striped">
        <thead>
        <tr>
            <th>Создана</th>
            <th>Обновлена</th>
            <th>Тема</th>
            <th>Статус</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($tickets as $ticket)
            <tr>
                <td>{{ dtFormat($ticket->created_at) }}</td>
                <td>{{ dtFormat($ticket->updated_at) }}</td>
                <td><a href="{{ route('cabinet.tickets.show', $ticket) }}" target="_blank">{{ $ticket->subject }}</a></td>
                <td>
                    @if ($ticket->isOpen())
                        <span class="badge badge-danger">Открыта</span>
                    @elseif ($ticket->isApproved())
                        <span class="badge badge-primary">Получена</span>
                    @elseif ($ticket->isClosed())
                        <span class="badge badge-secondary">Закрыта</span>
                    @endif
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
    </div>

    {{ $tickets->links() }}
@endsection
