<?php

namespace Clearazil\LaravelCrudGenerator\Console\Commands;

use Illuminate\Console\Command;

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
        $this->info('Tested command!');
    }
}
