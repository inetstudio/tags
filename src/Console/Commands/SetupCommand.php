<?php

namespace InetStudio\Tags\Console\Commands;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:tags:setup';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Setup tags package';

    /**
     * Список дополнительных команд.
     *
     * @var array
     */
    protected $calls = [];

    /**
     * Запуск команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->initCommands();

        foreach ($this->calls as $info) {
            if (! isset($info['command'])) {
                continue;
            }

            $this->line(PHP_EOL.$info['description']);
            $this->call($info['command'], $info['params']);
        }
    }

    /**
     * Инициализация команд.
     *
     * @return void
     */
    private function initCommands(): void
    {
        $this->calls = [
            [
                'description' => 'Publish migrations',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'InetStudio\Tags\Providers\TagsServiceProvider',
                    '--tag' => 'migrations',
                ],
            ],
            [
                'description' => 'Migration',
                'command' => 'migrate',
                'params' => [],
            ],
            [
                'description' => 'Create folders',
                'command' => 'inetstudio:tags:folders',
                'params' => [],
            ],
            [
                'description' => 'Publish public',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'InetStudio\Tags\Providers\TagsServiceProvider',
                    '--tag' => 'public',
                    '--force' => true,
                ],
            ],
            [
                'description' => 'Publish config',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'InetStudio\Tags\Providers\TagsServiceProvider',
                    '--tag' => 'config',
                ],
            ],
        ];
    }
}
