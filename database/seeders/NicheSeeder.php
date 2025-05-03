<?php

namespace Database\Seeders;

use App\Models\ContractState;
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

        // Contract states
        ContractState::insert([
            [
                'name' => 'Pendiente',
                'slug' => 'pendiente',
                'description' => 'Pendiente de respuesta',
            ],
            [
                'name' => 'Aprobado',
                'slug' => 'aprobado',
                'description' => 'Aprobado, esperando pago',
            ],
            [
                'name' => 'Vigente',
                'slug' => 'vigente',
                'description' => 'Vigente, pago realizado',
            ],
            [
                'name' => 'Vencido',
                'slug' => 'vencido',
                'description' => 'Vencido',
            ],
            [
                'name' => 'Rechazado',
                'slug' => 'rechazado',
                'description' => 'Rechazado',
            ],
        ]);
    }
}
