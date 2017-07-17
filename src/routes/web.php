<?php

Route::group(['namespace'  => 'LaravelEnso\Risco\app\Http\Controllers',
              'prefix'     => 'risco',
              'as'         => 'risco.',
              'middleware' => ['web', 'auth', 'core'], ], function () {
                  Route::get('', 'RiscoController@index')->name('index');
                  Route::get('query', 'RiscoController@query')->name('query');
              });
