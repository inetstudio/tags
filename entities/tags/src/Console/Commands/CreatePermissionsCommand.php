<?php

namespace InetStudio\TagsPackage\Tags\Console\Commands;

use Illuminate\Console\Command;
use InetStudio\ACL\Permissions\Contracts\Services\Back\ItemsServiceContract as PermissionsServiceContract;

class CreatePermissionsCommand extends Command
{
    protected $name = 'inetstudio:tags-package:tags:permissions:seed';

    protected $description = 'Create tags package acl permissions';

    protected array $permissions = [
        'tags-package.tags.create' => [
            'display_name' => 'Создание тегов',
            'description' => '',
        ],
        'tags-package.tags.read' => [
            'display_name' => 'Чтение тегов',
            'description' => '',
        ],
        'tags-package.tags.update' => [
            'display_name' => 'Обновление тегов',
            'description' => '',
        ],
        'tags-package.tags.delete' => [
            'display_name' => 'Удаление тегов',
            'description' => '',
        ],
    ];

    public function __construct(
        protected PermissionsServiceContract $permissionsService
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        foreach ($this->permissions as $name => $permissionData) {
            $permission = $this->permissionsService->getModel()->where([['name', '=', $name]])->first();

            if (! $permission) {
                $this->permissionsService->save(
                    [
                        'name' => $name,
                        'display_name' => $permissionData['display_name'],
                        'description' => $permissionData['description'],
                    ],
                    0
                );
            }
        }
    }
}
