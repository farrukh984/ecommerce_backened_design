<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Condition;

class ConditionController extends Controller
{
    public function index()
    {
        $conditions = Condition::orderBy('name')->get();
        return view('admin.conditions.index', compact('conditions'));
    }

    public function create()
    {
        return view('admin.conditions.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:conditions,name']);
        Condition::create(['name' => $request->name]);
        return redirect()->route('admin.conditions.index');
    }

    public function edit(Condition $condition)
    {
        return view('admin.conditions.edit', compact('condition'));
    }

    public function update(Request $request, Condition $condition)
    {
        $request->validate(['name' => 'required|string|unique:conditions,name,' . $condition->id]);
        $condition->update(['name' => $request->name]);
        return redirect()->route('admin.conditions.index');
    }

    public function destroy(Condition $condition)
    {
        $condition->delete();
        return redirect()->route('admin.conditions.index');
    }
}
