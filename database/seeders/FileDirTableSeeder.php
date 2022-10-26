<?php

namespace Modules\System\Seeders;

use Illuminate\Database\Seeder;

class FileDirTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('file_dir')->insert([
            [
                'name' => 'default',
                'has_type' => 'admin',
            ],
            [
                'name' => 'image',
                'has_type' => 'admin',
            ],
            [
                'name' => 'video',
                'has_type' => 'admin',
            ],
            [
                'name' => 'other',
                'has_type' => 'admin',
            ],
        ]);


    }
}
