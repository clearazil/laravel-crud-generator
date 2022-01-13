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

    protected $fieldOptions = [
        1 => 'text',
        2 => 'textarea',
    ];

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

        $storageDriver = Storage::build([
            'driver' => 'local',
            'root' => base_path(),
        ]);

        $files = [
            [
                'contents' => file_get_contents(__DIR__ . '/stubs/views/create.blade.php.stub'),
                'targetDir' => 'resources/views/' . $modelName . '/',
                'name' => 'create.blade.php',
            ],
            [
                'contents' => file_get_contents(__DIR__ . '/stubs/views/edit.blade.php.stub'),
                'targetDir' => 'resources/views/' . $modelName . '/',
                'name' => 'edit.blade.php',
            ],
        ];

        $type = '';


        $dataFields = [];

        while ($type !== null) {
            $typeIsValid = false;

            while (!$typeIsValid) {
                $type = $this->ask("Submit fields \n1. text\n2. textarea\n\nEnter nothing to stop submitting fields");

                if (empty($type)) {
                    $type = null;
                    break;
                }

                if (isset($this->fieldOptions[$type])) {
                    $typeIsValid = true;
                } else {
                    $this->error('You submitted an invalid option.');
                }
            }

            if ($type === null) {
                break;
            }

            $name = strtolower($this->ask('Submit a name for the field'));

            $dataFields[] = [
                'type' => $type,
                'name' => $name,
            ];
        }

        foreach ($files as $file) {
            $fields = '';
            $fileContents = $file['contents'];

            if ($file['name'] === 'create.blade.php' || 'edit.blade.php') {
                foreach ($dataFields as $field) {
                    $formField = $this->createFormField($field, $modelName, $file['name']);
                    $fields = $fields . "\n$formField";
                }

                $fileContents = str_replace('**fields**', $fields, $fileContents);
            }

            $fileContents = str_replace([
                '**nameLowerCase**',
                '**nameUppercase**',
                '**namePluralLowercase**',
                '**namePluralUppercase**',
                '**enctype**',
            ], [
                $modelName,
                ucfirst($modelName),
                Str::plural($modelName),
                ucfirst(Str::plural($modelName)),
                'enctype="multipart/form-data', // only with file upload
            ], $fileContents);

            $storageDriver->put($file['targetDir'] . $file['name'], $fileContents);
        }


       // Storage::disk('local')->put('/resources/views/' . strtolower($modelName) . '/create.blade.php', $file);
        $this->info('Tested command!');
    }

    /**
     * Undocumented function
     *
     * @param array $field
     * @param string $modelName
     * @param string $fileName
     * @return void
     */
    private function createFormField($field, $modelName, $fileName)
    {
        $formField = '';

        switch ($this->fieldOptions[$field['type']]) {
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
            ucfirst($field['name']),
            $field['name'],
            $fileName === 'create.blade.php' ?
                "{{ old('{" . $field['name'] . "}') }}" :
                "{{ old('{" . $field['name'] . "}', $" . $modelName . "->" . $field['name'] . ") }}",
        ], $formField);

        return $formField;
    }
}
