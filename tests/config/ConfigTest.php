<?php

declare(strict_types=1);

namespace Tests\Config;

use PHPUnit\Framework\TestCase;
use App\Config\SYSTEM_NAME;
use App\Config\SYSTEM_VERSION;
use App\Config\ENVIRONMENT;

require_once __DIR__ . '/../../app/config/config.php';


class ConfigTest extends TestCase
{
    public function testSystemName()
    {
        $this->assertSame('Ominichanel', \App\Config\SYSTEM_NAME);
    }

    public function testSystemVersion()
    {
        $this->assertEquals('1.0.0', \App\Config\SYSTEM_VERSION);
    }

    public function testEnvironment()
    {
        $this->assertEquals('TEST', \App\Config\ENVIRONMENT);
    }

    public function testDatabaseConfig()
    {
        $config = \App\Config\get_database_config();

        $this->assertArrayHasKey('host', $config);
        $this->assertArrayHasKey('user', $config);
        $this->assertArrayHasKey('password', $config);
        $this->assertArrayHasKey('name', $config);
    }

    // ... (adicione outros testes para as funcionalidades do config.php)
}