<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'tools',
    'auth_app' => 'Extension tool'
], function () {

    Route::group([
        'auth_group' => 'regional data'
    ], function () {
        Route::manage(\Modules\Tools\Admin\Area::class)->only(['index'])->make();
        Route::get('area/add', ['uses' => 'Modules\Tools\Admin\Area@import', 'desc' => 'import'])->name('admin.tools.area. import');
        Route::post('area/store', ['uses' => 'Modules\Tools\Admin\Area@importData', 'desc' => 'import data'])->name('admin.tools.area .importData');
        Route::post('area/del/{id?}', ['uses' => 'Modules\Tools\Admin\Area@del', 'desc' => 'delete'])->name('admin .tools.area.del');
    });

    Route::group([
        'auth_group' => 'custom form'
    ], function () {
        Route::manage(\Modules\Tools\Admin\Form::class)->only(['index', 'data', 'page', 'save', 'del'])->make();
        Route::get('form/setting/{id}', ['uses' => 'Modules\Tools\Admin\Form@setting', 'desc' => 'settings'])->name('admin. tools.form.setting');
        Route::post('form/setting/{id}', ['uses' => 'Modules\Tools\Admin\Form@settingSave', 'desc' => 'setting data'])->name('admin .tools.form.setting.save');
    });

    Route::group([
        'auth_group' => 'form data'
    ], function () {
        Route::manage(\Modules\Tools\Admin\FormData::class)->only(['index', 'data', 'page', 'save', 'status', 'del'])->make ();
    });

    Route::group([
        'auth_group' => 'link management'
    ], function () {
        Route::manage(\Modules\Tools\Admin\Url::class)->only(['data'])->make();
    });

    // Generate Route Make
});
