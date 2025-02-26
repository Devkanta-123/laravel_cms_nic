<?php

namespace Database\Seeders;

use App\Models\PageSectionMaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSectionMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = [
            ['id' => 1,
            'page_section_name' =>'Carousel',
            'icon' => 'logo/slider.png'
           ],
           ['id' => 2,
            'page_section_name' =>'Latest News',
            'icon' => 'logo/news.png'
           ],
           ['id' => 3,
            'page_section_name' =>'Home Page Paragraph',
             'icon' => 'logo/website.png'
           ],
           ['id' =>4,
           'page_section_name' =>'Cards',
            'icon' => 'logo/description.png'
           ],
           ['id' =>5,
           'page_section_name' =>'Gallery',
            'icon' => 'logo/gallery.png'
           ],
           ['id' =>6,
           'page_section_name' =>'Notifications',
            'icon' => 'logo/notifications.png'
           ],
           ['id' =>7,
           'page_section_name' =>'Paragraph',
            'icon' => 'logo/description.png'
           ]
           ,
           ['id' =>8,
           'page_section_name' =>'Banner',
            'icon' => 'logo/table.png'
           ]
        
        ];

        PageSectionMaster::insert($menu);
    }
}
