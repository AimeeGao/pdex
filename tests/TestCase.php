<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set queue driver to sync for tests to avoid async job issues
        config(['queue.default' => 'sync']);
        
        // Fake queue to prevent jobs from being dispatched and causing transaction conflicts
        Queue::fake();
        
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
        
        // Run migrations fresh for each test to avoid transaction issues
        // Use simple migrate to avoid VACUUM and transaction conflicts
        $this->artisan('migrate');
    }

    protected function tearDown(): void
    {
        // No need for explicit cleanup with migrate:fresh approach
        parent::tearDown();
    }
}
