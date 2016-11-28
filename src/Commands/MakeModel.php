<?php

namespace KevinOngko\LaravelGenode\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;

class MakeModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new model for a module';

    /**
     * Module name.
     *
     * @var string
     */
    protected $moduleName;

    /**
     * Model name.
     *
     * @var string
     */
    protected $modelName;

    /**
     * Module directory path.
     *
     * @var string
     */
    protected $moduleDirectory;

    /**
     * Filesystem instance.
     */
    protected $file;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(File $file)
    {
        parent::__construct();

        $this->file = $file;
        $this->composer = app()['composer'];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modules = collect(config('module.active'));

        $this->modelName = studly_case($this->ask('What is the model name?'));

        $this->moduleName = $this->choice('Where does this model live?', $modules);

        $this->moduleDirectory = base_path('modules/'.$this->moduleName);

        $this->generateDirectories();

        $this->compileStubs();

        $this->info('Model created successfully.');

        $this->composer->dumpAutoloads();
    }

    /**
     * Build the directory for the class.
     */
    public function generateDirectories()
    {
        $this->file->makeDirectory($this->moduleDirectory.'/src/Models', 0775, true);
    }

    /**
     * Compile the stubs.
     */
    protected function compileStubs()
    {
        $this->file->put(
            $this->moduleDirectory.'/src/Models/'.$this->modelName.'.php',
            $this->compileModelStub()
        );
    }

    /**
     * Compile stub for provider.
     *
     * @return string
     */
    protected function compileModelStub()
    {
        $stub = $this->file->get(__DIR__.'/../../stubs/model.stub');

        $this->replaceClass($stub)->replaceNamespace($stub)

        return $stub;
    }

    /**
     * Replace class name.
     *
     * @param string $stub
     * @return $this
     */
    protected function replaceClass(&$stub)
    {
        $name = $this->modelName;

        $stub = str_replace('{{class}}', $name, $stub);

        return $this;
    }

    /**
     * Replace namespace.
     *
     * @param string $stub
     * @return $this
     */
    protected function replaceNamespace(&$stub)
    {
        $stub = str_replace('{{namespace}}', $this->modelName, $stub);

        return $this;
    }
}
