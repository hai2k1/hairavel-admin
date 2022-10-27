<?php

namespace Modules\System\Admin;

use Illuminate\Support\Facades\DB;
use Hairavel\Core\UI\Table;
use Hairavel\Core\UI\Widget;

class VisitorApi extends \Modules\System\Admin\Expend
{
    public string $model = \Hairavel\Core\Model\VisitorApi::class;

    protected function table(): Table
    {
        $table = new Table(new $this->model());
        $table->title('Interface Statistics');
        $table->model()->select(DB::raw('name, SUM(pv) as `pv`, SUM(uv) as `uv`, MAX(min_time) as `min`, MAX(max_time) as `max`, `desc`, `method`'));
        $table->model()->groupBy('name', 'desc', 'method');
        $table->filter('time', 'day', function ($query, $value) {
            $startTime = strtotime("-{$value} day");
            $query->where('desc', '>=', date('Y-m-d', $startTime));
        });

        $table->filter('interface name', 'desc', function ($query, $value) {
            $query->where('desc', $value);
        })->text('Please enter the interface name')->quick();


        $table->filter('start date', 'start', function ($query, $value) {
            $query->where('created_at', '>=', $value);
        })->date();
        $table->filter('end date', 'stop', function ($query, $value) {
            $query->where('updated_at', '<=', $value);
        })->date();

        $table->map([
            'method'
        ]);

        $table->column('interface', 'desc')->desc('name');
        $table->column('visits', 'pv')->sorter();
        $table->column('visitor', 'uv')->sorter();
        $table->column('Maximum response', 'max', function ($value) {
            return $value .'s';
        })->sorter();
        $table->column('minimum response', 'min', function ($value) {
            return $value .'s';
        })->sorter();

        return $table;
    }

    public function loadTotal()
    {
        $day = request()->get('type', 7);
        $startTime = strtotime("-{$day} day");
        $apiList = app(\Hairavel\Core\Model\VisitorApi::class)
            ->select(DB::raw('name, SUM(pv) as `value`, SUM(uv) as `uv`, `desc`'))
            ->where('date', '>=', date('Y-m-d', $startTime))
            ->groupBy('name', 'desc')
            ->orderBy('value', 'desc')
            ->limit(10)
            ->get();

        $apiListSum = $apiList->sum('value');
        $apiList->each(function ($item) use ($apiListSum) {
            $item['rate'] = $apiListSum ? round($item['value'] / $apiListSum * 100) : 0;
            return $item;
        });
        if (!$apiList->count()) {
            return app_error('暂未找到数据');
        }
        $this->assign('apiList', $apiList);
        return $this->dialogView('vendor/haibase/hairavel-admin/src/System/View/Admin/VisitorApi/loadTotal');
    }

    public function loadDelay()
    {
        $day = request()->get('type', 7);
        $startTime = strtotime("-{$day} day");
        $apiList = app(\Hairavel\Core\Model\VisitorApi::class)
            ->select(DB::raw('name, MAX(max_time) as `value`, `desc`'))
            ->where('date', '>=', date('Y-m-d', $startTime))
            ->groupBy('name', 'desc')
            ->orderBy('value', 'desc')
            ->limit(10)
            ->get();

        $apiListSum = $apiList->sum('value');
        $apiList->each(function ($item) use ($apiListSum) {
            $item['rate'] = $apiListSum ? round($item['value'] / $apiListSum * 100) : 0;
            return $item;
        });
        if (!$apiList->count()) {
            return app_error('No data found yet');
        }
        $this->assign('apiList', $apiList);
        return $this->dialogView('vendor/haibase/hairavel-admin/src/System/View/Admin/VisitorApi/loadDelay');
    }

}
