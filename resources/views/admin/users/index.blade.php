@php($pageTitle = 'Управление пользователями')
@extends('layouts.app')

@section('content')
    @include('admin.users._nav')
    <div class="d-flex flex-row mb-3">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mr-1">Создать пользователя</a>
    </div>

    <div class="card mb-3">
        <div class="card-header">Фильтрация</div>
        <div class="card-body">
            <form action="?" method="GET">
                <div class="row">
                    <div class="col-sm-1">
                        <div class="form-group">
                            <label for="id" class="col-form-label">ID</label>
                            <input id="id" class="form-control" name="id" value="{{ request('id') }}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Имя</label>
                            <input id="name" class="form-control" name="name" value="{{ request('name') }}">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="email" class="col-form-label">Email</label>
                            <input id="email" class="form-control" name="email" value="{{ request('email') }}">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="status" class="col-form-label">Статус</label>
                            <select id="status" class="form-control" name="status">
                                <option value=""></option>
                                @foreach ($statuses as $value => $label)
                                    <option
                                        value="{{ $value }}"{{ $value === request('status') ? ' selected' : '' }}>{{ $label }}</option>
                                @endforeach;
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="role" class="col-form-label">Роль</label>
                            <select id="role" class="form-control" name="role">
                                <option value=""></option>
                                @foreach ($roles as $value => $label)
                                    <option
                                        value="{{ $value }}"{{ $value === request('role') ? ' selected' : '' }}>{{ $label }}</option>
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

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Статус</th>
            <th>Роль</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td><a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->isWait())
                        <span class="badge badge-secondary">{{ $statuses[App\Entity\User\User ::STATUS_WAIT] }}</span>
                    @endif
                    @if($user->isActive())
                        <span class="badge badge-primary">{{ $statuses[App\Entity\User\User ::STATUS_ACTIVE] }}</span>
                    @endif
                </td>
                <td>
                    @if($user->isAdmin())
                        <span class="badge badge-danger">{{ $roles[App\Entity\User\User ::ROLE_ADMIN] }}</span>
                    @else
                        <span class="badge badge-secondary">{{ $roles[App\Entity\User\User ::ROLE_USER] }}</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>

    {{ $users->links() }}
@endsection
