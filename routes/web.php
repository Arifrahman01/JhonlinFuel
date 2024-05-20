
<?php

use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Adjustment\AdjustmentList;
use App\Livewire\Quota\QuotaList;
use App\Livewire\Issue\IssueList;
use App\Livewire\Transaction\PostingList;
use App\Livewire\Transaction\TransactionList;

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

    Route::get('/posting', PostingList::class)->name('posting.index');
    Route::get('/transaction', TransactionList::class)->name('transaction.index');



    Route::get('/issue', IssueList::class)->name('issue.index');

    Route::get('/soh-overview', [MaterialController::class, 'sohOverview'])->name('soh.index');
    Route::get('/adjustment', AdjustmentList::class)->name('adjustment.index');
});

require __DIR__ . '/auth.php';
