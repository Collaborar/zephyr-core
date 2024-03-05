<?php

namespace ZephyrCoreTestTools;

use Brain\Monkey;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

/**
 * Test Case
 */
class TestCase extends PHPUnitTestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }

    /**
     * This method is called after each test.
     */
    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }
}

