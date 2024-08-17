<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller {
    public function index(Request $request) {
        $transactions = Transaction::where('user', $request->get('userId'))->get();
        return response()->json($transactions);
    }

    public function show($id) {
        $transaction = Transaction::where('_id', $id)->get();
        return response()->json($transaction);
    }

    public function store(Request $request) {
        $transaction = new Transaction($request->all());
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
