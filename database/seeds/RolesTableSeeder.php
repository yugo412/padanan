<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('permission.role.roles') as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
