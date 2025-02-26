<?php

namespace Database\Seeders;

use App\Models\MenuMaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // MenuMaster::updateOrCreate(['menu_name'=>'Main Menu']);
        // MenuMaster::updateOrCreate(['menu_name'=>'Left Menu']);
        // MenuMaster::updateOrCreate(['menu_name'=>'Right Menu']);

        $menu = [
            ['id' => 1,
            'menu_name' =>'Main Menu',
           ],
           ['id' => 2,
            'menu_name' =>'Left Menu',
           ],
           ['id' => 3,
            'menu_name' =>'Right Menu',
           ],
        
        ];

        MenuMaster::insert($menu);
    }
}
