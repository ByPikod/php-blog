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
        $this->getBranch('/a/b', false)[] = 'test';
        $test->assertArrayContains($this->getBranch('/a/b', false), 'test');
        // Test if getter returns the correct branch with popping the last element
        $this->getBranch('/a/b/test', true)[] = 'test';
        $test->assertArrayContains($this->getBranch('/a/b', false), 'test');
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
        }, '/x');
        $this->getBranch('/x', false)[0]();
        $test->assertTrue($testPassed);
    }

    /**
     * Test use
     * @test Check if Router calls the middleware
     * @since 1.0.0
     */
    public function useTest(Test $test): void
    {
        $_SERVER['REQUEST_URI'] = '/z';
        $testPassed = false;
        // Test use
        $this->use(function ($context) use (&$testPassed) {
            $testPassed = true;
        }, '/z');
        $this->run();
        $test->assertTrue($testPassed);
    }
}
