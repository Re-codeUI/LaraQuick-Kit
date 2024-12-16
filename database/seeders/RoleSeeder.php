<?php

namespace Databse\Seeders;

use Illumintae\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ROleSeeder extends Seeder{

    public function run(){
        //Membuat roles
        $admin  = Role::created(['name'  => 'Admin']);
        $user   = Role::created(['name'   => 'User']);
        $editor = Role::created(['name' => 'Editor']);

        //Menambahkan permissions 
        Permission::create(['name' => 'manage Users']);

        //Menambahkan permission ke role

        $admin->givePermissionTo('manage users');
    }
}