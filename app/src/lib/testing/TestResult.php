<?php

namespace Lib\Testing;

use Lib\Testing\TestException;

const TEXT_TEST_FAILED = <<<EOD
Test failed:
    Name: %s
    Message: %s
    Traceback:
    %s\n
EOD;

const TEXT_TEST_FAILED_ASSERTION = <<<EOD
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
    public \Exception $exception;
    public string $message;

    /**
     * TestResult constructor
     * @since 1.0.0
     */
    public function __construct(string $name, \Exception $exception = null)
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
     * Convert trace to string
     * @param array $trace Trace
     * @return string Trace as string
     */
    public static function traceToString(array $trace): string
    {
        $file = $trace['file'];
        $line = $trace['line'];
        $function = $trace['function'];

        $args = array_map(function ($arg) {
            if (is_string($arg))
                return "'$arg'";
            if (is_array($arg))
                return 'Array';
            if (is_object($arg))
                return get_class($arg);
            return $arg;
        }, $trace['args'] ?? []);

        $args = implode(', ', $args);
        return "$function($args) @ $file:$line";
    }

    /**
     * Get traceback as string
     * @param array $traceback Traceback
     * @return string Traceback as string
     * @since 1.0.0
     */
    public static function getTracebackAsString(array $traceback): string
    {
        $traceback = array_splice($traceback, 1, -1);

        $traceback = array_map(function ($item) {
            return self::traceToString($item);
        }, $traceback);

        $traceback[] = '{main}';
        $traceback = implode("\n    ", $traceback);
        return $traceback;
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
        if ($result->exception instanceof TestException) {
            // If exceptiion is a TestException message
            $exception = $result->exception;
            $traceback = $exception->getTrace();
            $assertion = self::traceToString($traceback[1]);
            return sprintf(TEXT_TEST_FAILED_ASSERTION, $result->name, $exception->getMessage(), $assertion);
        }
        // Otherwise, return exception message
        $exception = $result->exception;
        $traceback = $exception->getTrace();
        $traceback = self::getTracebackAsString($traceback);
        return sprintf(TEXT_TEST_FAILED, $result->name, $exception->getMessage(), $traceback);
    }

    public function __toString(): string
    {
        return $this->message;
    }
}
