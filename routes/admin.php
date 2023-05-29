<?php

//use Vesaka\Core\Http\Controllers\Admin\DashboardController;
Route::namespace('Admin')
        ->middleware('auth')
        ->group(function () {
            Route::get('model/items', 'ModelController@datatable')->name('model.items');
            Route::get('list/items', 'ModelController@paginate')->name('paginate.items');

            Route::resource('user', 'UserController');
            Route::resource('image', 'ImageController');
            Route::resource('website', 'WebsiteController');
            Route::resource('skill', 'SkillController');
            Route::resource('model', 'ModelController');
            Route::resource('category', 'CategoryController');
            Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');
});
