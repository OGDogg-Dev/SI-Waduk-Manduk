<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn() => Inertia::render('Home/Index'))->name('home');
Route::get('/attractions', fn() => Inertia::render('Attractions/Index'))->name('attractions.index');
Route::get('/attractions/{slug}', fn(string $slug) => Inertia::render('Attractions/Show', ['slug' => $slug]))
    ->name('attractions.show');
Route::get('/tickets', fn() => Inertia::render('Tickets/Index'))->name('tickets.index');
Route::get('/events', fn() => Inertia::render('Events/Index'))->name('events.index');
Route::get('/announcements', fn() => Inertia::render('Announcements/Index'))->name('announcements.index');
Route::get('/merchants', fn() => Inertia::render('Merchants/Index'))->name('merchants.index');
Route::get('/contact', fn() => Inertia::render('Contact/Index'))->name('contact.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', fn() => Inertia::render('dashboard'))->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
