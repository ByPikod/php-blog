<?php

/**
 * Main layout file
 */

// use Core\Router\Context;
use Lib\Testing\Test;

require_once 'autoload.php';

// Failing test
echo "*Failing Test*\n";
Test::it("test", function (Test $test) {
    $test->assertEqual(1, 1);
    $test->assertEqual(1, 2);
});
echo "\n";

// Passing test
echo "*Passing Test*\n";
Test::it("test", function (Test $test) {
    $test->assertEqual(1, 1);
});
echo "\n";

// Suite
echo "*Suite Test*\n";
Test::suite("test", function ($it) {
    $it("test", function (Test $test) {
        $test->assertEqual(1, 1);
    });
    $it("test", function (Test $test) {
        $test->assertEqual(1, 2);
    });
});
echo "\n";
