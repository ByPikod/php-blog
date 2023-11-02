<?php

namespace Lib\Testing;

/**
 * TestException class
 * @since 1.0.0
 */
class TestException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }

    public function __toString()
    {
        return "Test failed: {$this->getMessage()}\n";
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
     * NOTE: This function is no longer used.
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
}
