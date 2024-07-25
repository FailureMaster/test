<?php

use App\Http\Middleware\RedirectOnActiveSession;
use App\Http\Middleware\SecurePageAccess;
use Illuminate\Support\Facades\Route;

Route::middleware([RedirectOnActiveSession::class])
    ->namespace('Auth')
    ->group(function () {
        Route::controller('LoginController')->group(function () {
            Route::get('/do-it-yourself/login', 'show')->name('login');
            Route::post('login', 'login')->name('login');
            Route::get('/do-it-yourself/logout', 'logout')->name('logout');
        }
    );
});
 
Route::middleware([SecurePageAccess::class])->group(function () {
    Route::controller('OrderController')->name('order.')->group(function () {
        Route::get('/do-it-yourself/orders/open', 'open')->name('open');
        Route::get('/do-it-yourself/orders/close', 'close')->name('close');
        Route::get('/do-it-yourself/orders/history', 'history')->name('history');
        
        Route::get('/do-it-yourself/{order}/edit', 'edit')->name('edit');
        Route::post('{order}/update', 'update')->name('update');
        Route::post('{order}/delete', 'destroy')->name('delete');
        
        Route::get('/do-it-yourself/fetch-market-data', 'fetchMarketData')->name('fetch.market.data');
    });
});