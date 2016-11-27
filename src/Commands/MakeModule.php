<?php

namespace KevinOngko\LaravelGenode\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new module';

    /**
     * Module Name.
     *
     * @var string
     */
    protected $moduleName;

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
        $this->moduleName = studly_case($this->ask('What is the module name?'));

        $this->moduleDirectory = base_path('modules/'.$this->moduleName);

        if ($this->file->exists($this->moduleDirectory)) {
            return $this->error($this->moduleName.' module already exists!');
        }

        $this->generateDirectories();

        $this->compileStubs();

        $this->info('Module created successfully.');

        $this->composer->dumpAutoloads();
    }

    /**
     * Build the directory for the class.
     */
    public function generateDirectories()
    {
        $this->file->makeDirectory($this->moduleDirectory.'/database/migrations', 0775, true);
        $this->file->makeDirectory($this->moduleDirectory.'/resources/lang/en', 0775, true);
        $this->file->makeDirectory($this->moduleDirectory.'/resources/views', 0775, true);
        $this->file->makeDirectory($this->moduleDirectory.'/routes', 0775, true);
        $this->file->makeDirectory($this->moduleDirectory.'/src/Http/Controllers', 0775, true);
        $this->file->makeDirectory($this->moduleDirectory.'/src/Providers', 0775, true);
    }

    /**
     * Compile the stubs.
     */
    protected function compileStubs()
    {
        $this->file->put(
            $this->moduleDirectory.'/src/Providers/'.$this->moduleName.'ServiceProvider.php',
            $this->compileProviderStub()
        );

        $this->file->put(
            $this->moduleDirectory.'/composer.json',
            $this->compileComposerStub()
        );

        $this->file->put(
            $this->moduleDirectory.'/routes/api.php',
            $this->file->get(__DIR__.'/../../stubs/route-api.stub')
        );

        $this->file->put(
            $this->moduleDirectory.'/routes/web.php',
            $this->file->get(__DIR__.'/../../stubs/route-web.stub')
        );
    }

    /**
     * Compile stub for provider.
     *
     * @return string
     */
    protected function compileProviderStub()
    {
        $stub = $this->file->get(__DIR__.'/../../stubs/provider.stub');

        $this->replaceProviderClass($stub)
            ->replaceNamespace($stub)
            ->replaceModuleName($stub);

        return $stub;
    }

    /**
     * Compile stub for composer.json.
     *
     * @return string
     */
    protected function compileComposerStub()
    {
        $stub = $this->file->get(__DIR__.'/../../stubs/composer.stub');

        $this->replaceVendorName($stub)
            ->replaceAuthorName($stub)
            ->replaceAuthorEmail($stub)
            ->replaceNamespace($stub)
            ->replaceModuleName($stub);

        return $stub;
    }

    /**
     * Replace service provider class name.
     *
     * @param string $stub
     * @return $this
     */
    protected function replaceProviderClass(&$stub)
    {
        $name = $this->moduleName.'ServiceProvider';

        $stub = str_replace('{{class}}', $name, $stub);

        return $this;
    }

    /**
     * Replace composer.json vendor  name.
     *
     * @param string $stub
     * @return $this
     */
    protected function replaceVendorName(&$stub)
    {
        $name = config('module.composer.vendor');

        $stub = str_replace('{{vendor}}', $name, $stub);

        return $this;
    }

    /**
     * Replace composer.json author name.
     *
     * @param string $stub
     * @return $this
     */
    protected function replaceAuthorName(&$stub)
    {
        $name = config('module.composer.author.name');

        $stub = str_replace('{{author}}', $name, $stub);

        return $this;
    }

    /**
     * Replace composer.json author email.
     *
     * @param string $stub
     * @return $this
     */
    protected function replaceAuthorEmail(&$stub)
    {
        $name = config('module.composer.author.email');

        $stub = str_replace('{{email}}', $name, $stub);

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
        $stub = str_replace('{{namespace}}', $this->moduleName, $stub);

        return $this;
    }

    /**
     * Replace module name.
     *
     * @param string $stub
     * @return $this
     */
    protected function replaceModuleName(&$stub)
    {
        $name = strtolower($this->moduleName);

        $stub = str_replace('{{module}}', $name, $stub);

        return $this;
    }
}
