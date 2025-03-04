<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ✅ Connexion (Sign In)
    public function signIn(Request $request)
    {
        $credentials = $request->validate([
            'id' => 'required|integer',
            'password' => 'required'
        ]);

        $employee = Employee::find($credentials['id']);

        if ($employee && $employee->password === $credentials['password']) {
            $token = $employee->createToken('authToken')->plainTextToken;

            return response()->json([
                'employee' => $employee,
                'token' => $token
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // ✅ Déconnexion (Sign Out)
    public function signOut(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }
}