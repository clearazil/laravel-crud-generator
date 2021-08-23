<?php

namespace Clearazil\LaravelCrudGenerator\Console\Commands;

use Illuminate\Console\Command;

/**
 * Undocumented class
 *
 */
class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:testing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test command in package';

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
