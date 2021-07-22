<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(User $user)
    {
        $user->create([
            'name' => 'Admin',
            'username' => 'admin',
            'password' => 'admin',
            'role' => 'admin'
        ]);
    }
}
