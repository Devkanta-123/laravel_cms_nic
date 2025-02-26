<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = [
            ['id' => 1,
            'menu_name' =>'Home',
            'menu_master' =>'1',
            'order' =>'1',
            'parent' =>'0',
            'status' =>'1',
           ],
           ['id' => 2,
            'menu_name' =>'About Us',
            'menu_master' =>'1',
            'order' =>'2',
            'parent' =>'0',
            'status' =>'1',
           ],
           ['id' => 3,
            'menu_name' =>'Contact Us',
            'menu_master' =>'1',
            'order' =>'3',
            'parent' =>'0',
            'status' =>'1',
           ],
        
        ];

        Menu::insert($menu);

        DB::statement('SELECT setval(\'menu_id_seq\', (SELECT MAX(id) FROM menu))');
    }
}
