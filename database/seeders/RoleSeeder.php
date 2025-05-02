<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // check if the roles table is empty
    if (Role::count() > 0) {
      return;
    }
    // Create 4 roles, admin, helper, auditor, and regular
    Role::create([
      'name' => 'Administrador',
      'description' => 'Tiene acceso total al sistema y es el responsable principal de su gestión y supervisión',
      'slug' => 'admin',
    ]);

    Role::create([
      'name' => 'Ayudante',
      'description' => 'Apoya al administrador en tareas operativas específicas, con acceso limitado a funciones de entrada y edición de datos.',
      'slug' => 'helper',
    ]);

    Role::create([
      'name' => 'Auditor',
      'description' => 'Se encarga de validar la integridad y consistencia de la información, asegurando transparencia y cumplimiento de las políticas municipales.',
      'slug' => 'auditor',
    ]);

    Role::create([
      'name' => 'Regular',
      'description' => 'Rol asignado a familiares, amigos o personal autorizado con acceso restringido para visualizar información relevante.',
      'slug' => 'regular',
    ]);
  }
}
