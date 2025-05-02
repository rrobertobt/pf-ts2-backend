<?php

namespace Database\Seeders;

use App\Models\NicheState;
use App\Models\NichesType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NicheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert the states
        NicheState::insert([
            [
                'name' => 'Ocupado',
                'slug' => 'ocupado',
                'description' => 'Ocupado',
            ],
            [
                'name' => 'Disponible',
                'slug' => 'disponible',
                'description' => 'Disponible',
            ],
            [
                'name' => 'En proceso de exhumación',
                'slug' => 'exhumacion',
                'description' => 'En proceso de exhumación',
            ],
        ]);

        // Insert the types
        NichesType::insert([
            [
                'name' => 'Adulto',
                'slug' => 'adulto',
                'description' => 'Nicho para adulto',
            ],
            [
                'name' => 'Infante',
                'slug' => 'infante',
                'description' => 'Nicho para infante',
            ]
        ]);
    }
}
