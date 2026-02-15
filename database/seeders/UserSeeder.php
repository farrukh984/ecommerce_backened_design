<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add specific Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'farrokh984@gmail.com', // <--- Yahan apna Email likhein
            'password' => Hash::make('Farrokh@123_Password'), // <--- Yahan apna Farrokh@123_Password  Password likhein
            'role' => 'admin',
        ]);

        // Add a User if needed
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
        
        echo "Users created successfully: \n";
        echo "Admin: admin@example.com / password\n";
        echo "User: user@example.com / password\n";
    }
}
