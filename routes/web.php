<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirstController;
use App\Http\Controllers\PhotoController;
use App\Models\Photo;
use App\Models\Album;


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
    // load albums and their photos based on database dump structure
    $albums = Album::with('photos')->orderBy('id','desc')->get();
    return view('index', compact('albums'));
});

// photo persistence routes (store / destroy)
Route::post('/photos', [PhotoController::class, 'store'])->name('photos.store');
Route::get('/photos/{photo}/image', [PhotoController::class, 'image'])->name('photos.image');
Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');
