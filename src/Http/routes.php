<?php

Route::group([
    'namespace'  => 'Fubbleskag\Seat\Altcheck\Http\Controllers',
    'prefix' => 'altcheck',
], function () {
    Route::group([
        'middleware' => ['web', 'auth', 'locale'],
    ], function () {
        Route::get('/', [
            'as'   => 'altcheck.view',
            'uses' => 'AltcheckController@getAltcheckView'
        ]);
        Route::post('/runReport', [
            'as'   => 'altcheck.runreport',
            'uses' => 'AltcheckController@getAltcheckReport'
        ]);
    });
});