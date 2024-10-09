<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\User;
use Illuminate\Http\Request;

class DepositController extends Controller
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
        $deposits = Deposit::with('user')->get();
        return response()->json($deposits);
        // return view('deposits.all', compact('deposits'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $request->validate([
            'user_id'=>'required',
            'image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status'=>'required',
        ]);

        $input = $request->all();
        // Store the profile picture
        if ($request->hasFile('image')) {
            $profilePic = $request->file('image');
            $input['image']= $profilePic->store('Deposit_pictures', 'public'); // Store image in the public directory
        }

        Deposit::create($input);

        return response()->json([
            'message' => 'Deposit Created ',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Deposit $deposit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deposit $deposit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
       $user=User::find($request->user_id);

       $request->validate([
           'status' => 'required',
       ]);


      $user->deposit()->update($request->status);

       return response()->json([
           'message' => 'Deposit Updated ',
       ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deposit $deposit)
    {
        //
    }
}
