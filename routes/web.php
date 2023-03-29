<?php

use App\Http\Controllers\StripeController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

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



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('single-charge',[SubscriptionController::class,'singleCharge'])->name('single.charge');
Route::get('plans-create',[SubscriptionController::class,'showPlanForm'])->name('plans.create');
Route::post('plans-store',[SubscriptionController::class,'savePlan'])->name('plans.store');
Route::get('plans',[SubscriptionController::class,'allPlans'])->name('all.store');
Route::get('plans-checkout/{planID}',[SubscriptionController::class,'checkoutPlan'])->name('plans.checkout');
Route::post('plans-process',[SubscriptionController::class,'checkoutProcess'])->name('checkout.process');
