<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class WalletController extends Controller {
    public function index(Request $request) {
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
        $userId = $tokenArr['userId'];
        $wallets = Wallet::where('user', $userId)->get();
        return response()->json($wallets, 200);
    }

    public function store(Request $request) {
        $token = null;
        $headers = $request->header();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'totalAllotment' => ['required', 'numeric'],
            'totalSpent' => ['required', 'numeric'],
        ]);
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
        $validated['user'] = $tokenArr['userId'];
        $wallet = new Wallet($validated);
        $wallet->save();
        return response()->json($wallet, 201);
    }

    public function update(Request $request, $id) {
        $token = null;
        $headers = $request->header();
        $validated = $request->validate([
            'name' => ['string', 'max:255'],
            'totalAllotment' => ['numeric'],
            'totalSpent' => ['numeric'],
        ]);
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
        $wallet = Wallet::where('_id', $id)->first();
        if ($wallet->user !== $tokenArr['userId']) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $updated = $wallet->update($validated);
        return response()->json($updated, 200);
    }

    public function destroy(Request $request, $id) {
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
            return response()->json(['error' => 'Authorization header not found.'],  403);
        }
        $jwtSecret = env('SECRET');
        $decoded = JWT::decode($token, new Key($jwtSecret, 'HS256'));
        $tokenArr = (array)$decoded->data;
        $result = Wallet::where('_id', $id)->first();
        if ($result->user !== $tokenArr['userId']) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $result->delete();
        return response()->json(null, 204);
    }
}
