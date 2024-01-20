<?php

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

Route::get('/test-database', function () {
  try {
    DB::connection()->getPdo();
    print_r("Connected successfully to: " . DB::connection()->getDatabaseName());
  } catch (\Exception $e) {
    die("Could not connect to the database.  Please check your configuration. Error:" . $e);
  }
});
