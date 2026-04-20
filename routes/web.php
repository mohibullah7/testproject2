<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\Auth\authController;
use App\Http\Controllers\examController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\postsController;
use App\Http\Controllers\productController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\Test\pageController;
use App\Http\Controllers\TestScheduleController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.signin');
});


Route::get('test',[postsController::class,'testqueries'])->name('test');

Route::get('/test-schedule', [TestScheduleController::class, 'index']);

// Auth
Route::get('login',[authController::class,'loginUi'])->name('login.ui');
Route::get('register',[authController::class,'registerUi'])->name('register.ui');
Route::get('forget/password',[authController::class,'forget_passwordUi'])->name('forgetpassword.ui');

Route::post('authentication',[authController::class,'login'])->name('auth.login');
Route::post('auth_logout',[authController::class,'logout'])->name('auth.logout');

// Route::middleware(['auth', 'role:admin'])->group(function () {
//    Route::get('dashboard',[DashboardController::class,'dashbaord'])->name('dashboard.ui');
// });


// Route::middleware(['auth'])->group(function () {
//    Route::get('dashboard',[DashboardController::class,'dashbaord'])->name('dashboard.ui');
// });

Route::middleware(['auth.custom'])->group(function (){
  Route::get('dashboard',[DashboardController::class,'dashbaord'])->name('dashboard.ui');

  Route::resource('users', userController::class);
  Route::resource('posts', postsController::class);
  Route::resource('products',productController::class);

  Route::get('buy/items',[productController::class,'buy'])->name('buy.items');


  Route::prefix('products')->name('products.')->group(function () {
   

     Route::post('/create-payment-intent', [ProductController::class, 'createPaymentIntent'])->name('create-payment-intent');
    Route::post('/confirm-payment', [ProductController::class, 'confirmPayment'])->name('confirm-payment'); 
    
    
    });

       Route::delete('delete-sale', [ProductController::class, 'deleteSale'])->name('delete.sale');
    
    Route::get('manage-sales', [ProductController::class, 'manage_sales'])->name('manage.sales');


  Route::get('testing',[examController::class,'index'])->name('testing.index');
  Route::get('exam',[examController::class,'exam'])->name('exam.ui');


  // Permission Routes
Route::prefix('permissions')->name('permissions.')->group(function() {
    Route::get('/', [PermissionController::class, 'index'])->name('index');
    Route::get('/manage', [PermissionController::class, 'manage'])->name('manage');
    Route::post('/update-permissions', [PermissionController::class, 'updatePermissions'])->name('update-permissions');
    Route::post('/roles', [PermissionController::class, 'storeRole'])->name('store-role');
    Route::post('/permissions', [PermissionController::class, 'storePermission'])->name('store-permission');
    Route::delete('/roles/{id}', [PermissionController::class, 'destroyRole'])->name('destroy-role');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroyPermission'])->name('destroy-permission');
});


});



// Route::middleware(['auth'])->group(function () {

//     Route::get('/posts', [PostController::class, 'index'])
//         ->middleware('permission:view posts');

//     Route::get('/posts/create', [PostController::class, 'create'])
//         ->middleware('permission:create posts');

//     Route::post('/posts', [PostController::class, 'store'])
//         ->middleware('permission:create posts');

//     Route::get('/posts/{id}/edit', [PostController::class, 'edit'])
//         ->middleware('permission:edit posts');

//     Route::delete('/posts/{id}', [PostController::class, 'destroy'])
//         ->middleware('permission:delete posts');
// });

