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
    // invert albums order (ascending)
        $albums = Album::with(['photos.tags'])->orderBy('id','asc')->get();
        $tags = App\Models\Tag::orderBy('nom')->get();
    return view('index', compact('albums','tags'));
});

// album show route removed — album selection and display are handled inline on the index page

// photo persistence routes (store / destroy)
Route::post('/photos', [PhotoController::class, 'store'])->name('photos.store');
Route::get('/photos/{photo}/image', [PhotoController::class, 'image'])->name('photos.image');
Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');

// attach/detach tags to photos
Route::post('/photos/{photo}/tags', [PhotoController::class, 'attachTag'])->name('photos.attachTag');
Route::delete('/photos/{photo}/tags/{tag}', [PhotoController::class, 'detachTag'])->name('photos.detachTag');
