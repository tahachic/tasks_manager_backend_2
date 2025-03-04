<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return response()->json(Department::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $department = Department::create($request->all());
        return response()->json($department, 201);
    }

    public function show($id)
    {
        return response()->json(Department::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        $department->update($request->all());
        return response()->json($department);
    }

    public function destroy($id)
    {
        Department::destroy($id);
        return response()->json(['message' => 'Department deleted']);
    }
}
