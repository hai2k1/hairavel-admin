<?php

namespace Modules\System\Admin;

class Upload extends Common
{
    /**
     * Mandatory file driver
     * @var string
     */
    protected string $driver = '';


    use \Hairavel\Core\Manage\Upload;
}
