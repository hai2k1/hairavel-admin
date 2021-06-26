<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'app' => '系统工具'
], function () {
    Route::group([
        'group' => '自定义页面'
    ], function () {
        Route::get('page/{id}', ['uses' => 'Modules\Tools\Web\Pages@index', 'desc' => '页面'])->name('web.pages');
    });

    Route::group([
        'prefix' => 'form',
        'group' => '自定义表单'
    ], function () {
        Route::get('list/{id}', ['uses' => 'Modules\Tools\Web\Form@index', 'desc' => '列表'])->name('web.form.list');
        Route::get('info/{id}', ['uses' => 'Modules\Tools\Web\Form@info', 'desc' => '详情'])->name('web.form.info');
        Route::post('push/{id}', ['uses' => 'Modules\Tools\Web\Form@push', 'desc' => '发布'])->name('web.form.push');
    });
});