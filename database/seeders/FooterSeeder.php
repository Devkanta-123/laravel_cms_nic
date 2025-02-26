<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Footer;


class FooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $page = [
            ['id' =>1,
            'type' => 'text',
            'content' =>'Website ',
            'link' =>'user',
            'order' => '1',
          
          
            ]
        ];
        Footer::insert($page);

        DB::statement('SELECT setval(\'footer_id_seq\', (SELECT MAX(id) FROM footer))');
    }
}
