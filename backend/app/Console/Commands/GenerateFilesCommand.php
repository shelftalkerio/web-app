<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class GenerateFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate files for development.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $generatedItems = [];
        $name = Str::title(text('What is the name of the model?'));

        $controller = "{$name}Controller";
        $model = $name;
        $factory = "{$name}Factory";
        $policy = "{$name}Policy";
        $seeder = "{$name}Seeder";
        $test = "{$name}Test";
        $databaseTest = "{$name}DatabaseTableTest";
        $service = "{$name}Service";

        $confirmController = confirm('Would you like a Controller?');
        $optionsController = $confirmController ? multiselect('What options would you like with the Controller?', options: ['Resource', 'API']) : '';
        $confirmModel = confirm(label: 'Would you like a Model?');
        $optionsModel = $confirmModel ? multiselect(label: 'What options would you like with the Model?', options: ['Migration', 'Factory', 'Policy'], hint: 'You can select more than 1.') : '';
        $confirmTest = confirm('Would you like a Test?');
        $optionsTest = $confirmTest ? select(label: 'What type of test would you like?', options: ['Feature', 'Unit']) : '';
        $confirmService = confirm('Would you like a service file?');

        try {

            if ($confirmController) {

                $generatedItems[] = $controller;

                $controllerFlags = ['name' => $controller];

                if (in_array('Resource', $optionsController)) {
                    $controllerFlags['--resource'] = true;
                }

                if (in_array('API', $optionsController)) {
                    $controllerFlags['--api'] = true;
                }

                Artisan::call('make:controller', $controllerFlags);
            }

            if ($confirmModel) {
                $generatedItems[] = $model.' Model';

                $modelFlags = ['name' => $model];

                if (in_array('Migration', $optionsModel)) {
                    $generatedItems[] = $model.' Migration';
                    $modelFlags['-m'] = true;
                }

                if (in_array('Factory', $optionsModel)) {
                    $generatedItems[] = $factory;
                    $modelFlags['-f'] = true;
                }

                if (in_array('Policy', $optionsModel)) {
                    $generatedItems[] = $policy;
                    $modelFlags['--policy'] = true;
                }

                Artisan::call('make:model', $modelFlags);

                $generatedItems[] = $seeder;
                Artisan::call('make:custom-seeder', [
                    'name' => $seeder,
                    '--model' => $name,
                ]);

                Artisan::call('update:database-seeder', [
                    'name' => $seeder,
                ]);

                $generatedItems[] = $databaseTest;
                Artisan::call('generate:database-test', [
                    'name' => $name,
                ]);

            }

            if ($confirmTest) {

                $generatedItems[] = $test;
                $testFlags = ['name' => $test];

                if ($optionsTest === 'Unit') {
                    $testFlags['--unit'] = true;
                }

                Artisan::call('make:test', $testFlags);
            }

            if ($confirmService) {
                $generatedItems[] = $service;
                Artisan::call('generate:service-file', [
                    'name' => $name,
                ]);
            }

            $this->info('Successfully created: '.implode(', ', $generatedItems).'.');

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
