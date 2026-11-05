<?php

namespace OZiTAG\Tager\Backend\Core\Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use OZiTAG\Tager\Backend\Core\CoreServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            CoreServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        tap($app->make(Repository::class), function (Repository $config) {
            $config->set('database.default', 'testing');
            $config->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ]);
        });
    }

    /**
     * Schema for the fixture models used across the core test-suite.
     */
    protected function defineDatabaseMigrations(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->nullable();
            $table->string('title');
            $table->string('body')->nullable();
            $table->integer('priority')->default(0);
            $table->timestamps();
        });
    }
}
