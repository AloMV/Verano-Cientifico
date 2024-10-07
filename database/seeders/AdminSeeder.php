<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cobertura de visibilidad
        $admin = Role::where('name', 'Admin')->first();
        $alumno = Role::where('name', 'Alumno')->first();
        $reviewer = Role::where('name', 'Revisor')->first();
        $profesor = Role::where('name', 'Profesor')->first();
        $comite = Role::where('name', 'Comite')->first();

        $admin->givePermissionTo(Permission::where('module_key', 'seg')->get());
        $admin->givePermissionTo(Permission::where('module_key', 'cat')->get());
        $admin->givePermissionTo(Permission::where('module_key', 'priv')->get());
        $admin->givePermissionTo(Permission::where('module_key', 'div')->get());
        
        // demas usuarios
        $profesor->givePermissionTo('article.index', 'article.store', 'article.update');
        $reviewer->givePermissionTo('article.index', 'articleReview.index', 'articleReview.update');
        $alumno->givePermissionTo('article.index', 'article.update', 'articleReview.index', 'articleReview.store', 'articleReview.update', 'articleReview.delete');
        $comite->givePermissionTo('article.index', 'article.update', 'articleReview.index', 'articleReview.store', 'articleReview.update', 'articleReview.delete');


    }
}
