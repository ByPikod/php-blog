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
}
