<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@bookstore.com',
        ]);

        if (app()->isLocal()) {
            $this->call([
                BookSeeder::class
            ]);
        }
    }
}
