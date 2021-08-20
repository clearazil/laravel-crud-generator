<?php

namespace Clearazil\LaravelCrudGenerator\Commands;

use Illuminate\Console\Command;

/**
 * Undocumented class
 *
 * @category TEst
 * @package  Package
 * @author   Clearazil <clearazil@info.com>
 * @license  license http://url.com
 * @link     http://url.com
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
