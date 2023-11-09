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
     * @test Method Test - getBranch
     * @since 1.0.0
     */
    public function getBranchTest(Test $test): void
    {
        // Check if getter returns the correct branch
        $this->getBranch('/getBranchTest')[] = 'test';
        $test->assertArrayContains($this->getBranch('/getBranchTest'), 'test');
    }

    /**
     * Test seperate path
     * @test Method Test - seperatePath
     * @since 1.0.0
     */
    public function seperatePathTest(Test $test)
    {
        // Check if the path is seperated correctly
        $assertions = [
            '/' => [],
            '' => [],
            '/seperatePathTest' => ['seperatePathTest'],
            '/seperatePathTest/' => ['seperatePathTest'],
            'seperatePathTest' => ['seperatePathTest'],
            'seperatePathTest/' => ['seperatePathTest'],
            '/seperatePathTest/seperatePathTest' => ['seperatePathTest', 'seperatePathTest'],
            '/seperatePathTest/seperatePathTest/' => ['seperatePathTest', 'seperatePathTest'],
            'seperatePathTest/seperatePathTest' => ['seperatePathTest', 'seperatePathTest'],
            'seperatePathTest/seperatePathTest/' => ['seperatePathTest', 'seperatePathTest'],
        ];
        foreach ($assertions as $path => $expected) {
            $test->assertArrayEqual(self::seperatePath($path), $expected);
        }
    }

    /**
     * Test method getExecutables
     * @test Method Test - getExecutables
     * @since 1.0.0
     */
    public function getExecutablesTest(Test $test): void
    {
        $fill = [];
        $fakeFunc = function () use (&$fill) {
            $fill[] = true;
        };

        $branch = [
            new Route('test', $fakeFunc),
            new Route('test', $fakeFunc),
            $fakeFunc,
            'a' => 'test',
            'b' => 'test',
            'test'
        ];

        $executables = self::getExecutables($branch, true);
        foreach ($executables as $executable) {
            if (is_callable($executable)) $executable();
            if ($executable instanceof Route) {
                ($executable->callback)();
            }
        }

        $test->assertEqual(3, sizeof($fill));
        $test->assertArrayEqual($fill, [true, true, true]);
    }

    /**
     * Run executables test
     * @test Method Test - runExecutables
     * @since 1.0.0
     */
    public function runExecutablesTest(Test $test): void
    {
        $fill = [];
        $fakeFunc = function (Context $ctx) use (&$fill) {
            $fill[] = true;
            $ctx->next();
        };

        $this->use($fakeFunc);
        $this->use($fakeFunc, '/executablesTest');
        $this->route('/executablesTest', $fakeFunc)->use($fakeFunc)->use($fakeFunc);

        $this->executeTree('/executablesTest');
        $test->assertEqual(4, sizeof($fill));
    }

    /**
     * Test use
     * @test Middleware add test
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
     * @test Middleware test
     * @since 1.0.0
     */
    public function useTest(Test $test): void
    {
        $_SERVER['REQUEST_URI'] = '/useTest';
        $testPassed = false;
        // Test use
        $this->use(function (Context $context) use (&$testPassed) {
            $testPassed = true;
            $context->next();
        }, '/useTest');
        $this->run();
        $test->assertTrue($testPassed);
    }

    /**
     * Test route
     * @test Route add test
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
     * @test Route test
     * @since 1.0.0
     */
    public function routeTest(Test $test): void
    {
        $_SERVER['REQUEST_URI'] = '/routeTest';
        $testPassed = false;
        // Test route
        $this->route('/routeTest', function (Context $context) use (&$testPassed) {
            $testPassed = true;
            $context->next();
        });
        $this->run();
        $test->assertTrue($testPassed);
    }

    /**
     * Test group
     * @test Route wildcard test
     * @since 1.0.0
     */
    public function routeWildcardTest(Test $test): void
    {
    }
}
