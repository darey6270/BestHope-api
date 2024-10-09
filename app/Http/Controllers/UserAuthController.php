<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserAuthController extends Controller
{
    //


    public function register(Request $request){

        $registerUserData = $request->validate([
            'username'=>'required',
            'fullname'=>'required',
            'country'=>'required',
            'city'=>'required',
            'age'=>'required',
            'phone'=>'required',
            'referral'=>'min:11',
            'email'=>'required|email',
            'password'=>'required|min:8',
            'address'=>'required',
            'image'=>'required|max:5048',
            'gender'=>'required',
        ]);

        // Store the profile picture
    if ($request->hasFile('image')) {
        $profilePic = $request->file('image');
        $registerUserData['image']= $profilePic->store('profile_pictures', 'public'); // Store image in the public directory
    }
        $user = User::create([
            'username' => $registerUserData['username'],
            'fullname' => $registerUserData['fullname'],
            'country' => $registerUserData['country'],
            'city' => $registerUserData['city'],
            'age' => $registerUserData['age'],
            'phone' => $registerUserData['phone'],
            'referral' => $registerUserData['referral'],
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
            'address' => $registerUserData['address'],
            'image' => $registerUserData['image'],
            'gender' => $registerUserData['gender'],
        ]);




        return response()->json([
            'message' => 'User Created ',
        ]);
    }



    public function update(Request $request, User $user){
        $updateUserData = $request->validate([
            'fullname'=>'required',
            'country'=>'required',
            'city'=>'required',
            'age'=>'required',
            'phone'=>'required',
            'email'=>'required|email',
            'address'=>'required',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender'=>'required|in:male,female,other',
        ]);

        // Store the profile picture
        if ($request->hasFile('image')) {
            $profilePic = $request->file('image');
            $updateUserData['image']= $profilePic->store('profile_pictures', 'public'); // Store image in the public directory
        }

        $user->update($updateUserData);

        return response()->json([
           'message' => 'User Updated ',
        ]);


    }

    public function delete(User $user){
        $user->delete();
        return response()->json([
           'message' => 'User Deleted ',
        ]);
    }


    public function getUsers(){
        $users = User::all();
        return response()->json($users);
    }

    public function getUser($id){
        $user = User::find($id);
        if(!$user){
            return response()->json([
               'message' => 'User Not Found'
            ],404);
        }
        return response()->json($user);
    }

    public function updatePassword(Request $request, User $user){
        $updatePasswordData = $request->validate([
            'password'=>'required|min:8'
        ]);
        $user->password = Hash::make($updatePasswordData['password']);
        $user->save();
        return response()->json([
           'message' => 'Password Updated ',
        ]);
    }




    public function login(Request $request){
        $loginUserData = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|min:8'
        ]);
        $user = User::where('email',$loginUserData['email'])->first();
        if(!$user || !Hash::check($loginUserData['password'],$user->password)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;

        return response()->json(['data'=>array($loginUserData['email'],$loginUserData['password']),
            'token' => $token,
        ]);
    }



    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
          "message"=>"logged out"
        ]);
    }


    public function getUserWithImage($id)
    {
        // Assuming you have a User model with an 'image' field that stores image filenames
        $user = User::find($id);

        // Construct the full image URL
        if ($user && $user->image) {
            $user->image = url('storage/' . $user->image);
        } else {
            $user->image = null; // Fallback if no image exists
        }

        return response()->json($user);
    }

    public function searchUser(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'searchTerm' => 'required|string|max:255',
        ]);

        $searchTerm = $request->input('searchTerm');

        // Search by username or referralId
        $user = User::where('username', $searchTerm)
                    ->orWhere('referral', $searchTerm)
                    ->first();

        if ($user) {
            return response()->json([
                'status' => 'success',
                'data' => $user,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }
    }
}
