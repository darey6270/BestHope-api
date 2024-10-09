<?php

use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PosterController;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(UserAuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::get('/user/{id}', 'getUser');
    Route::get('/users', 'getUsers');
    Route::get('/user/{id}', 'delete');
    Route::put('/user/{id}', 'update');
    Route::get('/logout', 'logout');
    Route::get('/getImage/{id}', 'getUserWithImage');
    Route::get('/search-user', 'searchUser');
});

Route::controller(PosterController::class)->group(function(){

Route::get('/posters','index');        // Get all images
Route::get('/posters/{id}', 'show');    // Get specific image by ID
Route::post('/posters', 'store');       // Upload new image
Route::post('/posters/{id}', 'update'); // Update existing image
Route::delete('/posters/{id}','destroy'); // Delete an image

});

Route::controller(WithdrawalController::class)->group(function(){
    Route::post('/withdrawal/store', 'store');
    Route::get('/withdrawal', 'all');
    Route::put('/withdrawal/{id}', 'update');
});


Route::controller(DepositController::class)->group(function(){
    Route::post('/deposit/store', 'store');
    Route::get('/deposit', 'all');
    Route::put('/deposit/{id}', 'update');
});


// Route::middleware('auth:sanctum')->group( function () {
//     Route::resource('products', ProductController::class);
// });
// Route::post('/login', [UserAuthController::class, 'login']);
// Route::post('/register', [UserAuthController::class, 'register']);
// Route::resource('categories', CategoryController::class);
