<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            ['id' => 1,
            'name' =>'Lucy',
            'email' =>'lucy.manisha@nic.in',
            'password' => Hash::make('lucy.manisha@nic.in'),
            'role_id' => 1
           ],

           ['id' => 2,
            'name' =>'Admin',
            'email' =>'admin@mail.com',
            'password' => Hash::make('admin@mail.com'),
            'role_id' => 2
           ],
           
        
        ];

        User::insert($user);
        DB::statement("SELECT setval('users_id_seq', (SELECT MAX(id) FROM users))");
    }
}
