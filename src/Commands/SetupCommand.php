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
        'vandor:publish --provider="InetStudio\Tags\TagsServiceProvider" --tag="migrations" --force' => 'Publish migrations',
        'migrate' => 'Migration',
        'optimize' => 'Optimize',
        'inetstudio:tags:folders' => 'Create folders',
        'vandor:publish --provider="InetStudio\Tags\TagsServiceProvider" --tag="public" --force' => 'Publish public',
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        foreach ($this->calls as $command => $info) {
            $this->line(PHP_EOL.$info);
            $this->call($command);
        }
    }
}
