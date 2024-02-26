<?php

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

use App\Http\Controllers\Admin\OpenApiDocumentation;

Route::get('/', [OpenApiDocumentation::class, 'index'])->name('doc');

Route::name('openapi.')
    ->prefix('/api')
    ->group(static function () {
        Route::get('/spec', [OpenApiDocumentation::class, 'openApiSpec'])->name('spec');
    });
