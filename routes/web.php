<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

// Route::get('/seed', [\Database\Seeders\PostVideoSeeder::class, 'run']);
Route::get('/', [PostController::class, 'home'])->name('home');

// Jetstream
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post:slug}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');

    // Locations
    Route::get('/locations/create/{complicated?}', [LocationController::class, 'create'])->name('location.create');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');

    // Choose user's favorite regions
    Route::post('/regions/chose-favorite', [RegionController::class, 'chooseFavorite'])->name('regions.chose-favorite');

    // Profile
    //Route::get('/user/profile', [ProfileController::class, 'profile'])->name('profile.show');
    Route::get('/profile/locations', [ProfileController::class, 'locations'])->name('user.locations.index');
    Route::get('/profile/comments', [ProfileController::class, 'comments'])->name('user.comments.index');
});

// Votes
Route::post('/vote', [VoteController::class, 'vote'])->name('vote');
Route::post('/unvote', [VoteController::class, 'unvote'])->name('unvote');


// Posts
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

// Users
Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

// Rating
Route::get('/rating', [RatingController::class, 'index'])->name('rating');

// SOCIALITE
// Route::get('socialite/{provider}', [SocialiteController::class, 'redirectToProvider']);
// Route::get('socialite/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);
