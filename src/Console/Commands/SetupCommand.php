<?php

namespace InetStudio\Products\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class SetupCommand
 * @package InetStudio\Products\Console\Commands
 */
class SetupCommand extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:products:setup';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Setup products package';

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
                ],
            ],
        ];
    }
}
