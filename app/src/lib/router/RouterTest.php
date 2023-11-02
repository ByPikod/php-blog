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
     * @test Branch Getter Test
     * @since 1.0.0
     */
    public function getBranchTest(Test $test): void
    {
        // Test get branch
        $this->getBranch('/a/b', false)[] = 'test';
        $test->assertArrayContains($this->getBranch('/a/b', false), 'test');
        // Test by popping the last element
        $this->getBranch('/a/b/test', true)[] = 'test';
        $test->assertArrayContains($this->getBranch('/a/b', false), 'test');
    }
}
