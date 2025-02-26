<?php

namespace Database\Seeders;

use App\Models\ThemeMaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemeMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $theme = [
            ['id' => 1,
            'theme_name' =>'Theme 1',
            'path' => 'smb',
            'image' => 'smb.png'
           ],

           ['id' => 2,
           'theme_name' =>'Theme 2',
           'path' => 'theme2',
           'image' => 'smb.png'
          ],
        ];

        ThemeMaster::insert($theme);
        DB::statement('SELECT setval(\'theme_master_id_seq\', (SELECT MAX(id) FROM theme_master))');
    }
}
