<?php

namespace Lib\Router;

use Lib\Testing\Test;

/**
 * RouterTest class
 * @since 1.0.0
 */
class RouterTest extends Router
{
    /**
     * Test get branch
     * @test Method getBranch
     * @since 1.0.0
     */
    public function getBranchTest(Test $test): void
    {
        // Check if getter returns the correct branch
        $this->getBranch('/getBranchTest')[] = 'test';
        $test->assertArrayContains($this->getBranch('/getBranchTest'), 'test');
    }

    /**
     * Test use
     * @test Check if Router adds the middleware
     * @since 1.0.0
     */
    public function useAdditionTest(Test $test): void
    {
        $testPassed = false;
        // Test use
        $this->use(function () use (&$testPassed) {
            $testPassed = true;
        }, '/useAdditionTest');
        $this->getBranch('/useAdditionTest')[0]();
        $test->assertTrue($testPassed);
    }

    /**
     * Test use
     * @test Check if Router calls the middleware
     * @since 1.0.0
     */
    public function useTest(Test $test): void
    {
        $_SERVER['REQUEST_URI'] = '/useTest';
        $testPassed = false;
        // Test use
        $this->use(function ($context) use (&$testPassed) {
            $testPassed = true;
        }, '/useTest');
        $this->run();
        $test->assertTrue($testPassed);
    }

    /**
     * Test route
     * @test Check if Router adds the route
     * @since 1.0.0
     */
    public function routeAdditionTest(Test $test): void
    {
        $testPassed = false;
        // Test route
        $this->route('/routeAdditionTest', function () use (&$testPassed) {
            $testPassed = true;
        });
        // Test if the route was added
        $route = $this->getBranch('/routeAdditionTest')[0];
        $test->assertInstanceOf($route, Route::class);
        // Test if the route has the callback
        ($route->callback)();
        $test->assertTrue($testPassed);
    }

    /**
     * Test route
     * @test Check if Router calls the route
     * @since 1.0.0
     */
    public function routeTest(Test $test): void
    {
        $_SERVER['REQUEST_URI'] = '/routeTest';
        $testPassed = false;
        // Test route
        $this->route('/routeTest', function ($context) use (&$testPassed) {
            $testPassed = true;
        });
        $this->run();
        $test->assertTrue($testPassed);
    }
}
