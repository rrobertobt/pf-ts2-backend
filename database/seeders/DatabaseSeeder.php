<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // First execute the RoleSeeder
        $this->call(RoleSeeder::class);

        // Create 4 initial users with role admin, helper, auditor, and regular
        // search each id of the roles in the database based on slug
        $adminRole = Role::where('slug', 'admin')->first();
        $helperRole = Role::where('slug', 'helper')->first();
        $auditorRole = Role::where('slug', 'auditor')->first();
        $regularRole = Role::where('slug', 'regular')->first();

        // Create the users
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@email.com',
            'password' => bcrypt('admin'),
            'role_id' => $adminRole->id,
            'dpi' => '0',
        ]);
        $helper = User::create([
            'first_name' => 'Helper',
            'last_name' => 'User',
            'email' => 'helper@email.com',
            'password' => bcrypt('helper'),
            'role_id' => $helperRole->id,
            'dpi' => '1',
        ]);
        $auditor = User::create([
            'first_name' => 'Auditor',
            'last_name' => 'User',
            'email' => 'auditor@email.com',
            'password' => bcrypt('auditor'),
            'role_id' => $auditorRole->id,
            'dpi' => '2',
        ]);
        $regular = User::create([
            'first_name' => 'Regular',
            'last_name' => 'User',
            'email' => 'regular@email.com',
            'password' => bcrypt('regular'),
            'role_id' => $regularRole->id,
            'dpi' => '3',
        ]);
        
    }
}
