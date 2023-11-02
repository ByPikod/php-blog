<?php

namespace Lib\Testing;

use Lib\Testing\TestException;

const TEXT_SUITE_TEST_RESULT = <<<EOD
Results:
    Suit Name: %s
    Passed: %s
    Failed: %s\n
EOD;

const TEXT_SUITE_TEST = <<<EOD
Test suite:\n
EOD;

/**
 * TestManager class
 * @since 1.0.0
 */
class Test
{
    /**
     * Private constructor to prevent instantiation.
     * @since 1.0.0
     */
    private function __construct()
    {
    }

    /**
     * Run a test
     * @param string $name Test name
     * @param callable $callback Test callback
     * @return TestResult Test result
     * @since 1.0.0
     */
    private static function test(string $name, callable $callback): TestResult
    {
        $test = new Test();
        $result = null;
        try {
            $callback($test);
            $result = new TestResult($name);
        } catch (TestException $e) {
            $result = new TestResult($name, $e);
        }

        return $result;
    }

    /**
     * Run a test
     * @since 1.0.0
     */
    public static function it(string $name, callable $callback): void
    {
        // Just print the result
        echo self::test($name, $callback)->message;
    }

    /**
     * Run all tests
     */
    public static function suite(string $name, callable $callback)
    {
        // Statistics
        $passed = 0;
        $failed = 0;

        echo TEXT_SUITE_TEST;

        // Run all tests
        $callback(function ($name, $callback) use (&$passed, &$failed) {
            $result = self::test($name, $callback); // Run test
            $message = $result->message; // Get message
            $message = "\u{2022} " . $message; // Add bullet point
            $message = preg_replace('/^/m', '    ', $message); // Indent
            echo $message;
            if ($result->passed) $passed++;
            else $failed++;
        });

        // Format results
        $result = sprintf(
            TEXT_SUITE_TEST_RESULT,
            $name,
            $passed . "/" . $passed + $failed,
            $failed . "/" . $passed + $failed
        );
        $result = preg_replace('/^/m', '    ', $result); // Add indentation

        // Print results
        echo "\n";
        echo $result;
    }

    /**
     * Throw error
     * @since 1.0.0
     */
    public function fatal($message)
    {
        throw new TestException($message);
    }

    //
    // Equality assertions
    //

     /**
      * Assert that two values are equal
      * @since 1.0.0
      */
    public function assertEqual($a, $b)
    {
        if ($a != $b)
            $this->fatal("{$a} != {$b}");
    }

    /**
     * Assert that two values are not equal
     * @since 1.0.0
     */
    public function assertNotEqual($a, $b)
    {
        if ($a == $b)
            $this->fatal("{$a} === {$b}");
    }

    /**
     * Assert that reference values are equal
     * @since 1.0.0
     */
    public function assetSame(&$a, &$b)
    {
        if ($a !== $b)
            $this->fatal("{$a} !== {$b}");
    }

    /**
     * Assert that reference values are not equal
     * @since 1.0.0
     */
    public function assertNotSame(&$a, &$b)
    {
        if ($a === $b)
            $this->fatal("{$a} === {$b}");
    }

    //
    // Nullity assertions
    //

    /**
     * Assert that value is null
     * @since 1.0.0
     */
    public function assertNull($a)
    {
        if ($a !== null)
            $this->fatal("{$a} !== null");
    }

    /**
     * Assert that value is not null
     * @since 1.0.0
     */
    public function assertNotNull($a)
    {
        if ($a === null)
            $this->fatal("{$a} === null");
    }

    //
    // Boolean assertions
    //

    /**
     * Assert that value is true
     * @since 1.0.0
     */
    public function assertTrue($a)
    {
        if ($a !== true)
            $this->fatal("{$a} !== true");
    }

    /**
     * Assert that value is false
     * @since 1.0.0
     */
    public function assertFalse($a)
    {
        if ($a !== false)
            $this->fatal("{$a} !== false");
    }

    //
    // Comparision assertions
    //

    /**
     * Assert that value is greater than
     * @since 1.0.0
     */
    public function assertGreaterThan($a, $b)
    {
        if ($a <= $b)
            $this->fatal("{$a} <= {$b}");
    }

    /**
     * Assert that value is greater than or equal to
     * @since 1.0.0
     */
    public function assertGreaterThanOrEqual($a, $b)
    {
        if ($a < $b)
            $this->fatal("{$a} < {$b}");
    }

    /**
     * Assert that value is less than
     * @since 1.0.0
     */
    public function assertLessThan($a, $b)
    {
        if ($a >= $b)
            $this->fatal("{$a} >= {$b}");
    }

    /**
     * Assert that value is less than or equal to
     * @since 1.0.0
     */
    public function assertLessThanOrEqual($a, $b)
    {
        if ($a > $b)
            $this->fatal("{$a} > {$b}");
    }

    //
    // String assertions
    //

    /**
     * Assert that string contains substring
     * @since 1.0.0
     */
    public function assertContains(string $a, string $b)
    {
        if (strpos($a, $b) === false)
            $this->fatal("{$a} does not contain {$b}");
    }

    /**
     * Assert that string does not contain substring
     * @since 1.0.0
     */
    public function assertNotContains(string $a, string $b)
    {
        if (strpos($a, $b) !== false)
            $this->fatal("{$a} contains {$b}");
    }

    /**
     * Assert that string starts with substring
     * @since 1.0.0
     */
    public function assertStartsWith(string $a, string $b)
    {
        if (strpos($a, $b) !== 0)
            $this->fatal("{$a} does not start with {$b}");
    }

    /**
     * Assert that string does not start with substring
     * @since 1.0.0
     */
    public function assertNotStartsWith(string $a, string $b)
    {
        if (strpos($a, $b) === 0)
            $this->fatal("{$a} starts with {$b}");
    }

    /**
     * Assert that string ends with substring
     * @since 1.0.0
     */
    public function assertEndsWith(string $a, string $b)
    {
        if (strpos($a, $b) !== strlen($a) - strlen($b))
            $this->fatal("{$a} does not end with {$b}");
    }

    /**
     * Assert that string does not end with substring
     * @since 1.0.0
     */
    public function assertNotEndsWith(string $a, string $b)
    {
        if (strpos($a, $b) === strlen($a) - strlen($b))
            $this->fatal("{$a} ends with {$b}");
    }

    //
    // Array assertions
    //

    /**
     * Assert that array contains value
     * @since 1.0.0
     */
    public function assertArrayContains(array $a, $b)
    {
        if (!in_array($b, $a))
            $this->fatal("Array does not contain {$b}");
    }

    /**
     * Assert that array does not contain value
     * @since 1.0.0
     */
    public function assertArrayNotContains(array $a, $b)
    {
        if (in_array($b, $a))
            $this->fatal("Array contains {$b}");
    }

    /**
     * Assert that array contains key
     * @since 1.0.0
     */
    public function assertArrayHasKey(array $a, $b)
    {
        if (!array_key_exists($b, $a))
            $this->fatal("Array does not contain key {$b}");
    }

    /**
     * Assert that array does not contain key
     * @since 1.0.0
     */
    public function assertArrayNotHasKey(array $a, $b)
    {
        if (array_key_exists($b, $a))
            $this->fatal("Array contains key {$b}");
    }

    /**
     * Assert that array empty
     * @since 1.0.0
     */
    public function assertArrayEmpty(array $a)
    {
        if (!empty($a))
            $this->fatal("Array is not empty");
    }

    /**
     * Assert that array not empty
     * @since 1.0.0
     */
    public function assertArrayNotEmpty(array $a)
    {
        if (empty($a))
            $this->fatal("Array is empty");
    }

    // Exception assertions

    /**
     * Assert that exception is thrown
     * @since 1.0.0
     */
    public function assertException(callable $callback)
    {
        try {
            $callback();
        } catch (\Exception $e) {
            return;
        }

        $this->fatal("Exception not thrown");
    }

    /**
     * Assert that exception is not thrown
     * @since 1.0.0
     */
    public function assertNotException(callable $callback)
    {
        try {
            $callback();
        } catch (\Exception $e) {
            $this->fatal("Exception thrown");
        }
    }

    //
    // Other assertions
    //

    /**
     * Timeout assertion
     */
    public function assertTimeout(int $seconds, callable $callback)
    {
        $start = microtime(true);
        $callback();
        $end = microtime(true);
        $elapsed = $end - $start;
        if ($elapsed > $seconds)
            $this->fatal("Timeout after {$elapsed} seconds");
    }
}
