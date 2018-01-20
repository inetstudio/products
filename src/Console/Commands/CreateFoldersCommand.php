<?php

namespace InetStudio\Products\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class CreateFoldersCommand
 * @package InetStudio\Products\Console\Commands
 */
class CreateFoldersCommand extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:products:folders';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Create package folders';

    /**
     * Запуск команды.
     *
     * @return void
     */
    public function handle(): void
    {
        if (config('filesystems.disks.products')) {
            $path = config('filesystems.disks.products.root');

            if (! is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }
    }
}
