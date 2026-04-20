<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

    $user = User::create([
        'name' => 'Simple User',
        'email' => 'user@example.com',
        'password' => Hash::make('password123'),
    ]);

    // $user->assignRole($adminRole);
    $user->assignRole($userRole);
    }
}
