<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->group(__DIR__.'/user.php');
// Route::prefix('lib-category')->name('lib-category.')->group(__DIR__.'/lib-category.php');
// Route::prefix('lib-category-item')->name('lib-category-item.')->group(__DIR__.'/lib-category-item.php');
// Route::prefix('lib-category-item-value')->name('lib-category-item-value.')->group(__DIR__.'/lib-category-item-value.php');
