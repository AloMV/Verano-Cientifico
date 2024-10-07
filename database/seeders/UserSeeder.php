<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertar usuarios
        $users = [
            [
                'name' => 'Administrador',
                'email' => 'javi.yomy1928@gmail.com',
                'password' => Hash::make('12345678'),
                'institution_id' => null,
                'email_verified_at' => now(),
                'created_at' => now()
            ],
            [
                'name' => 'Javier Villagran Placencio',
                'email' => 'vpjo200958@upemor.edu.mx',
                'password' => Hash::make('12345678'),
                'institution_id' => 1,
                'email_verified_at' => now(),
                'created_at' => now()
            ],
            [
                'name' => 'Jose Antonio Ortega Martinez',
                'email' => 'villagranjavier19@gmail.com',
                'password' => Hash::make('12345678'),
                'institution_id' => null,
                'email_verified_at' => now(),
                'created_at' => now()
            ],
            [
                'name' => 'Vitervo Lopez Caballero',
                'email' => 'javiervillagranplacencio@gmail.com',
                'password' => Hash::make('12345678'),
                'institution_id' => null,
                'email_verified_at' => now(),
                'created_at' => now()
            ],
            [
                'name' => 'Miguel Angel Roman Chano',
                'email' => 'chano@gmail.com',
                'password' => Hash::make('12345678'),
                'institution_id' => null,
                'email_verified_at' => now(),
                'created_at' => now()
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }

        // Crear roles
        $admin = Role::firstOrCreate(['name' => 'Admin'], ['description' => 'Administrador']);
        $alumno = Role::firstOrCreate(['name' => 'Alumno'], ['description' => 'Alumno']);
        $reviewer = Role::firstOrCreate(['name' => 'Revisor'], ['description' => 'Revisor']);
        $profesor = Role::firstOrCreate(['name' => 'Profesor'], ['description' => 'Profesor']);
        $comite = Role::firstOrCreate(['name' => 'Comite'], ['description' => 'Comite']);

        // Asignar roles a usuarios
        User::find(1)?->assignRole($admin);
        User::find(2)?->assignRole($alumno);
        User::find(3)?->assignRole($reviewer);
        User::find(4)?->assignRole($profesor);
        User::find(5)?->assignRole($comite);
    }
}
