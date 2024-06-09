<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use TestCaseSeeder;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;

    /**
     * @var TestCaseSeeder
     */
    public $seeder;

    public function setUp(): void
    {
        parent::setUp();

        $this->seeder = new TestCaseSeeder();
    }

    /**
     * @param object $instance
     * @param string $method
     * @param array $arguments
     * @return object
     * @throws \ReflectionException
     */
    protected function invokePrivateMethod($instance, $method, array $arguments = [])
    {
        $reflection = new \ReflectionClass($instance);
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($instance, $arguments);
    }
}
