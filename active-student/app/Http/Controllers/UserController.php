<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('group')->orderBy('role')->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $groups = Group::orderBy('name')->get();
        return view('admin.users.form', ['user' => new User(), 'groups' => $groups]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:8|confirmed',
            'role'        => 'required|in:admin,teacher,student,dean',
            'group_id'    => 'nullable|exists:groups,id',
            'teams_email' => 'nullable|email',
            'student_id'  => 'nullable|string|max:50|unique:users',
        ]);

        User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'group_id'    => $request->group_id,
            'teams_email' => $request->teams_email,
            'student_id'  => $request->student_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно создан');
    }

    public function edit(User $user)
    {
        $groups = Group::orderBy('name')->get();
        return view('admin.users.form', compact('user', 'groups'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'password'    => 'nullable|min:8|confirmed',
            'role'        => 'required|in:admin,teacher,student,dean',
            'group_id'    => 'nullable|exists:groups,id',
            'teams_email' => 'nullable|email',
            'student_id'  => 'nullable|string|max:50|unique:users,student_id,' . $user->id,
        ]);

        $data = $request->only(['name', 'email', 'role', 'group_id', 'teams_email', 'student_id']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно обновлён');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Пользователь удалён');
    }
}
