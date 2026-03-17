<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['username' => 'admin', 'email' => 'info@marnix-ruys.com'],
            ['name' => 'Admin', 'password' => 'Pr@x!-786']
        );

        $user->syncRoles(['Super Admin']);
    }
}
