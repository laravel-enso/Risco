<?php

Route::group(['namespace'  => 'LaravelEnso\Risco\app\Http\Controllers',
              'prefix'     => 'Risco',
              'as'         => 'Risco.',
              'middleware' => ['web', 'auth', 'core'], ], function () {
                  Route::get('get/{subscribedApp}', 'RiscoController@get')->name('get');
                  Route::get('getConsolidated', 'RiscoController@getConsolidated')->name('getConsolidated');
                  Route::delete('clearLaravelLog/{subscribedApp}', 'RiscoController@clearLaravelLog')->name('clearLaravelLog');
                  Route::post('', 'RiscoController@store')->name('store');
                  Route::get('', 'RiscoController@index')->name('index');
                  Route::delete('{subscribedApp}', 'RiscoController@destroy')->name('destroy');
                  Route::post('setMaintenanceMode/{subscribedApp}', 'RiscoController@setMaintenanceMode')->name('setMaintenanceMode');
                  Route::post('updatePreferences/{subscribedApp}', 'RiscoController@updatePreferences')->name('updatePreferences');
              });

//Route::group(['namespace'  => 'LaravelEnso\Risco\app\Http\Controllers',
//              'middleware' => ['web', 'auth', 'core'], ], function () {
//                  Route::resource('subscribedApp', 'RiscoController');
//              });
