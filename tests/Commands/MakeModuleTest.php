<?php

namespace Tests\Commands;

use Artisan;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Filesystem\Filesystem;
use Mockery;
use Tests\TestCase;

class MakeModuleTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->modulePath = base_path('modules/Blog');
    }

    public function tearDown()
    {
        parent::tearDown();
        exec('rm -rf '.base_path('modules'));
    }

    /** @test */
    public function it_display_info_when_success()
    {
        $command = Mockery::mock('\KevinOngko\LaravelGenode\Commands\MakeModule[ask]', [new Filesystem]);
        $command->shouldReceive('ask')->once()->with('What is the module name?')->andReturn('blog');
        $this->app[Kernel::class]->registerCommand($command);

        Artisan::call('module:new', ['--no-interaction' => true]);
        $output = Artisan::output();
        $this->assertEquals('Module created successfully.'."\n", $output);
    }

    /** @test */
    public function it_generate_module_folder_and_files()
    {
        $command = Mockery::mock('\KevinOngko\LaravelGenode\Commands\MakeModule[ask]', [new Filesystem]);
        $command->shouldReceive('ask')->once()->with('What is the module name?')->andReturn('blog');
        $this->app[Kernel::class]->registerCommand($command);

        Artisan::call('module:new', ['--no-interaction' => true]);

        $this->assertTrue(is_dir($this->modulePath));
        $this->assertTrue(is_dir($this->modulePath.'/database/migrations'));
        $this->assertTrue(is_dir($this->modulePath.'/resources/lang/en'));
        $this->assertTrue(is_dir($this->modulePath.'/resources/views'));
        $this->assertTrue(is_dir($this->modulePath.'/routes'));
        $this->assertTrue(is_dir($this->modulePath.'/src/Http/Controllers'));
        $this->assertTrue(is_dir($this->modulePath.'/src/Providers'));

        $this->assertTrue(is_file($this->modulePath.'/routes/web.php'));
        $this->assertTrue(is_file($this->modulePath.'/routes/api.php'));
        $this->assertTrue(is_file($this->modulePath.'/src/Providers/BlogServiceProvider.php'));
        $this->assertTrue(is_file($this->modulePath.'/composer.json'));
    }

    /** @test */
    public function it_display_errors_if_module_exist()
    {
        $command = Mockery::mock('\KevinOngko\LaravelGenode\Commands\MakeModule[ask]', [new Filesystem]);
        $command->shouldReceive('ask')->twice()->with('What is the module name?')->andReturn('blog');
        $this->app[Kernel::class]->registerCommand($command);

        Artisan::call('module:new', ['--no-interaction' => true]);
        Artisan::call('module:new', ['--no-interaction' => true]);

        $output = Artisan::output();
        $this->assertEquals('Blog module already exists!'."\n", $output);
    }
}
