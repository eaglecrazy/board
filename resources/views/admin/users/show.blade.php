@php($pageTitle = 'Пользователь "' . $user->name . '"')
@extends('layouts.app')

@section('content')
    @include('admin.users._nav')
    <div class="d-flex flex-row mb-3">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary mr-1">Редактировать</a>
        @if($user->isWait())
            <a href="{{ route('admin.users.verify', $user) }}" class="btn btn-success mr-1">Верифицировать</a>
        @endif

        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="mr-1">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Удалить</button>
        </form>
    </div>

    <table class="table table-bordered table-striped">
        <tbody>
        <tr>
            <th>ID</th>
            <td>{{ $user->id }}</td>
        </tr>
        <tr>
            <th>Имя</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Статус</th>
            <td>
                @if($user->isWait())
                    <span class="badge badge-secondary">Ожидает</span>
                @endif
                @if($user->isActive())
                    <span class="badge badge-primary">Активный</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Роль</th>
            <td>
                @if($user->isAdmin())
                    <span class="badge badge-danger">Admin</span>
                @else
                    <span class="badge badge-secondary">User</span>
                @endif
            </td>
        </tr>
        </tbody>
    </table>
@endsection
