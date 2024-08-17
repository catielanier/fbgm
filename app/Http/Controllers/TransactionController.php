<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller {
    public function index(Request $request) {
        $transactions = Transaction::where('user', $request->get('userId'))->get();
        return response()->json($transactions);
    }

    public function store(Request $request) {
        $transaction = new Transaction($request->all());
        $transaction->save();
        return response()->json($transaction);
    }
}
