<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;

// Public API routes (no authentication required)
Route::post('/login', [AuthController::class, 'apiLogin']);

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');

// All API routes require authentication
// Route::middleware(['auth:sanctum'])->group(function () {

//   Route::post('/logout', [AuthController::class, 'apiLogout']);
//   // job invoice routes
//   Route::prefix('invoices')->group(function () {
//     Route::get('/', [InvoiceController::class, 'index'])->name('api.invoices.index');
//     Route::post('/', [InvoiceController::class, 'store'])->name('api.invoices.store');
//     Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('api.invoices.edit');
//     Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('api.invoices.update');
//   });
// }); // End of auth:sanctum middleware group
