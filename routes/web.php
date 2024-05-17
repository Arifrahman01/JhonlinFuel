
<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Quota\QuotaList;
use App\Livewire\Issue\IssueList;
use App\Livewire\User\UserList;

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
    
    Route::get('/users', UserList::class)->name('users.index'); 
    Route::get('/qouta', QuotaList::class)->name('qouta.index'); 


    Route::get('/issue', IssueList::class)->name('issue.index');
});

require __DIR__ . '/auth.php';
