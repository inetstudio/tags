<?php

namespace InetStudio\Tags\Commands;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'inetstudio:tags:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup tags package';

    /**
     * Commands to call with their description.
     *
     * @var array
     */
    protected $calls = [];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
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
    private function initCommands()
    {
        $this->calls = [
            [
                'description' => 'Publish migrations',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'InetStudio\Tags\TagsServiceProvider',
                    '--tag' => 'migrations',
                ],
            ],
            [
                'description' => 'Migration',
                'command' => 'migrate',
                'params' => [],
            ],
            [
                'description' => 'Optimize',
                'command' => 'optimize',
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
                    '--provider' => 'InetStudio\Tags\TagsServiceProvider',
                    '--tag' => 'public',
                    '--force' => true,
                ],
            ],
            [
                'description' => 'Publish config',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'InetStudio\Tags\TagsServiceProvider',
                    '--tag' => 'config',
                ],
            ],
        ];
    }
}
