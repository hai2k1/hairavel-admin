<?php

namespace Modules\System\Admin;

use Duxravel\Core\Events\ManageForm;
use Duxravel\Core\Events\ManageTable;
use Illuminate\Support\Collection;
use \Duxravel\Core\UI\Widget;
use \Duxravel\Core\UI\Form;

class Setting extends \Modules\System\Admin\Expend
{

    public function handle()
    {
        return $this->form()->render();
    }

    public function form(): Form
    {
        //$this->dispatch(new \Duxravel\Core\Jobs\Task(\Modules\System\Service\Menu::class, 'test', [], 20));

        $data = collect(\Dotenv\Dotenv::createArrayBacked(base_path())->load());
        $form = new \Duxravel\Core\UI\Form($data, false);
        $form->title('Cài đặt hệ thống', false);
        $form->action(route('admin.system.setting.save'));
        $form->layout(Widget::alert('Các tùy chọn cài đặt hệ thống thuận tiện cho nhân viên vận hành và bảo trì, và những người không chuyên nghiệp hoặc các tùy chọn không rõ ràng không nên tự ý sửa đổi, nếu không hệ thống có thể bị hỏng', '安全提示', function ($alert) {
            $alert->type('warning');
        }));

        $tabs = $form->tab();
        $tabs->column('Information configuration', function (Form $form) {
            $form->text('System name', 'APP_NAME');
            $form->text('System domain name', 'APP_URL');
            $form->text('System specification', 'APP_DESC');
            $form->text('Contact information', 'APP_CONTACT');
        });

        $tabs->column('Security configuration', function (Form $form) {
            $form->radio('Debug mode', 'APP_DEBUG', [
                true => 'ON',
                false => 'OFF'
            ]);

            $data = \collect(config('logging.channels'))->map(function ($item, $key) {
                return $key;
            })->toArray();
            $form->select('Default log channel', 'LOG_CHANNEL', $data);

            $form->select('Default log level', 'LOG_LEVEL', [
                'emergency' => 'emergency',
                'alert' => 'alert',
                'critical' => 'critical',
                'error' => 'error',
                'warning' => 'warning',
                'notice' => 'notice',
                'info' => 'info',
                'debug' => 'debug',
            ]);
        });
        $tabs->column('Performance configuration', function ($form) {
            $data = \collect(config('broadcasting.connections'))->map(function ($item, $key) {
                return $key;
            })->toArray();
            $form->select('Default broadcast driver', 'BROADCAST_DRIVER', $data);

            $data = \collect(config('cache.stores'))->map(function ($item, $key) {
                return $key;
            })->toArray();
            $form->select('Default cache driver', 'CACHE_DRIVER', $data);

            $data = \collect(config('queue.connections'))->map(function ($item, $key) {
                return $key;
            })->toArray();
            $form->select('Default queue driver', 'QUEUE_CONNECTION', $data);

            $form->select('Default SESSION driver', 'SESSION_DRIVER', [
                'file' => 'file',
                'cookie' => 'cookie',
                'database' => 'database',
                'apc' => 'apc',
                'memcached' => 'memcached',
                'redis' => 'redis',
                'dynamodb' => 'dynamodb',
                'array' => 'array',
            ]);
            $form->text('SESSION Lifecycle', 'SESSION_LIFETIME')->type('number')->afterText('分钟');

        });

        $tabs->column('Image processing', function ($form) {
            $form->radio('Image driven', 'IMAGE_DRIVER', [
                'gd' => 'gd',
                'imagick' => 'imagick',
            ]);
            $form->radio('Thumbnail crop', 'IMAGE_THUMB', [
                '' => 'Off by default',
                'center' => 'center',
                'fixed' => 'fixed',
                'scale' => 'scale',
            ]);
            $form->text('Thumbnail width', 'IMAGE_THUMB_WIDTH')->type('number')->afterText('pixel');
            $form->text('Thumbnail height', 'IMAGE_THUMB_HEIGHT')->type('number')->afterText('pixel');
            $form->select('Watermark location', 'IMAGE_WATER', [
                0 => 'Off by default',
                1 => 'top left',
                2 => 'top center',
                3 => 'top right corner',
                4 => 'left center',
                5 => 'Center',
                6 => 'right center',
                7 => 'lower left corner',
                8 => 'Bottom center',
                9 => 'lower right corner',
            ]);
            $form->text('Watermark Transparency', 'IMAGE_WATER_ALPHA')->type('number')->afterText('%');
            $form->text('watermark path', 'IMAGE_WATER_IMAGE')->beforeText('resources/');
        });

        $tabs->column('Upload Settings', function ($form) {
            $data = \collect(config('filesystems.disks'))->map(function ($item, $key) {
                return $key;
            })->toArray();
            $form->select('Upload driver', 'FILESYSTEM_DRIVER', $data);
        });

        $tabs->column('Seven cattle store', function ($form) {
            $form->text('access key', 'QINIU_AK');
            $form->text('secret key', 'QINIU_SK');
            $form->text('bucket', 'QINIU_BUCKET');
            $form->text('Access domain name', 'QINIU_HOST');
            $form->text('Policy effective time', 'QINIU_EXPIRE');
        });

        $tabs->column('Tencent Cloud Storage', function ($form) {
            $form->text('app id', 'COS_APP_ID');
            $form->text('secret id', 'COS_SECRET_ID');
            $form->text('secret key', 'COS_SECRET_KEY');
            $form->text('Region ID', 'COS_REGION');
            $form->text('bucket', 'COS_BUCKET');
            $form->text('CDN domain name', 'COS_CDN');
            $form->text('Path prefix', 'COS_PATH_PREFIX');
            $form->text('Access timeout', 'COS_TIMEOUT')->beforeText('seconds');
            $form->text('connection timeout', 'COS_CONNECT_TIMEOUT')->beforeText('seconds');
        });

        event(new ManageForm(get_called_class(), $form));

        return $form;
    }

    public function save($id = 0)
    {
        $data = $this->form()->save()->toArray();
        $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';
        $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));
        $useKeys = [];
        $contentArray->transform(function ($item) use ($data, &$useKeys) {
            foreach ($data as $key => $vo) {
                if (str_contains($item, $key . '=')) {
                    $useKeys[] = $key;
                    return $key . '=' . $this->getValue($vo);
                }
            }
            return $item;
        });

        $diffData = [];
        foreach ($data as $key => $vo) {
            if (!in_array($key, $useKeys)) {
                $diffData[$key] = $vo;
            }
        }
        if ($diffData) {
            $contentArray->push('');
            foreach ($diffData as $key => $vo) {
                $contentArray->push($key . '=' . $this->getValue($vo));
            }
        }

        $content = implode("\n", $contentArray->toArray());
        \File::put($envPath, $content);
        return app_success('Save the configuration successfully');
    }

    private function getValue($value)
    {
        if (is_string($value)) {
            $value = str_replace("'", "\\'", $value);
            $value = '"' . $value . '"';
        }
        return $value;
    }
}
