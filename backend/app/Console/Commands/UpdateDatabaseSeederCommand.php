<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateDatabaseSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:database-seeder {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update the DatabaseSeeder.php with any new seeders created.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = trim($this->argument('name'));
        $databaseSeederPath = database_path('seeders/DatabaseSeeder.php');

        if (! File::exists($databaseSeederPath)) {
            return $this->error('DatabaseSeeder.php not found.');
        }

        $contents = File::get($databaseSeederPath);

        if (str_contains($contents, "{$name}::class")) {
            return $this->info("{$name} is already included in DatabaseSeeder.");
        }

        // Find insertion point (inside run() method)
        $pattern = '/public function run\(\): void\s*\{([\s\S]*?)\}/';

        $replacement = function ($matches) use ($name) {
            // Add proper indentation
            $insertion = "  \$this->call({$name}::class);";

            return "public function run(): void {\n{$matches[1]}{$insertion}\n    }";
        };

        $updated = preg_replace_callback($pattern, $replacement, $contents, 1);

        if (! $updated) {
            return $this->error('Could not modify DatabaseSeeder.php.');
        }

        File::put($databaseSeederPath, $updated);

        $this->info("{$name} has been appended to DatabaseSeeder.");
    }
}
