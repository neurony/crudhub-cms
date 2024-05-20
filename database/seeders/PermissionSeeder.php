<?php

namespace Database\Seeders\CrudhubCms;

use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Permission as PermissionModelContract;

class PermissionSeeder extends Seeder
{
    /**
     * @var array|string[][]
     */
    protected array $permissions = [
        // pages
        [
            'name' => 'pages-list',
            'guard_name' => 'admin'
        ],
        [
            'name' => 'pages-add',
            'guard_name' => 'admin'
        ],
        [
            'name' => 'pages-edit',
            'guard_name' => 'admin'
        ],
        [
            'name' => 'pages-delete',
            'guard_name' => 'admin'
        ],


        // menus
        [
            'name' => 'menus-list',
            'guard_name' => 'admin'
        ],
        [
            'name' => 'menus-add',
            'guard_name' => 'admin'
        ],
        [
            'name' => 'menus-edit',
            'guard_name' => 'admin'
        ],
        [
            'name' => 'menus-delete',
            'guard_name' => 'admin'
        ],


        // blocks
        [
            'name' => 'blocks-list',
            'guard_name' => 'admin'
        ],
        [
            'name' => 'blocks-add',
            'guard_name' => 'admin'
        ],
        [
            'name' => 'blocks-edit',
            'guard_name' => 'admin'
        ],
        [
            'name' => 'blocks-delete',
            'guard_name' => 'admin'
        ],


    ];

    /**
     * @param PermissionModelContract $permissionModel
     * @return void
     */
    public function run(PermissionModelContract $permissionModel)
    {
        foreach ($this->permissions as $data) {
            $permission = $permissionModel->where($data)->first();

            if (!($permission instanceof PermissionModelContract && $permission->exists)) {
                $permissionModel->create($data);
            }
        }
    }
}
