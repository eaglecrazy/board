@php($pageTitle = 'Сообщения')
@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'dialogs'])
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Объявление</th>
            <th>Дата сообщения</th>
            <th>Новых сообщений</th>
            <th>Последнее сообщение</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($dialogs as $dialog)
            @php($advert = $dialog->getAdvert())
            <tr>
                <td>
                    <a href="{{ route('adverts.show', $advert) }}">{{ $advert->title }}</a>
                </td>
                <td>{{ isset($dialog->updated_at) ? dtFormat($dialog->updated_at) : '' }}</td>
                <td>{{ $dialog->user_id === Auth::id() ? $dialog->user_new_messages : $dialog->client_new_messages }}</td>
                <td>{{ $dialog->getLastMessageShort() }}</td>
                <td>
                    <a href="{{ route('cabinet.dialogs.dialog', $advert) }}"
                       class="btn btn-sm btn-primary">Посмотреть диалог</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $dialogs->links() }}
@endsection
