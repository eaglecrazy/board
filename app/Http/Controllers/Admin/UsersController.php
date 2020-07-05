<?php

namespace App\Http\Controllers\Admin;

//use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entity\User;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:1', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
            'status' => User::STATUS_ACTIVE,
        ]);

        return redirect()->route('admin.users.show', ['id' => $user->id]);
    }

    public function show(User $user)
    {
        //compact передаёт переменную по её имени
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $statuses = [
            User::STATUS_ACTIVE => 'Active',
            User::STATUS_WAIT => 'Waiting',
        ];
        return view('admin.users.edit', compact('user', 'statuses'));
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['required', 'string', 'min:1', 'confirmed'],
            'status' => ['required', 'string', Rule::in([User::STATUS_WAIT, User::STATUS_ACTIVE])],
        ]);
        $user->update($data);
        return redirect()->route('admin.users.show', $user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index');
    }
}
