<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sharing = Permission::create(['name' => 'view.sharing']);

        $role = Role::create(['name' => 'share']);

        $role->givePermissionTo($sharing);

        $userShare = User::find(5);

        $userShare->assignRole('share');
    }
}
