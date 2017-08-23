<?php

namespace InetStudio\Products\Commands;

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'inetstudio:products:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup products package';

    /**
     * Commands to call with their description.
     *
     * @var array
     */
    protected $calls = [
        [
            'description' => 'Publish migrations',
            'command' => 'vendor:publish',
            'params' => [
                '--provider' => 'InetStudio\Products\ProductsServiceProvider',
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
            'command' => 'inetstudio:products:folders',
            'params' => [],
        ],
        [
            'description' => 'Publish public',
            'command' => 'vendor:publish',
            'params' => [
                '--provider' => 'InetStudio\Products\ProductsServiceProvider',
                '--tag' => 'public',
                '--force' => true,
            ],
        ],
        [
            'description' => 'Publish config',
            'command' => 'vendor:publish',
            'params' => [
                '--provider' => 'InetStudio\Products\ProductsServiceProvider',
                '--tag' => 'config',
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
        foreach ($this->calls as $info) {
            $this->line(PHP_EOL.$info['description']);
            $this->call($info['command'], $info['params']);
        }
    }
}
