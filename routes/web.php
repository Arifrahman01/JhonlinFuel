
<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Activity\ActivityList;
use App\Livewire\Adjustment\AdjustmentList;
use App\Livewire\Company\CompanyList;
use App\Livewire\Dashboard;
use App\Livewire\Department\DepartmentList;
use App\Livewire\Equipment\EquipmentList;
use App\Livewire\FuelConsumption;
use App\Livewire\FuelDistribution;
use App\Livewire\Fuelman\FuelmanList;
use App\Livewire\IssuePosting;
use App\Livewire\Material\MaterialList;
use App\Livewire\Period\PeriodList;
use App\Livewire\UOM\UOMList;
use App\Livewire\Plant\PlantList;
use App\Livewire\Profile;
use App\Livewire\Quota\QuotaList;
use App\Livewire\ReceiptPostingList;
use App\Livewire\ReceiptTransferPostingList;
use App\Livewire\Role\RoleList;
use App\Livewire\StockOverview;
use App\Livewire\Transaction\IssueList as TransactionIssueList;
use App\Livewire\Transaction\ReceiptList;
use App\Livewire\Transaction\ReceiptTransferList;
use App\Livewire\Transaction\TransferList;
use App\Livewire\TransferPostingList;
use App\Livewire\User\UserList;
use App\Livewire\Warehouse\WarehouseList;
use App\Livewire\Warehouse\WarehouseTransferList;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    // Route::get('/', function () {
    //     return view('welcome1');
    // });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', UserList::class)->name('users.index');

    /* data transaction */
    Route::get('/receipt', ReceiptPostingList::class)->name('receipt.index');
    Route::get('/transfer', TransferPostingList::class)->name('transfer.index');
    Route::get('/receipt-transfer', ReceiptTransferPostingList::class)->name('receipt-transfer.index');
    // Route::get('/issue', PostingList::class)->name('issue.index');
    Route::get('/issue', IssuePosting::class)->name('issue.index');

    /* Loadder */
    // Route::get('/loader/issue', TransactionList::class)->name('issue.loader');
    Route::get('/loader/issue', TransactionIssueList::class)->name('issue.loader');
    Route::get('/loader/transfer', TransferList::class)->name('transfer.loader');
    Route::get('/loader/receipt', ReceiptList::class)->name('received.loader');
    Route::get('/loader/receipt-transfer', ReceiptTransferList::class)->name('receipt-transfer.loader');

    /* Report */
    Route::get('/stock-overview', StockOverview::class)->name('stock.index');
    Route::get('/adjustment', AdjustmentList::class)->name('adjustment.index');
    Route::get('/consumption', FuelConsumption::class)->name('consumption.index');
    Route::get('/distribution', FuelDistribution::class)->name('distribution.index');

    /* Master */
    Route::get('/equipment', EquipmentList::class)->name('equipment.index');
    Route::get('/activity', ActivityList::class)->name('activity.index');
    Route::get('/company', CompanyList::class)->name('company.index');
    Route::get('/plant', PlantList::class)->name('plant.index');
    Route::get('/warehouse', WarehouseList::class)->name('warehouse.index');
    Route::get('/fuelman', FuelmanList::class)->name('fuelman.index');
    Route::get('/department', DepartmentList::class)->name('department.index');
    Route::get('/material', MaterialList::class)->name('material.index');
    Route::get('/uom', UOMList::class)->name('uom.index');
    Route::get('/period', PeriodList::class)->name('period.index');
    Route::get('/warehouse-transfer', WarehouseTransferList::class)->name('warehouse-transfer.index');
    Route::get('/quota', QuotaList::class)->name('quota.index');

    /* Manajement User */
    Route::get('/role', RoleList::class)->name('role.index');
    Route::get('/profile', Profile::class)->name('profile.index');

    Route::get('/', Dashboard::class)->name('home');
});

require __DIR__ . '/auth.php';
