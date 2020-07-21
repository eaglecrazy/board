<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Users\CreateRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entity\User;
use Illuminate\Validation\Rule;
use App\Usecases\Auth\RegisterService;

class UsersController extends Controller
{
    private $service;

    public function __construct(RegisterService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = User::orderByDesc('id');

        if(!empty($value = $request->get('id'))){
            $query->where('id', $value);
        }

        if(!empty($value = $request->get('name'))){
            $query->where('name', 'like', '%' . $value . '%');
        }

        if(!empty($value = $request->get('email'))){
            $query->where('email', 'like', '%' . $value . '%');
        }

        if(!empty($value = $request->get('status'))){
            $query->where('status', $value);
        }

        if(!empty($value = $request->get('role'))){
            $query->where('role', $value);
        }

        $users = $query->paginate(20);
        $roles = User::rolesList();
        $statuses = User::statusesList();
        return view('admin.users.index', compact('users','roles', 'statuses'));
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
//        return view('admin.users.show', ['user' => $user]);
    }

    public function edit(User $user)
    {
        $roles = [
            User::ROLE_USER => 'User', User::ROLE_ADMIN => 'Admin'
        ];

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->only(['name', 'email', 'status', 'role']));
        return redirect()->route('admin.users.show', $user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index');
    }

    public function verify(User $user){
        try {
            $this->service->verify($user->id);
            return redirect()->route('admin.users.show', $user)->with('success', 'User ' . $user->name . ' is verified.');
        } catch (\DomainException $e){
            return redirect()->route('admin.users.show', $user)->with('error', $e->getMessage());
        }
    }
}
