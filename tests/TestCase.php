<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = false;

    /**
     * Run a specific seeder before each test.
     *
     * @var string
     */
    protected $seeder;

    /**
     * Define hooks to migrate the database before and after each test.
     *
     * @return void
     */
    public function refreshDatabase(): void
    {
        $this->artisan('migrate:fresh', [
            '--drop-views' => true,
            '--drop-types' => true,
            '--seed' => $this->seed,
            '--seeder' => $this->seeder,
        ]);

        $this->app[Kernel::class]->setArtisan(null);
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set queue driver to sync for tests to avoid async job issues
        config(['queue.default' => 'sync']);
        
        // Ensure we're in testing environment
        $this->assertSame('testing', app()->environment(), 'Tests must run in testing environment');
        
        // Ensure we're using in-memory SQLite for tests
        $this->assertSame('sqlite', Config::get('database.default'), 'Tests must use SQLite');
        $this->assertSame(':memory:', Config::get('database.connections.sqlite.database'), 'Tests must use in-memory database');
        
        // Verify we're not connected to production database
        $this->assertNotSame('pgsql', DB::getDefaultConnection(), 'Tests must not use production PostgreSQL');
        $this->assertNotSame('mysql', DB::getDefaultConnection(), 'Tests must not use MySQL');
        
        // Additional safety check
        if (app()->environment('production')) {
            throw new \Exception('Tests should never run in production environment');
        }
    }

    protected function tearDown(): void
    {
        // Ensure database is properly cleaned up after each test
        if (app()->bound('db')) {
            DB::disconnect();
        }
        
        parent::tearDown();
    }
}
