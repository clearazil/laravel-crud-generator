<?php

namespace Clearazil\LaravelCrudGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Undocumented class
 *
 */
class CreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud-generator:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate code from a template';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        //var_dump(__DIR__);die;
        $modelName = strtolower($this->ask('Model name:'));

        $file = file_get_contents(__DIR__ . '/stubs/views/create.blade.php.stub');

        $field = '';
        $fields = '';

        while ($field !== null) {
            $field = $this->ask("Submit fields \n1. text\n2. textarea\n\nEnter nothing to stop submitting fields");

            if (empty($field)) {
                $field = null;
                break;
            }

            $formField = $this->createFormField($field);
            if (!empty($formField)) {
                $fields = $fields . "\n$formField";
            }
        }

        $file = str_replace('**fields**', $fields, $file);

        $file = str_replace([
            '**nameUppercase**',
            '**namePluralLowercase**',
            '**namePluralUppercase**',
            '**enctype**',
        ], [
            ucfirst($modelName),
            Str::plural($modelName),
            ucfirst(Str::plural($modelName)),
            'enctype="multipart/form-data', // only with file upload
        ], $file);
        //var_dump($file);die;

        $storageDriver = Storage::build([
            'driver' => 'local',
            'root' => base_path(),
        ]);

        $storageDriver->put('resources/views/' . $modelName . '/create.blade.php', $file);

       // Storage::disk('local')->put('/resources/views/' . strtolower($modelName) . '/create.blade.php', $file);
        $this->info('Tested command!');
    }

    private function createFormField($field)
    {
        $fieldOptions = [
            1 => 'text',
            2 => 'textarea',
        ];

        $formField = '';
        if (isset($fieldOptions[$field])) {
            $name = strtolower($this->ask('Submit a name for the field'));
            switch ($fieldOptions[$field]) {
                case 'text':
                    $formField = file_get_contents(__DIR__ . '/stubs/views/partials/form-text.stub');
                    break;
                case 'textarea':
                    $formField = file_get_contents(__DIR__ . '/stubs/views/partials/form-textarea.stub');
                    break;
            }

            $formField = str_replace([
                '**nameUpperCase**',
                '**nameLowerCase**',
                '**formValue**',
            ], [
                ucfirst($name),
                $name,
                "{{ old('" . $name . "') }}",
            ], $formField);
        } else {
            $this->error('You submitted an invalid option.');
        }

        return $formField;
    }
}
