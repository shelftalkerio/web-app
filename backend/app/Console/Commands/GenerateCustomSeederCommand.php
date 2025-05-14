<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class GenerateCustomSeederCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:custom-seeder {name} {--model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new seeder for a model with custom stub replacements';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Seeder';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        // Ensure the path is correct
        return $this->resolveStubPath('/stubs/seeder.stub');
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {

        $name = str_replace('\\', '/', $name);
        $name = str_replace('App/', '', $name);

        return base_path("database/seeders/{$name}.php");
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = file_get_contents($this->getStub());

        // Get the name from the arguments
        $className = $this->argument('name');
        $modelName = $this->option('model') ?? 'Model'; // Default fallback if model not provided

        // Replace the placeholders in the stub
        return str_replace(
            ['{{ class }}', '{{ model }}', '{{ namespace }}'],
            [$className, $modelName, 'Database\\Seeders'], // Update namespace if needed
            $stub
        );
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Call the parent handle method to run the generator
        parent::handle();
    }
}
