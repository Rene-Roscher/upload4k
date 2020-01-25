<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HandleController@index')->name('upload.index');
Route::post('/upload', 'HandleController@upload')->name('upload');
Route::get('/download/{downloadKey}', 'HandleController@download');