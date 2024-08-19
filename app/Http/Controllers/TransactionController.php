<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller {
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
        $transactions = Transaction::where('user', $userId);
        return response()->json($transactions);
    }

    public function show($id) {
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
        $transaction = Transaction::where('_id', $id)->get();
        if ($transaction->user !== $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json($transaction);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'amount' => ['required', 'numeric'],
            'recipient' => ['required', 'string'],
            'date' => ['required', 'date'],
            'category' => ['required', 'string'],
        ]);
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
        $validated['user'] = $userId
        $transaction = new Transaction($validated);
        $transaction->save();
        return response()->json($transaction);
    }

    public function update(Request $request, $id) {
        $transaction = Transaction::where('_id', $id)->update($request->all());
        return response()->json($transaction);
    }

    public function destroy($id) {
        try {
            $result = Transaction::where('_id', $id)->delete();
            return response()->json($result);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }
}
