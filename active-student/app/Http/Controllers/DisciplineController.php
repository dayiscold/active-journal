<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use Illuminate\Http\Request;

class DisciplineController extends Controller
{
    public function index()
    {
        $disciplines = Discipline::orderBy('name')->get();
        return view('admin.disciplines.index', compact('disciplines'));
    }

    public function create()
    {
        return view('admin.disciplines.form', ['discipline' => new Discipline()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:disciplines',
        ]);
        Discipline::create($request->only(['name', 'code']));
        return redirect()->route('admin.disciplines.index')->with('success', 'Дисциплина успешно создана');
    }

    public function edit(Discipline $discipline)
    {
        return view('admin.disciplines.form', compact('discipline'));
    }

    public function update(Request $request, Discipline $discipline)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:disciplines,code,' . $discipline->id,
        ]);
        $discipline->update($request->only(['name', 'code']));
        return redirect()->route('admin.disciplines.index')->with('success', 'Дисциплина успешно обновлена');
    }

    public function destroy(Discipline $discipline)
    {
        $discipline->delete();
        return redirect()->route('admin.disciplines.index')->with('success', 'Дисциплина удалена');
    }
}
