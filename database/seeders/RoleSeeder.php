<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = [
            ['id' => 1,
            'role_name' =>'SuperAdmin',
           ],
           ['id' => 2,
            'role_name' =>'Admin',
           ],
            ['id' => 3,
           'role_name' =>'Publisher',
            ],
        
        ];

        Role::insert($role);
        DB::statement("SELECT setval('role_id_seq', (SELECT MAX(id) FROM role))");

    }
}
