<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// routes/web.php

// use App\Http\Middleware\Fail2Ban;
// use Illuminate\Support\Facades\Auth;

// Route::middleware([Fail2Ban::class])->group(function () {
//     Route::get('/login', function () {
//         return view('auth.login');
//     })->name('login');

//     Route::post('/login', function (Illuminate\Http\Request $request) {
//         $credentials = $request->validate([
//             'email' => 'required|email',
//             'password' => 'required',
//         ]);

//         if (Auth::attempt($credentials)) {
//             $request->session()->regenerate();

//             return redirect()->intended('/');
//         }

//         return back()->withErrors([
//             'email' => 'Email atau password salah.',
//         ]);
//     });
// });


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['namespace'=>'\App\\Http\\Controllers'], function (){
    Route::get('login','LoginController@formLogin');
    Route::post('login','LoginController@login')->middleware('throttle:login');
});

require __DIR__.'/auth.php';