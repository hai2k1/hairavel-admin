<?php

namespace Modules\System\Admin;

use Illuminate\Support\Facades\DB;
use Hairavel\Core\UI\Table;
use Hairavel\Core\UI\Widget;

class Task extends \Modules\System\Admin\Expend
{

    protected function table(): Table
    {
        $class = config('queue.default');
        if ($class <> 'redis' && $class <> 'database') {
            app_error('Queue type not supported');
        }
        $type = request()->get('type');
        $statsDown = 0;
        $statsUp = 0;
        $statsAll = 0;
        if ($type <> 3) {
            if ($class == 'database') {
                $data = new \Hairavel\Core\Model\Jobs();
                $table = new Table($data);
            }
            if ($class == 'redis') {
                $connection = config('queue.connections.redis.connection');
                $default = config('queue.connections.redis.queue');
                $data = collect(\Queue::getRedis()->connection($connection)->zrange('queues:' . $default . ':delayed', 0, -1))->map(function ($value) use (&$statsDown, &$statsUp) {
                    $payload = json_decode($value, true);
                    if ($payload['attempts']) {
                        $statsDown++;
                    } else {
                        $statsUp++;
                    }
                    return [
                        'payload' => $payload,
                        'attempts' => $payload['attempts']
                    ];
                });
                $statsAll = $data->count();

                if ($type == 1) {
                    $data = $data->filter(function ($value, $key) {
                        return $value['attempts'] > 0;
                    });
                }
                if ($type == 2) {
                    $data = $data->filter(function ($value, $key) {
                        return $value['attempts'] == 0;
                    });
                }
                $table = new Table($data);
            }

            $table->title('Task queue');
            $table->column('task | parameters', 'payload', function ($value) {
                $data = (array)unserialize($value['data']['command']);
                return $data["\x00*\x00class"] . '@' . $data["\x00*\x00method"];
            })->desc('payload', function ($value) {
                $data = (array)unserialize($value['data']['command']);
                $params = $data["\x00*\x00params"];
                return $params ? json_encode($params) : '-';
            });
            $table->column('execution times', 'attempts');
            $table->column('status', 'attempts', function ($value) {
                return $value ? 1 : 0;
            })->status([
                1 => 'Executing',
                0 => 'to be executed'
            ], [
                1 => 'green',
                0 => 'red'
            ]);
            $table->header(Widget::StatsCard(function (Widget\StatsCard $card) use ($statsAll, $statsDown, $statsUp) {
                $card->item('All queues', $statsAll);
                $card->item('Processing', $statsDown);
                $card->item('to be processed', $statsUp);
                $statsFail = \Hairavel\Core\Model\JobsFailed::count();
                $card->item('Failure queue', $statsFail);
            }));

        } else {
            $data = new \Hairavel\Core\Model\JobsFailed();
            $table = new Table($data);

            $table->title('Task queue');
            $table->column('task/parameters', 'payload', function ($value) {
                $data = (array)unserialize($value['data']['command']);
                return $data["\x00*\x00class"] . '@' . $data["\x00*\x00method"];
            })->desc('payload', function ($value) {
                $data = (array)unserialize($value['data']['command']);
                $params = $data["\x00*\x00params"];
                return $params ? json_encode($params) : '-';
            });
            $table->column('link/queue', 'connection')->desc('queue');
            $table->column('failed time', 'failed_at');


            $table->title('Failed task');
        }

        $table->filterType('all');
        $table->filterType('Processing');
        $table->filterType('to be processed');
        $table->filterType('failed');

        return $table;
    }

}
