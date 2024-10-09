<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function all()
    {

        $withdrawals = Withdrawal::with('user')->get();
        return response()->json($withdrawals);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $user=Auth::user();

        $request->validate([
            'user_id'=>'required',
            'bank_name'=>'required',
            'account_holder_name'=>'required',
            'account_number'=>'required'
        ]);

        $input = $request->all();

       $user->withdrawal->create($input);

        return response()->json([
            'message' => 'Withdrawal Created ',
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Withdrawal $withdrawal)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,)
    {
        //
       $user=User::find($request->id)->withdrawal()->where("user_id",$request->user_id);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required',
        ]);

        $input = $request->all();
        if ($request->hasFile('image')) {
            $profilePic = $request->file('image');
            $request['image']= $profilePic->store('withdrawal_pictures', 'public'); // Store image in the public directory
        }

       $user->update($input);

        return response()->json([
            'message' => 'Withdrawal Updated ',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Withdrawal $withdrawal)
    {
        //
    }
}
