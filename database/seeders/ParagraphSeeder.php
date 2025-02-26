<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Paragraph;
use Illuminate\Support\Facades\DB;

class ParagraphSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $page = [
            ['id' =>1,
            'title' => 'About Us',
            'description' =>'This is the about us page',
            'menu_id' => '2',
            'page_section_id' =>'7',
            'meta_title' => 'About Us',
            'meta_description' => 'Meta About us description',
            'meta_keywords' => 'about us',
            'status' => 1
            ]
        ];
        Paragraph::insert($page);

        DB::statement('SELECT setval(\'paragraph_id_seq\', (SELECT MAX(id) FROM paragraph))');
    }
}
