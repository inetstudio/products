<?php

namespace InetStudio\ProductsPackage\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;

class SetupCommand extends BaseSetupCommand
{
    protected $name = 'inetstudio:products-package:setup';

    protected $description = 'Setup products package';

    protected function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => 'Products setup',
                'command' => 'inetstudio:products-package:products:setup',
            ],
            [
                'type' => 'artisan',
                'description' => 'Products links setup',
                'command' => 'inetstudio:products-package:links:setup',
            ],
            [
                'type' => 'artisan',
                'description' => 'Products items setup',
                'command' => 'inetstudio:products-package:products-items:setup',
            ],
        ];
    }
}
