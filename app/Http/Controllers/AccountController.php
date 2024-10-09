<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Auth;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    //
    public function create(Request $request){

        $account = Account::create($request->all());
        return response()->json([
            'message' => 'Account Created ',
        ]);
    }

    public function update(Request $request){

        $account =Account::find(Auth::id)->update(
            [$request->bank_name,$request->account_holder_name,$request->account_number]);
        return response()->json([
            'message' => 'Account Created ',
        ]);
    }
}
