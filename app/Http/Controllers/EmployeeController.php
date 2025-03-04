<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\EmployeeResource;
class EmployeeController extends Controller
{
    public function index()
    {
        return response()->json(Employee::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'password' => 'required|string',
            'account_type' => 'required|integer|between:0,2'
        ]);

        $employee = Employee::create([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'password' => $request->password,
            'account_type' => $request->account_type
        ]);

        return response()->json($employee, 201);
    }

    public function show($id)
    {
        return response()->json(Employee::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->update($request->all());
        return response()->json($employee);
    }

    public function destroy($id)
    {
        Employee::destroy($id);
        return response()->json(['message' => 'Employee deleted']);
    }

        public function getEmployeesByDepartment($department_id)
    {
        // Récupère les employés du département donné
        $employees = Employee::where('department_id', $department_id)->get();

        // Retourne les employés sous forme de ressource
        
        return response()->json(EmployeeResource::collection($employees));
    }
}