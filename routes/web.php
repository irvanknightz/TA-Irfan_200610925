<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\SupplierPerformanceController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/home2', function () {
    return view('home', ['title' => 'Kalkulasi AHP']);
});

Route::get('/about', function () {
    return view('about', ['title' => 'Database']);
});

Route::get('/data', function () {
    return view('data', ['title' => 'Database']);
});

Route::get('/criteria', function (Request $request) {
    $n = $request->input('n');
    return view('create', ['title' => 'Input Kriteria AHP', 'n' => $n]);
})->name('criteria.create');

Route::post('/save_criteria', function (Request $request) {
    $criteria = $request->input('criteria');
    return redirect('/')->with('success', 'Kriteria berhasil disimpan!');
})->name('criteria.store');

Route::post('/comparison/create', function (Request $request) {
    $criteria = $request->input('criteria');
    $n = $request->input('n');
    return view('comparison', compact('criteria', 'n'));
})->name('comparison.create');

// New Route for comparison data submission
Route::post('/comparison/store', function (Request $request) {
    $comparison = $request->input('comparison');
    $n = count($comparison); // Assuming each comparison key has n elements
    // Logic to process and store comparison data in database or session
    // ...
    return redirect('/'); // Redirect to home page after processing
})->name('comparison.store');

Route::get('/create', function () {
    return view('create');
})->name('comparison.create');

Route::get('/comparison/create', [ComparisonController::class, 'create'])->name('comparison.create');
Route::post('/comparison/storeCriteria', [ComparisonController::class, 'storeCriteria'])->name('comparison.storeCriteria');
Route::post('/comparison/storeComparison', [ComparisonController::class, 'storeComparison'])->name('comparison.storeComparison');
Route::post('/store-criteria', [ComparisonController::class, 'storeCriteria'])->name('comparison.storeCriteria');
Route::post('/store-comparison', [ComparisonController::class, 'storeComparison'])->name('comparison.storeComparison');
Route::get('/about', [SupplierPerformanceController::class, 'index'])->name('about');

Route::get('/results', [ComparisonController::class, 'showResults'])->name('results.show');

Route::resource('supplier-performances', SupplierPerformanceController::class);

// Create a new route for displaying the data table
Route::get('/data', [SupplierPerformanceController::class, 'showData'])->name('data');
Route::get('/data/{year?}', [SupplierPerformanceController::class, 'showData'])->name('data.show');

// Ensure no duplicate route definitions
Route::get('/about', [SupplierPerformanceController::class, 'index'])->name('supplier_performance.index');
Route::get('/about/create', [SupplierPerformanceController::class, 'create'])->name('supplier_performance.create');
Route::post('/about', [SupplierPerformanceController::class, 'store'])->name('supplier_performance.store');
Route::get('/supplier-performances/{supplierPerformance}/edit', [SupplierPerformanceController::class, 'edit'])->name('supplier-performances.edit');
Route::put('/supplier-performances/{supplierPerformance}', [SupplierPerformanceController::class, 'update'])->name('supplier-performances.update');
Route::get('/about', [SupplierPerformanceController::class, 'index'])->name('about');

Route::get('/compare', [SupplierPerformanceController::class, 'compare'])->name('supplier-performances.compare');
Route::post('/compare', [SupplierPerformanceController::class, 'storeComparison'])->name('supplier-performances.storeComparison');


Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'authenticate']);

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');