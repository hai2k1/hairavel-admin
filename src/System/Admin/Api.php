<?php

namespace Modules\System\Admin;

use Duxravel\Core\UI\Form;
use Duxravel\Core\UI\Table;

class Api extends \Modules\System\Admin\Expend
{

    public string $model = \Duxravel\Core\Model\Api::class;

    protected function table(): Table
    {
        $table = new Table(new $this->model());
        $table->title('Interface authorization');

        $table->action()->button('Add', 'admin.system.api.page')->type('dialog');

        $table->column('Name', 'name');
        $table->column('SECRET_ID', 'secret_id');
        $table->column('SECRET_KEY', 'secret_key')->hidden();

        $table->column('Status', 'status')->status([
            1 => 'Normal',
            0 => 'Disabled'
        ], [
            1 => 'green',
            0 => 'red'
        ]);

        $column = $table->column('operate')->width(220);
        $column->link('reset token', 'admin.system.api.token', ['id' => 'api_id'])->type('ajax', ['method' => 'post']);
        $column->link('Edit', 'admin.system.api.page', ['id' => 'api_id'])->type('dialog');
        $column->link('Deleted', 'admin.system.api.del', ['id' => 'api_id'])->type('ajax');

        $table->filter('Search', 'name', function ($query, $value) {
            $query->where('name', 'like', '%' . $value . '%');
        })->text('Please enter a description to search')->quick();

        return $table;
    }

    public function form(int $id = 0): Form
    {
        $form = new Form(new $this->model());
        $form->dialog(true);

        $form->text('description', 'name')->verify([
            'required',
        ], [
            'required' => 'Please enter an interface description',
        ]);

        $form->radio('state', 'status', [
            1 => 'enable',
            0 => 'disabled',
        ]);

        $form->before(function ($data, $type, $model) {
            if ($type !== 'add') {
                return false;
            }
            $model->secret_id = random_int(10000000, 99999999);
            $model->secret_key = \Str::random(32);
        });

        return $form;
    }

    public function token($id = 0)
    {
        $info = $this->model::find($id);
        $info->secret_key = \Str::random(32);
        $info->save();
        return app_success('Reset TOKEN successfully');
    }
}
