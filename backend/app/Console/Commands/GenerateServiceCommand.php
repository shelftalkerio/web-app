<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class GenerateServiceFile extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:service-file {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will create a new service file';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/service.stub');
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

        return base_path("app/Services/{$name}Service.php");
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
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = file_get_contents($this->getStub());

        $name = str_replace('App\\', '', $name);

        $stub = str_replace('{{ class }}', $name, $stub);
        $stub = str_replace('{{ namespace }}', 'App\\Services', $stub);

        return $stub;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
        ];
    }
}
