<?php
//use Vesaka\Core\Http\Controllers\Admin\DashboardController;
Route::namespace('Admin')
        ->middleware('auth')
        ->group(function() {
            Route::get('model/items', 'ModelController@datatable')->name('model.items');
            
            Route::resource('user', 'UserController');
            Route::resource('image', 'ImageController');
            Route::resource('model', 'ModelController');
            Route::resource('category', 'CategoryController');
            Route::get('dashboard', 'DashboardController@dashboard');
            
            
        });
