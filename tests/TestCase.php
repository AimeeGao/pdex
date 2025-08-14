<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutJobs();

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
