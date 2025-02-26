<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Carousel;
use Illuminate\Support\Facades\DB;

class CarouselTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carousel = [
            [
                'id' => 1,
                'type' => 'Slider',
                'image' => 'slides/1.jpg',
                'link' => 'slides/1.jpg',
                'title' => 'Main Carousel',
                'alt' => 'Alt Text',
                'order' => 1,
                'status' => 1,
            ],
            [
                'id' => 2,
                'type' => 'Slider',
                'image' => 'slides/2.jpg',
                'link' => 'slides/2.jpg',
                'title' => 'Second Carousel',
                'alt' => 'Alt Text',
                'order' => 2,
                'status' => 1,
            ]
        ];

        Carousel::insert($carousel);
        DB::statement('SELECT setval(\'carousel_id_seq\', (SELECT MAX(id) FROM carousel))');


    }
}
