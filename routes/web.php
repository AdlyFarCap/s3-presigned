<?php

use Illuminate\Http\Request;
use App\Http\Livewire\TestUpload;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\S3Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\FileUploadController;

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

Route::get('/s3', [S3Controller::class, 'index'])->name('s3.store');
