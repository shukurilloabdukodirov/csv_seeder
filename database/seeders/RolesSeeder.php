<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminPermission = [
            'create:user',
            'delete:user',
            'manage:user',
            'read:logs',
            'manage:logs',
            'attach:permisson',
            'detach:permisson',
            'manage:permisson',
        ];

        $supervisorPermission = [
            'read:own',
            'update:own',
            'list:own',
            'create:own',
            'monitor:own',
            'manage:profile',
            'manage:vehicle',
            'manage:device',
        ];

        $admin = Role::create(['name' => 'admin']);
        $supervisor = Role::create(['name' => 'supervisor']);

        foreach($adminPermission as $item)
        {
            $admin->givePermissionTo($item);
        }

        
        foreach($supervisorPermission as $item)
        {
            $supervisor->givePermissionTo($item);
        }
    }
}
