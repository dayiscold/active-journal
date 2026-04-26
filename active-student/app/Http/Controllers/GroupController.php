<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::withCount('students')->orderBy('faculty')->orderBy('course')->get();
        return view('admin.groups.index', compact('groups'));
    }

    public function create()
    {
        return view('admin.groups.form', ['group' => new Group()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:50|unique:groups',
            'course'  => 'required|integer|min:1|max:6',
            'faculty' => 'required|string|max:100',
        ]);
        Group::create($request->only(['name', 'course', 'faculty']));
        return redirect()->route('admin.groups.index')->with('success', 'Группа успешно создана');
    }

    public function edit(Group $group)
    {
        return view('admin.groups.form', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name'    => 'required|string|max:50|unique:groups,name,' . $group->id,
            'course'  => 'required|integer|min:1|max:6',
            'faculty' => 'required|string|max:100',
        ]);
        $group->update($request->only(['name', 'course', 'faculty']));
        return redirect()->route('admin.groups.index')->with('success', 'Группа успешно обновлена');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('admin.groups.index')->with('success', 'Группа удалена');
    }
}
