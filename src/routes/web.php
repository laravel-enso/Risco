<?php

Route::group(['namespace'  => 'LaravelEnso\Risco\app\Http\Controllers',
              'prefix'     => 'risco',
              'as'         => 'risco.',
              'middleware' => ['web', 'auth', 'core'], ], function () {
                  Route::get('identification', 'RiscoController@identification')->name('identification');
                  Route::get('', 'RiscoController@index')->name('index');
              });
