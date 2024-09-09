<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(__DIR__.'/admin/index.php');
// Route::prefix('assessment')->name('assessment.')->group(__DIR__.'/assessment/index.php');
// Route::prefix('library')->name('library.')->group(__DIR__.'/library/index.php');
// Route::prefix('appeal')->name('appeal.')->group(__DIR__.'/appeal/index.php');
