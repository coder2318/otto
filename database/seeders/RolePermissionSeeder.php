<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    protected array $roles = [
        'super-admin' => [], // Allowed all by default in AppServiceProvider
        'admin' => [
            'debug',
        ],
    ];

    public function run(): void
    {
        $permissions = collect($this->roles)->flatten()->unique()->values();
        $roles = collect($this->roles)->keys();
        $date = now();

        $map = fn (string $permission): array => [
            'name' => $permission,
            'created_at' => $date,
            'updated_at' => $date,
            'guard_name' => 'web',
        ];

        Role::upsert($roles->map($map)->all(), ['name', 'guard_name']);
        Permission::upsert($permissions->map($map)->all(), ['name', 'guard_name']);

        foreach (Role::all() as $role) {
            $role->syncPermissions($this->roles[$role->name]);
        }
    }
}
