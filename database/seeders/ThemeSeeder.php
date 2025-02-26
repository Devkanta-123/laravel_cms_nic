<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $theme = [
            ['id' => 1,
            'theme' =>1,
           ],
          
        ];

        Theme::insert($theme);
        DB::statement('SELECT setval(\'theme_id_seq\', (SELECT MAX(id) FROM theme))');
    }
}
