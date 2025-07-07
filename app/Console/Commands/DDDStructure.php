<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeDDDContext extends Command
{
    protected $signature = 'make:ddd-context {context : Bounded context name}';
    protected $description = 'Create DDD structure for a bounded context';

    public function handle()
    {
        $context = $this->argument('context');
        $basePath = app_path("Domain/{$context}");

        $dirs = [
            'Entities',
            'ValueObjects',
            'Repositories',
            'Services',
            'Events',
            'Listeners',
            'Exceptions',
            'Rules',
            'DTOs',
            'Queries',
            'Commands'
        ];

        foreach ($dirs as $dir) {
            File::ensureDirectoryExists("{$basePath}/{$dir}");
            $this->info("Created: {$basePath}/{$dir}");
        }

        // Agregar autoload en composer.json
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        $composer['autoload']['psr-4']["App\\Domain\\{$context}\\"] = "app/Domain/{$context}";
        file_put_contents(base_path('composer.json'), json_encode($composer, JSON_PRETTY_PRINT));

        $this->info("DDD context {$context} created. Run: composer dump-autoload");
        return Command::SUCCESS;
    }
}