<?php

namespace Clearazil\LaravelCrudGenerator\Console\Commands;

use \DateTime;
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

    private function getFiles($modelName)
    {
        return [
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
            [
                'contents' => file_get_contents(__DIR__ . '/stubs/views/show.blade.php.stub'),
                'targetDir' => 'resources/views/' . $modelName . '/',
                'name' => 'show.blade.php',
            ],
            [
                'contents' => file_get_contents(__DIR__ . '/stubs/views/index.blade.php.stub'),
                'targetDir' => 'resources/views/' . $modelName . '/',
                'name' => 'index.blade.php',
            ],
            [
                'contents' => file_get_contents(__DIR__ . '/stubs/model/Model.php.stub'),
                'targetDir' => 'app/Models/',
                'name' => ucfirst(Str::camel($modelName)) . '.php',
            ],
            [
                'contents' => file_get_contents(__DIR__ . '/stubs/controller/CrudController.php.stub'),
                'targetDir' => 'app/Http/Controllers/',
                'name' => ucfirst(Str::camel($modelName)) . 'Controller.php',
            ],
            [
                'contents' => file_get_contents(__DIR__ . '/stubs/database/migrations/create-new-table.stub'),
                'targetDir' => 'database/migrations/',
                'name' => (new DateTime())->format('Y_m_d') .
                    '_000000_create_' .  Str::plural(Str::snake($modelName)) . '_table.php',
            ],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $modelName = strtolower($this->ask('Model name:'));

        $storageDriver = Storage::build([
            'driver' => 'local',
            'root' => base_path(),
        ]);

        $type = '';
        $dataFields = [];
        $files = $this->getFiles($modelName);

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

        $indexHeadings = '';
        $headingContent = file_get_contents(__DIR__ . '/stubs/views/partials/indexHeading.stub');

        $fillables = '';
        $validationRules = '';
        $columns = '';

        foreach ($dataFields as $field) {
            $indexHeadings = $indexHeadings .
                str_replace('**nameUppercase**', ucfirst($field['name']), $headingContent);

            $fillables = $fillables . '\'' . $field['name'] . '\', ';

            $validationRules = $validationRules . str_replace(
                '**fieldName**',
                $field['name'],
                file_get_contents(__DIR__ . '/stubs/controller/partials/validation-rule.stub')
            );

            $columns = $columns . str_replace(
                '**fieldName**',
                Str::snake($field['name']),
                file_get_contents(__DIR__ . '/stubs/database/migrations/partials/column-' .
                    $this->fieldOptions[$field['type']] . '.stub')
            );
        }

        $indexHeadings = trim($indexHeadings);
        $fillables = trim($fillables);
        $validationRules = trim($validationRules);
        $columns = trim($columns);

        foreach ($files as $file) {
            $fields = '';
            $fileContents = $file['contents'];

            $viewFiles = [
                'create.blade.php',
                'edit.blade.php',
                'show.blade.php',
                'index.blade.php',
            ];

            if (in_array($file['name'], $viewFiles)) {
                foreach ($dataFields as $field) {
                    $fields = $fields . "\n" . trim($this->createPartialField($field, $modelName, $file['name']), "\n");
                }

                $fileContents = str_replace('**fields**', $fields, trim($fileContents, "\n"));
            }

            $fileContents = str_replace([
                '**modelNameLowercase**',
                '**modelNameUppercase**',
                '**modelNamePluralLowercase**',
                '**modelNamePluralUppercase**',
                '**modelNameCamelcase**',
                '**modelNamePluralCamelcase**',
                '**modelNamePluralPascalcase**',
                '**modelNamePascalcase**',
                '**modelNamePluralSnakecase**',
                '**modelNameSnakecase**',
                '**columns**',
                '**validationRules**',
                '**fillables**',
                '**indexHeadings**',
                '**enctype**',
            ], [
                $modelName,
                ucfirst($modelName),
                Str::plural($modelName),
                ucfirst(Str::plural($modelName)),
                Str::camel($modelName),
                Str::camel(Str::plural($modelName)),
                ucfirst(Str::camel(Str::plural($modelName))),
                ucfirst(Str::camel($modelName)),
                Str::snake(Str::plural($modelName)),
                Str::snake($modelName),
                $columns,
                $validationRules,
                $fillables,
                $indexHeadings,
                'enctype="multipart/form-data', // only with file upload
            ], $fileContents);

            $storageDriver->put($file['targetDir'] . $file['name'], $fileContents);
        }

        $this->info('Files created!');
    }

    private function createPartialField($field, $modelName, $fileName)
    {
        $fieldContent = '';

        $partialType = 'form';

        if ($fileName === 'show.blade.php') {
            $partialType = 'show';
        } elseif ($fileName === 'index.blade.php') {
            $partialType = 'index';
        }

        switch ($this->fieldOptions[$field['type']]) {
            case 'text':
                $fieldContent = file_get_contents(__DIR__ . '/stubs/views/partials/' . $partialType . '-text.stub');
                break;
            case 'textarea':
                $fieldContent = file_get_contents(__DIR__ . '/stubs/views/partials/' . $partialType . '-textarea.stub');
                break;
        }

        $formValue = '';

        if ($fileName === 'create.blade.php') {
            $formValue = "{{ old('{" . $field['name'] . "}') }}";
        } elseif ($fileName === 'edit.blade.php') {
            $formValue =  "{{ old('{" . $field['name'] . "}', $" . $modelName . "->" . $field['name'] . ") }}";
        }

        $fieldContent = str_replace([
            '**nameUppercase**',
            '**nameLowercase**',
            '**nameCamelcase**',
            '**modelNamePluralLowercase**',
            '**modelNameLowercase**',
            '**modelNameCamelcase**',
            '**formValue**',
        ], [
            ucfirst($field['name']),
            $field['name'],
            Str::camel($field['name']),
            Str::plural($modelName),
            $modelName,
            Str::camel($modelName),
            $formValue,
        ], $fieldContent);

        return $fieldContent;
    }
}
