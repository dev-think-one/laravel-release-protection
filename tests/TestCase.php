<?php

namespace ReleaseProtection\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            \ReleaseProtection\ServiceProvider::class,
        ];
    }

    public function defineEnvironment($app)
    {
        $app['config']->set('release-protection.email.envs.default', 'testing');
        $app['config']->set('release-protection.ip.envs.default', 'testing');
    }
}
