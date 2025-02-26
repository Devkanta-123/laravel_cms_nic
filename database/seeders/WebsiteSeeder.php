<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Website;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $page = [
            ['id' =>1,
            'website_name' => 'Website Name',
            'website_department' =>'Website Department',
            'website_short' =>'WB',
            'logo' =>'user.png',
            'title' => '',
            'description' => ''
          
            ]
        ];
        Website::insert($page);

        DB::statement('SELECT setval(\'website_id_seq\', (SELECT MAX(id) FROM website))');
    }
}
