<?php

namespace Modules\Tools\Admin;

use Illuminate\Validation\Rule;

class Form extends \Modules\System\Admin\Expend
{

    public string $model = \Hairavel\Core\Model\Form::class;

    protected function table()
    {
        $table = new \Hairavel\Core\UI\Table(new $this->model());
        $table->title('custom form');
        $table->action()->button('Add', 'admin.tools.form.page')->type('dialog');

        $table->filter('name', 'name', function ($query, $value) {
            $query->where('name', 'like', '%' . $value . '%');
        })->text('Please enter the form name to search')->quick();

        $table->column('#', 'form_id')->width(80);
        $table->column('name', 'name');

        $column = $table->column('operation')->width(180);
        $column->link('Designer', 'admin.tools.form.setting', ['id' => 'form_id']);
        $column->link('edit', 'admin.tools.form.page', ['id' => 'form_id'])->type('dialog');
        $column->link('delete', 'admin.tools.form.del', ['id' => 'form_id'])->type('ajax', ['method' => 'post']) ;

        return $table;
    }

    public function form(int $id = 0)
    {
        $form = new \Hairavel\Core\UI\Form(new $this->model());
        $form->dialog(true);


        $form->text('form name', 'name')->verify([
            'required',
        ], [
            'required' => 'Please fill in the form name',
        ]);

        $form->textarea('form description', 'description')->verify([
            'required',
        ], [
            'required' => 'Please fill in the form function description',
        ]);

        $form->radio('form type', 'manage', [
            1 => 'Application Integration',
            0 => 'independent management',
        ])->switch('manage');

        $form->text('menu name', 'menu')->group('manage', 0);
        $form->text('search field', 'search')->group('manage', 0);
        $form->text('list template', 'tpl_list')->placeholder('leave blank to close list')->group('manage', 0);
        $form->text('Detail template', 'tpl_info')->placeholder('Leave blank to close details')->group('manage', 0);
        $form->radio('Foreground submission', 'submit', [
            1 => 'normal',
            0 => 'disable',
        ])->group('manage', 0);
        $form->number('Submission interval', 'interval')->group('manage', 0);
        $form->radio('Content Audit', 'audit', [
            1 => 'required',
            0 => 'No need',
        ])->group('manage', 0);
        return $form;
    }

    public function setting(int $id)
    {
        $model = new $this->model();
        $info = $model->find($id);
        $this->assign('id', $id);
        $this->assign('info', $info);
        return $this->systemView('vendor.duxphp.duxravel-admin.src.Tools.View.Admin.Form.setting');
    }

    public function settingSave(int $id)
    {
        $data = request()->input('data');
        $model = new \Hairavel\Core\Model\Form();
        $model->where('form_id', $id)->update(['data' => $data]);
        return app_success('Save form data successfully');
    }

}
