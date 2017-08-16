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
    protected $calls = [
        'vendor:publish' => [
            'description' => 'Publish migrations',
            'params' => [
                '--provider' => 'InetStudio\Tags\TagsServiceProvider',
                '--tag' => 'migrations',
            ],
        ],
        'migrate' => [
            'description' => 'Migration',
            'params' => [],
        ],
        'optimize' => [
            'description' => 'Optimize',
            'params' => [],
        ],
        'inetstudio:tags:folders' => [
            'description' => 'Create folders',
            'params' => [],
        ],
        'vendor:publish' => [
            'description' => 'Publish public',
            'params' => [
                '--provider' => 'InetStudio\Tags\TagsServiceProvider',
                '--tag' => 'public',
                '--force' => true,
            ],
        ],
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        foreach ($this->calls as $command => $info) {
            $this->line(PHP_EOL.$info['description']);
            $this->call($command, $info['params']);
        }
    }
}
