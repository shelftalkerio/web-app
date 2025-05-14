<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class GenerateDatabaseTestCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:database-test {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new database test';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Test';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/database.table.test.stub');
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

        return base_path("tests/Feature/Database/{$name}DatabaseTableTest.php");
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
        $stub = str_replace('{{ namespace }}', 'Tests\\Feature\\Database', $stub);

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
