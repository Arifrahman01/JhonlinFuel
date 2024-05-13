
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Livewire\UserList;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome1');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::controller(UsersController::class)->group(function () {
    //     Route::get('/users', 'index')->name('users.index');
    // });
    
    Route::get('/users', UserList::class)->name('users.index');
 

});

require __DIR__.'/auth.php';
