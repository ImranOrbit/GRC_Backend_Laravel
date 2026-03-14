<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Check if user already exists
        $user = User::where('email', 'admin@site.com')->first();
        
        if (!$user) {
            // Create new admin user
            User::create([
                'name' => 'Admin',
                'email' => 'admin@site.com',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Admin user created successfully!');
        } else {
            // Update existing user's password
            $user->password = Hash::make('123456');
            $user->save();
            
            $this->command->info('Admin user password updated successfully!');
        }
    }
}