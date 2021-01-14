@php($pageTitle = 'Мой профиль')
@extends('layouts.app')

@section('content')
    @include('cabinet._nav', ['page' => 'profile'])

    <div class="mb-3">
        <a href="{{ route('cabinet.profile.edit') }}" class="btn btn-primary">Редактировать</a>
    </div>

    <table class="table table-bordered">
        <tbody>
        <tr>
            <th>Имя</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Фамилия</th>
            <td>{{ $user->last_name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Телефон</th><td>
                @if ($user->phone)
                    {{ $user->phone }}
                    @if (!$user->isPhoneVerified())
                        <i>не подтверждён</i>
                        <form method="POST" action="{{ route('cabinet.profile.phone') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Подтвердить телефон</button>
                        </form>
                    @endif
                @endif
            </td>
        </tr>
        @if ($user->phone && $user->phone_verified)
            <tr>
                <th>Двухфакторная аутентификация</th><td>
                    <form method="POST" action="{{ route('cabinet.profile.phone.auth') }}">
                        @csrf
                        @if ($user->isPhoneAuthEnabled())
                            <p>Включена</p> <button type="submit" class="btn btn-sm btn-success">Выключить</button>
                        @else
                            <p>Выключена</p><button type="submit" class="btn btn-sm btn-danger"> Включить</button>
                        @endif
                    </form>
                </td>
            </tr>
        @endif
        </tbody>
    </table>
@endsection
