<?php

namespace Tests;

use KevinOngko\LaravelGenode\LaravelGenodeServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
   protected function getPackageProviders($app)
    {
        return [LaravelGenodeServiceProvider::class];
    }
}
