<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Seeder untuk hak akses berbasis role dan permission.
 */
class PermissionRoleSeeder extends Seeder
{
    /**
     * Jalankan seeder RBAC.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $entities = [
            'attractions',
            'operating_hours',
            'ticket_types',
            'events',
            'announcements',
            'facilities',
            'merchants',
            'inquiries',
            'closures',
            'settings',
        ];

        $abilities = ['viewAny', 'view', 'create', 'update', 'delete'];

        $allPermissions = [];
        foreach ($entities as $entity) {
            foreach ($abilities as $ability) {
                $name = $entity.'.'.$ability;
                $permission = Permission::firstOrCreate(['name' => $name]);
                $allPermissions[$name] = $permission;
            }
        }

        $roles = [
            'super_admin',
            'admin',
            'editor',
            'officer',
        ];

        $roleInstances = [];
        foreach ($roles as $roleName) {
            $roleInstances[$roleName] = Role::firstOrCreate(['name' => $roleName]);
        }

        $roleInstances['super_admin']->syncPermissions($allPermissions);

        $adminPermissions = collect($allPermissions)
            ->except(['settings.delete'])
            ->values();
        $roleInstances['admin']->syncPermissions($adminPermissions);

        $editorEntities = [
            'attractions',
            'operating_hours',
            'events',
            'announcements',
            'facilities',
            'merchants',
            'closures',
        ];

        $editorPermissions = collect($editorEntities)
            ->flatMap(function (string $entity) use ($abilities, $allPermissions) {
                return collect($abilities)->map(fn ($ability) => $allPermissions[$entity.'.'.$ability]);
            })
            ->merge([
                $allPermissions['ticket_types.viewAny'],
                $allPermissions['ticket_types.view'],
                $allPermissions['ticket_types.create'],
                $allPermissions['ticket_types.update'],
                $allPermissions['inquiries.viewAny'],
                $allPermissions['inquiries.view'],
            ])
            ->values();
        $roleInstances['editor']->syncPermissions($editorPermissions);

        $officerPermissions = collect($entities)
            ->flatMap(function (string $entity) use ($allPermissions) {
                return [
                    $allPermissions[$entity.'.viewAny'],
                    $allPermissions[$entity.'.view'],
                ];
            })
            ->merge([
                $allPermissions['inquiries.update'],
            ])
            ->values();
        $roleInstances['officer']->syncPermissions($officerPermissions);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@wadukmanduk.local'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
            ]
        );

        if (! $adminUser->hasRole('super_admin')) {
            $adminUser->assignRole('super_admin');
        }
    }
}
