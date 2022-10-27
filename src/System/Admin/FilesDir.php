<?php

namespace Modules\System\Admin;

use Hairavel\Core\Model\FileDir;
use Hairavel\Core\UI\Node;
use Hairavel\Core\UI\Widget\Link;
use Hairavel\Core\UI\Widget\TreeList;
use Illuminate\Support\Facades\DB;
use Hairavel\Core\UI\Table;
use Hairavel\Core\UI\Widget;

class FilesDir extends \Modules\System\Admin\Expend
{
    public string $model = FileDir::class;

    protected function table(): Table
    {
        $table = new Table(new $this->model());
        $table->title('目录管理');
        $table->map([
            'key' => 'dir_id',
            'title' => 'name',
            'has_type',
        ]);
        // Generate Table Make
        return $table;
    }


}
