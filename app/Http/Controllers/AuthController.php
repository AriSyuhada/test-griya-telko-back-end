<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index(Request $request) {
        $validator = Validator::make($request->all(), [
            'role' => 'in:admin,sales',
        ]);

        if ($validator->fails() && isset($request->role)) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $role = $request->role ?? '';

        $users = User::where('role', 'LIKE', '%' . $role . '%')->get();

        return response()->json($users, Response::HTTP_OK);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
            'role' => 'in:admin,sales',
        ]);

        if ($validator->fails() && isset($request->role)) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }
        
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role ?? 'sales',
        ]);

        $token = $user->createToken('my-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'Type' => 'Bearer'
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]); 

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['errors' => ['credentials' => ['Wrong credentials']]], Response::HTTP_BAD_REQUEST);
        }

        $token = $user->createToken('my-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'Type' => 'Bearer',
            'role' => $user->role
        ], Response::HTTP_OK);
    }
}
