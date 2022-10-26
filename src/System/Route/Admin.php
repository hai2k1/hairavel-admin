<?php

use Illuminate\Support\Facades\Route;


Route::get('', ['uses' => 'Modules\System\Admin\Index@index', 'desc' => 'System Homepage'])->name('admin.index');

/**
 * User login
 */
Route::post('login', 'Modules\System\Admin\Login@submit')->name('admin.login.submit');
Route::get('login/logout', 'Modules\System\Admin\Login@logout')->name('admin.login.logout');
Route::get('login/check', 'Modules\System\Admin\Login@check')->name('admin.login.check');

/**
 * User registration
 */
Route::post('register', 'Modules\System\Admin\Register@submit')->middleware('auth.manage.register')->name('admin.register.submit');

/**
 * other public
 */
Route::get('map/weather', ['uses' => 'Modules\System\Admin\Map@weather', 'desc' => 'weather service'])->name('admin.map.weather ');
