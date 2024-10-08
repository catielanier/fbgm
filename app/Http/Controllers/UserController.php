<?php

namespace App\Http\Controllers;

use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\User;
use Firebase\JWT\JWT;

class UserController extends Controller {
    public function login(Request $request) {
        $validated = request()->validate([
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string']
        ]);
        $user = User::where('email', $validated['email'])->first();
        if (!$user || !password_verify($validated['password'], $user->password)) {
            return response()->json(['error' => 'Invalid email or password.'], 401);
        }
        $jwtSecret = env('SECRET');
        $payload = [
            'userId' => $user->_id,
            'iat' => time()
        ];
        $token = JWT::encode($payload, $jwtSecret, 'HS256');
        return response()->json(compact('token'));
    }

    public function register(Request $request) {
        $validated = $request->validate([
            'email' => ['required', 'string', 'unique:users', 'email'],
            'password' => ['required', 'string'],
            'verifyPassword' => ['required', 'string'],
            'firstName' => ['required', 'string'],
            'lastName' => ['required', 'string'],
        ]);
        $hashedPwd = null;
        if ($validated['password'] === $validated['verifyPassword']) {
            $hashedPwd = password_hash($validated['password'], PASSWORD_BCRYPT);
        } else {
            return response()->json(['error' => 'Password not match'], 401);
        }
        $validated['password'] = $hashedPwd;
        unset($validated['verifyPassword']);
        $user = new User($validated);
        $user->save();
        return response()->json(['success' => 'User created'], 200);
    }

    public function destroy(Request $request) {
        $token = null;
        $headers = $request->header();
        if (isset($headers['Authorization'])) {
            $authorization = $headers['Authorization'];
            $matches = [];
            preg_match('/Bearer\s(\S+)/', $authorization, $matches);
            if (isset($matches[1])) {
                $token = $matches[1];
            } else {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        } else {
            return response()->json(['error' => 'Authorization header not found.'], 403);
        }
        $jwtSecret = env('SECRET');
        $decoded = JWT::decode($token, new Key($jwtSecret, 'HS256'));
        $tokenArr = (array)$decoded->data;
        $user = $tokenArr['userId'];
        $result = User::where('_id', $user)->first()->delete();
        if ($result) {
            return response()->json(['success' => 'User deleted successfully'], 200);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
