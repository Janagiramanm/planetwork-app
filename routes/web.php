<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;


use App\Http\Livewire\Customers;
use App\Http\Livewire\Tasks;
use App\Http\Livewire\Roles;
use App\Http\Livewire\Users;
use App\Http\Livewire\Cities;
use App\Http\Livewire\Jobs;
use App\Http\Livewire\Leaves;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Reports;
use App\Http\Livewire\Holidays;
use App\Http\Livewire\Attendances;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('dashboard', Dashboard::class)->name('dashboard');

Route::get('customers', Customers::class)->name('customers');

Route::get('roles', Roles::class)->name('roles');

Route::get('tasks', Tasks::class)->name('tasks');

Route::get('users', Users::class)->name('users');

Route::get('cities', Cities::class)->name('cities');

Route::get('jobs', Jobs::class)->name('jobs');

Route::get('leaves', Leaves::class)->name('leaves');

Route::get('reports', Reports::class)->name('reports');

Route::get('holidays', Holidays::class)->name('holidays');

Route::get('attendance', Attendances::class)->name('attendance');

Route::get('cron-report', [App\Http\Controllers\CronJobController::class, 'cronWrokReport'])->name('cron-report');

Route::get('/notification', [App\Http\Controllers\NotificationController::class, 'index'])->name('home');
Route::post('/save-token', [App\Http\Controllers\NotificationController::class, 'saveToken'])->name('save-token');
Route::post('/send-notification', [App\Http\Controllers\NotificationController::class, 'sendNotification'])->name('send.notification');





