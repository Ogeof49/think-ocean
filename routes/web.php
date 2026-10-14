<?php

use App\Http\Controllers\Admin\ThinkOceanCmsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuluRentalsController;
use App\Http\Controllers\OmneySafariController;
use App\Http\Controllers\SafariBookingController;
use App\Http\Controllers\ThinkOceanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OmneySafariController::class, 'home'])->name('home');
Route::get('/think-ocean', [ThinkOceanController::class, 'index'])->name('think-ocean');
Route::get('/think-ocean/pages/{page}', [ThinkOceanController::class, 'page'])
    ->whereIn('page', ['about', 'what-we-do', 'projects', 'get-involved', 'news', 'contact', 'donate'])
    ->name('think-ocean.pages.show');
Route::get('/think-ocean/live', [ThinkOceanController::class, 'live'])->name('think-ocean.live');
Route::get('/think-ocean/projects/{project:slug}', [ThinkOceanController::class, 'project'])->name('think-ocean.projects.show');
Route::get('/think-ocean/news/{post:slug}', [ThinkOceanController::class, 'post'])->name('think-ocean.posts.show');
Route::get('/think-ocean/privacy', [ThinkOceanController::class, 'privacy'])->name('think-ocean.privacy');
Route::get('/think-ocean/accessibility', [ThinkOceanController::class, 'accessibility'])->name('think-ocean.accessibility');
Route::post('/think-ocean/contact', [ThinkOceanController::class, 'sendMessage'])->middleware('throttle:5,1')->name('think-ocean.contact.store');
Route::post('/think-ocean/subscribe', [ThinkOceanController::class, 'subscribe'])->middleware('throttle:5,1')->name('think-ocean.subscribe');

Route::middleware(['auth', 'can:manage-think-ocean'])->prefix('think-ocean/admin')->name('think-ocean.admin.')->group(function (): void {
    Route::get('/', [ThinkOceanCmsController::class, 'index'])->name('index');
    Route::put('/settings', [ThinkOceanCmsController::class, 'updateSettings'])->name('settings.update');
    Route::put('/live', [ThinkOceanCmsController::class, 'updateLive'])->name('live.update');
    Route::post('/projects', [ThinkOceanCmsController::class, 'storeProject'])->name('projects.store');
    Route::put('/projects/{project}', [ThinkOceanCmsController::class, 'updateProject'])->name('projects.update');
    Route::delete('/projects/{project}', [ThinkOceanCmsController::class, 'destroyProject'])->name('projects.destroy');
    Route::post('/posts', [ThinkOceanCmsController::class, 'storePost'])->name('posts.store');
    Route::put('/posts/{post}', [ThinkOceanCmsController::class, 'updatePost'])->name('posts.update');
    Route::delete('/posts/{post}', [ThinkOceanCmsController::class, 'destroyPost'])->name('posts.destroy');
    Route::patch('/messages/{message}/read', [ThinkOceanCmsController::class, 'markMessageRead'])->name('messages.read');
    Route::delete('/messages/{message}', [ThinkOceanCmsController::class, 'destroyMessage'])->name('messages.destroy');
    Route::delete('/subscribers/{subscriber}', [ThinkOceanCmsController::class, 'destroySubscriber'])->name('subscribers.destroy');
});
Route::get('/admin/homeland-estates', [GuluRentalsController::class, 'index'])->name('gulu-rentals');
Route::get('/admin/homeland-estates/buy', [GuluRentalsController::class, 'buy'])->name('gulu-rentals.buy');
Route::get('/admin/homeland-estates/rentals', [GuluRentalsController::class, 'rentals'])->name('gulu-rentals.rentals');
Route::get('/admin/homeland-estates/about', [GuluRentalsController::class, 'about'])->name('gulu-rentals.about');
Route::get('/admin/homeland-estates/contact', [GuluRentalsController::class, 'contact'])->name('gulu-rentals.contact');
Route::get('/admin/homeland-estates/list-property', [GuluRentalsController::class, 'listProperty'])->name('gulu-rentals.list-property');
Route::get('/admin/homeland-estates/properties/{property}', [GuluRentalsController::class, 'show'])->name('gulu-rentals.properties.show');
Route::redirect('/admin/v1', '/admin/homeland-estates', 301);
Route::get('/admin/v1/properties/{property}', function (int $property) {
    return redirect()->route('gulu-rentals.properties.show', $property, 301);
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/think-ocean/login', [AuthController::class, 'showThinkOceanLogin'])->name('think-ocean.login');
    Route::post('/think-ocean/login', [AuthController::class, 'loginThinkOcean'])->name('think-ocean.login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::redirect('/omney-safari', '/')->name('omney-safari');
Route::get('/omney-safari/bookings', [OmneySafariController::class, 'bookings'])->name('omney-safari.bookings');
Route::post('/omney-safari/bookings', [SafariBookingController::class, 'store'])->name('omney-safari.bookings.store');
Route::get('/omney-safari/faq', [OmneySafariController::class, 'faq'])->name('omney-safari.faq');
Route::get('/omney-safari/about-us', [OmneySafariController::class, 'about'])->name('omney-safari.about');
Route::get('/omney-safari/scr', [OmneySafariController::class, 'scr'])->name('omney-safari.scr');
Route::get('/omney-safari/bookings/pesapal/callback', [SafariBookingController::class, 'callback'])->name('omney-safari.booking.callback');
Route::get('/omney-safari/bookings/pesapal/ipn', [SafariBookingController::class, 'ipn'])->name('omney-safari.booking.ipn');
