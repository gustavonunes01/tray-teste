<?php

namespace Database\Seeders;

use App\Enums\ProfileTypesEnum;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            ParamsGeneralSeeder::class,
            ProfileTypeSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'superadmin',
            'email' => 'super@admin.teste',
            'password' => Hash::make('superadmin123'),
            'profile_id' => ProfileTypesEnum::ADMIN,
        ]);
    }
}
