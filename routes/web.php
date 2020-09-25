<?php

use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\BooksController;
use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\CheckoutBookController;
use App\Http\Controllers\CheckinBookController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('books', [BooksController::class, 'store']);
Route::patch('books/{book}', [BooksController::class, 'update']);
Route::delete('books/{book}', [BooksController::class, 'destroy']);


Route::get('/authors/create', [AuthorsController::class, 'create']);
Route::post('authors', [AuthorsController::class, 'store']);
Route::post('/checkout/{book}', [CheckoutBookController::class, 'store']);
Route::post('/checkin/{book}', [CheckinBookController::class, 'store']);


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
