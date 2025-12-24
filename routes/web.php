<?php

use App\Livewire\Brand\BrandCreate;
use App\Livewire\Brand\BrandEdit;
use App\Livewire\Brand\BrandIndex;
use App\Livewire\Category\CategoryCreate;
use App\Livewire\Category\CategoryEdit;
use App\Livewire\Category\CategoryIndex;
use App\Livewire\Dashboard;
use App\Livewire\Item\ItemCreate;
use App\Livewire\Item\ItemEdit;
use App\Livewire\Item\ItemIndex;
use App\Livewire\Quartz\QuartzCreate;
use App\Livewire\Quartz\QuartzEdit;
use App\Livewire\Quartz\QuartzIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function () {

    Route::get('/', Dashboard::class)->name('dashboard');

    Route::prefix('categories')->group(function () {
        Route::get('/', CategoryIndex::class)->name('categories.index');
        Route::get('/create', CategoryCreate::class)->name('categories.create');
        Route::get('/{id}/edit', CategoryEdit::class)->name('categories.edit');
    });
    Route::prefix('brands')->group(function () {
        Route::get('/', BrandIndex::class)->name('brands.index');
        Route::get('/create', BrandCreate::class)->name('brands.create');
        Route::get('/{id}/edit', BrandEdit::class)->name('brands.edit');
    });
    Route::prefix('items')->group(function () {
        Route::get('/', ItemIndex::class)->name('items.index');
        Route::get('/create', ItemCreate::class)->name('items.create');
        Route::get('/{id}/edit', ItemEdit::class)->name('items.edit');
        Route::get('/items/{id}', \App\Livewire\Item\ItemShow::class)->name('items.show');
    });
    Route::prefix('shops')->group(function () {
        Route::get('/', \App\Livewire\Shop\ShopIndex::class)->name('shops.index');
        Route::get('/create', \App\Livewire\Shop\ShopCreate::class)->name('shops.create');
        Route::get('/{id}/edit', \App\Livewire\Shop\ShopEdit::class)->name('shops.edit');
    });
    Route::prefix('units')->group(function () {
        Route::get('/', \App\Livewire\Unit\UnitIndex::class)->name('units.index');
        Route::get('/create', \App\Livewire\Unit\UnitCreate::class)->name('units.create');
        Route::get('/{id}/edit', \App\Livewire\Unit\UnitEdit::class)->name('units.edit');
    });
    Route::prefix('quartzs')->group(function () {
        Route::get('/', QuartzIndex::class)->name('quartzs.index');
        Route::get('/create', QuartzCreate::class)->name('quartzs.create');
        Route::get('/{quartz}/edit', QuartzEdit::class)->name('quartzs.edit');
    });
});

require __DIR__ . '/auth.php';
