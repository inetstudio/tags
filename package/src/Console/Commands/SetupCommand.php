<?php

namespace InetStudio\TagsPackage\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;

class SetupCommand extends BaseSetupCommand
{
    protected $name = 'inetstudio:tags-package:setup';

    protected $description = 'Setup tags package';

    protected function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => 'Tags setup',
                'command' => 'inetstudio:tags-package:tags:setup',
            ],
        ];
    }
}
