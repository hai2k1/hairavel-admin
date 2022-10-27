<?php

namespace Modules\System\Admin;

use Hairavel\Core\UI\Node;
use Hairavel\Core\UI\Widget\Link;
use Hairavel\Core\UI\Widget\TreeList;
use Illuminate\Support\Facades\DB;
use Hairavel\Core\UI\Table;
use Hairavel\Core\UI\Widget;

class Files extends \Modules\System\Admin\Expend
{
    public string $model = \Hairavel\Core\Model\File::class;

    protected function table(): Table
    {
        $table = new Table(new $this->model());

        $table->title('File management');

        $table->filter('File name', 'title', function ($query, $value) {
            $query->where('title', 'like', '%' . $value . '%');
        })->text('Please enter file name to search')->quick();

        $table->column('Document', 'title')->image('url', function ($value, $items) {
            if (!in_array($items->ext, ['jpg', 'png', 'bmp', 'jpeg', 'gif'])) {
                return route('service.image.placeholder', ['w' => 128, 'h' => 128, 't' => 'No preview yet']);
            }
            return $value;
        })->desc('size', fn($value)=> app_filesize($value));

        $table->column('Association type / drive', 'has_type')->desc('driver');
        $table->column('Created at', 'created_at', fn($value) => $value->format('Y-m-d H:i:s'));


        $column = $table->column('Operate')->width(100);
        $column->link('deleted', 'admin.system.files.del', ['id' => 'file_id'])->type('ajax', ['method' => 'post']);


        $table->filter('Sort', 'dir_id');
        $table->side(function () {
            return (new Node())->div(function ($node) {
                $node->div(
                    (new TreeList(request()->get('dir_id'), 'dir_id'))
                        ->search(true)
                        ->url(route('admin.system.filesDir.ajax'))
                        ->label('{{item.title}} ({{item.rawData.has_type}})')
                        ->render()
                )->class('p-2 h-10 flex-grow');
            })->class('h-screen flex flex-col')->render();
        }, 'left', false, '220px');

        return $table;
    }


}
