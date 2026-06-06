<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    // Получить список пользователей (с пагинацией)
    public function index()
    {
        $users = User::with('group')->orderBy('role')->orderBy('name')->paginate(10);
        return response()->json($users);
    }

    // Получить одного пользователя
    public function show(User $user)
    {
        return response()->json($user->load('group'));
    }

    // Создать пользователя
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:8',
            'role'        => 'required|in:admin,teacher,student,dean',
            'group_id'    => 'nullable|exists:groups,id',
            'teams_email' => 'nullable|email',
            'student_id'  => 'nullable|string|max:50|unique:users',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'group_id'    => $request->group_id,
            'teams_email' => $request->teams_email,
            'student_id'  => $request->student_id,
        ]);

        return response()->json($user, 201);
    }

    // Обновить пользователя
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'password'    => 'nullable|min:8',
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

        return response()->json($user);
    }

    // Удалить пользователя (Soft Delete)
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Пользователь удалён']);
    }
}
