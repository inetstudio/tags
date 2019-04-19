<?php

namespace InetStudio\TagsPackage\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;

/**
 * Class SetupCommand.
 */
class SetupCommand extends BaseSetupCommand
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:tags-package:setup';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Setup tags package';

    /**
     * Инициализация команд.
     */
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
