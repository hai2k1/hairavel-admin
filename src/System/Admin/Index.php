<?php

namespace Modules\System\Admin;

use Hairavel\Core\Util\View;

class Index extends Common
{

    use \Hairavel\Core\Manage\Notify;

    public function index()
    {
        return View::manage();
    }

    public function menu()
    {
        $list = app(\Hairavel\Core\Util\Menu::class)->getManage('admin');
        $static = app(\Hairavel\Core\Util\Menu::class)->getStatic('admin');
        $list = array_values($list);
        $apps = app(\Hairavel\Core\Util\Menu::class)->getApps();
        return app_success('ok', [
            'list' => $list,
            'apps' => $apps,
            'static' => $static
        ]);
    }
}
