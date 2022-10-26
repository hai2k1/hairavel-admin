<?php

namespace Modules\System\Admin;

class Application extends \Modules\System\Admin\Common
{

    public function index()
    {
        app(\Duxravel\Core\Util\Menu::class)->getManage('admin');
        $data = app(\Duxravel\Core\Util\Menu::class)->getApps();


        $typeArr = ['business', 'market', 'tools'];
        $typeData = [];
        foreach ($data as $vo) {
            if ($vo['name'] == 'application Center') continue;
            if(in_array($vo['type'], $typeArr)) {
                $name = $vo['type'];
            }else {
                $name = 'other';
            }
            $typeData[$name][] = $vo;
        }


        $typeList = [
            'business' => [
                'name' => 'Business Applications',
                'desc' => 'System business related modules',
                'color' => 'blue',
                'data' => $typeData['business'],
            ],
            'tools' => [
                'name' => 'tool application',
                'desc' => 'Common auxiliary tools of the system',
                'color' => 'green',
                'data' => $typeData['tools'],
            ],
            'other' => [
                'name' => 'other apps',
                'desc' => 'The system is used to define classification applications',
                'color' => 'yellow',
                'data' => $typeData['other'],
            ],
        ];
        $this->assign('typeList', $typeList);


        $formList = \Duxravel\Core\Model\Form::where('manage' , 0)->get();
        $this->assign('formList', $formList);
        return $this->systemView('vendor/duxphp/duxravel-admin/src/System/View/Admin/Application/index');
    }

}
