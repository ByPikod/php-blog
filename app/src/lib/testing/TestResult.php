<?php

namespace Lib\Testing;

use Lib\Testing\TestException;

const TEXT_TEST_FAILED = <<<EOD
Test failed:
    Name: %s
    Message: %s
    Assertion: %s\n
EOD;

/**
 * TestManager class
 * @since 1.0.0
 */
class TestResult
{
    public string $name;
    public bool $passed;
    public TestException $exception;
    public string $message;

    /**
     * TestResult constructor
     * @since 1.0.0
     */
    public function __construct(string $name, TestException $exception = null)
    {
        $this->name = $name;
        if ($exception !== null) {
            $this->passed = false;
            $this->exception = $exception;
        } else {
            $this->passed = true;
        }
        $this->message = self::toString($this);
    }

    /**
     * Convert TestResult to string
     * @param TestResult $result TestResult
     * @return string TestResult as string
     * @see TEXT_TEST_FAILED
     * @since 1.0.0
     */
    private static function toString(TestResult $result): string
    {
        // If passed or no exception, return passed message
        if ($result->passed) {
            return "Test passed: $result->name\n";
        }
        // Otherwise, return failed message
        $exception = $result->exception;
        $traceback = $exception->getTrace();
        $assertion = TestException::traceToString($traceback[1]);
        return sprintf(TEXT_TEST_FAILED, $result->name, $exception->getMessage(), $assertion);
    }

    public function __toString(): string
    {
        return $this->message;
    }
}
