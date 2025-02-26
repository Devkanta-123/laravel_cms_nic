<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PageSection;
use Illuminate\Support\Facades\DB;

class PageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $page = [
            ['id' =>1,
            'menu_id' => '1',
            'page_section_name' =>'Carousel',
            'page_section_id' =>'1',
            ],
            ['id' =>2,
            'menu_id' => '1',
            'page_section_name' =>'Latest News',
            'page_section_id' =>'2',
            ],
            ['id' =>3,
            'menu_id' => '1',
            'page_section_name' =>'Home Page Paragraph',  
            'page_section_id' =>'3',
            ],

            ['id' =>4,
            'menu_id' => '2',
            'page_section_name' =>'Paragraph',
            'page_section_id' =>'7',
            ]

            
        ];
        PageSection::insert($page);

        DB::statement('SELECT setval(\'page_section_id_seq\', (SELECT MAX(id) FROM page_section))');
    }
}
