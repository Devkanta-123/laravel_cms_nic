<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {


            $this->call(RoleSeeder::class);
            $this->call(UserSeeder::class);
            $this->call(ThemeMasterSeeder::class);
            $this->call(ThemeSeeder::class);
            $this->call(PageSectionMasterSeeder::class);
            $this->call(CarouselTableSeeder::class);
            $this->call(MenuMasterSeeder::class);
            $this->call(PageSectionSeeder::class);
            $this->call(MenuSeeder::class);
            $this->call(ParagraphSeeder::class);
            $this->call(WebsiteSeeder::class);
            $this->call(FooterSeeder::class);


    }
}
