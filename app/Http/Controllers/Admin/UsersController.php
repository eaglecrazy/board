<?php

namespace App\Http\Controllers\Admin;

//use http\Client\Curl\User;
use App\Http\Requests\Admin\Users\CreateRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;
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

    public function store(CreateRequest $request)
    {
        $user = User::new($request['name'], $request['email'], $request['password']);
        return redirect()->route('admin.users.show', ['id' => $user->id]);
    }

    public function show(User $user)
    {
        //compact передаёт переменную по её имени
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->only(['name', 'email', 'status']));
        return redirect()->route('admin.users.show', $user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index');
    }

    public function verify(User $user){
        try {
            $user->verify();
            return redirect()->route('admin.users.show', $user)->with('success', 'User ' . $user->name . ' is verified.');
        } catch (\DomainException $e){
            return redirect()->route('admin.users.show', $user)->with('error', $e->getMessage());
        }


    }
}
