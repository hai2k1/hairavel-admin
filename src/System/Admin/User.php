<?php

namespace Modules\System\Admin;

class User extends \Modules\System\Admin\Expend
{
    public string $model = \Modules\System\Model\SystemUser::class;

    use \Hairavel\Core\Manage\User;

}
