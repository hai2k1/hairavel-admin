<?php

use Illuminate\Support\Facades\Route;

// 系统资源
Route::group(['public' => true], function () {
    /**
     * Public resource
     */
    Route::get('menu', ['uses' => 'Modules\System\Admin\Index@menu', 'desc' => 'system menu'])->name('admin.menu');
    Route::get('side/{app}', ['uses' => 'Modules\System\Admin\Index@side', 'desc' => 'system sidebar'])->name('admin.side');
    Route::get('development', ['uses' => 'Modules\System\Admin\Development@index', 'desc' => 'Operation and maintenance overview'])->name('admin.development');
    Route::get('fileManage', ['uses' => 'Modules\System\Admin\FileManage@handle', 'desc' => 'file manager'])->name('admin.filemanage');
    Route::post('upload', ['uses' => 'Modules\System\Admin\Upload@ajax', 'desc' => 'File Upload'])->name('admin.upload');
    Route::post('uploadRemote', ['uses' => 'Modules\System\Admin\Upload@remote', 'desc' => 'remote save'])->name('admin.uploadRemote');
    Route::get('map/ip', ['uses' => 'Modules\System\Admin\Map@weather', 'desc' => 'ip parsing'])->name('admin.map.ip');

    /**
     * notification
     */
    Route::get('notification', ['uses' => 'Modules\System\Admin\Index@getNotify', 'desc' => 'Get notifications', 'ignore_operate_log' => true])->name('admin.notification');
    Route::get('notification/read', ['uses' => 'Modules\System\Admin\Index@readNotify', 'desc' => 'read message'])->name('admin.notification.read');
    Route::get('notification/del', ['uses' => 'Modules\System\Admin\Index@delNotify', 'desc' => 'delete message'])->name('admin.notification.del');

    /**
     * System Statistics
     */
    Route::get('system/visitorApi/loadTotal', ['uses' => 'Modules\System\Admin\VisitorApi@loadTotal', 'desc' => 'statistics'])->name('admin.system.visitorApi.loadTotal');
    Route::get('system/visitorApi/loadDelay', ['uses' => 'Modules\System\Admin\VisitorApi@loadDelay', 'desc' => 'Latency Statistics'])->name('admin.system.visitorApi.loadDelay');
    Route::get('system/visitorOperate/loadData', ['uses' => 'Modules\System\Admin\VisitorOperate@loadData', 'desc' => 'Operation log'])->name('admin.system.visitorOperate.loadData');
    Route::get('system/visitorViews/info', ['uses' => 'Modules\System\Admin\VisitorViews@info', 'desc' => 'Visitor Details'])->name('admin.system.visitorViews.info');

});

/**
 * system applications
 */
Route::group([
    'prefix' => 'system',
    'auth_app' => 'system applications'
], function () {
    /**
     * system settings
     */
    Route::group([
        'auth_group' => 'system settings'
    ], function () {
        Route::get('setting', ['uses' => 'Modules\System\Admin\Setting@handle', 'desc' => 'system settings'])->name('admin.system.setting');
        Route::post('setting/store', ['uses' => 'Modules\System\Admin\Setting@save', 'desc' => 'Save Settings'])->name('admin.system.setting.save');
    });
    /**
     * system user
     */
    Route::group([
        'auth_group' => 'system user'
    ], function () {
        Route::manage(\Modules\System\Admin\User::class)->only(['index', 'data', 'page', 'save', 'del'])->make();
    });
    /**
     * system role
     */
    Route::group([
        'auth_group' => 'system role'
    ], function () {
        Route::manage(\Modules\System\Admin\Role::class)->only(['index', 'data', 'page', 'save', 'del'])->make();
    });
    /**
     * Operation log
     */
    Route::group([
        'auth_group' => 'Operation log'
    ], function () {
        Route::get('operate', ['uses' => 'Modules\System\Admin\VisitorOperate@index', 'desc' => 'List'])->name('admin.system.operate');
        Route::get('operate/ajax', ['uses' => 'Modules\System\Admin\VisitorOperate@ajax', 'desc' => 'List'])->name('admin.system.operate.ajax');
        Route::get('operate/info/{id}', ['uses' => 'Modules\System\Admin\VisitorOperate@info', 'desc' => 'Details'])->name('admin.system.operate.info');
    });

    Route::group([
        'auth_group' => 'file management'
    ], function () {
        Route::manage(\Modules\System\Admin\FilesDir::class)->only(['index'])->make();
        Route::manage(\Modules\System\Admin\Files::class)->only(['index', 'del'])->make();
    });

    Route::group([
        'auth_group' => 'Interface Statistics'
    ], function () {
        Route::manage(\Modules\System\Admin\VisitorApi::class)->only(['index'])->make();
    });

    /**
     *system queue
     */
    Route::group([
        'auth_group' => 'task scheduling'
    ], function () {
        Route::manage(\Modules\System\Admin\Task::class)->only(['index'])->make();
    });


    Route::group([
        'auth_group' => 'application Center'
    ], function () {
        Route::get('application', ['uses' => 'Modules\System\Admin\Application@index', 'desc' => 'List'])->name('admin.system.application');
    });

    /**
     * 接口授权
     */
    Route::group([
        'auth_group' => 'Interface authorization'
    ], function () {
        Route::manage(\Modules\System\Admin\Api::class)->only(['index', 'data', 'page', 'save', 'del'])->make();
        Route::post('api/token/{id?}', ['uses' => 'Modules\System\Admin\Api@token', 'desc' => 'Change TOKEN'])->name('admin.system.api.token');
    });
});

