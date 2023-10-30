<?php

/**
 * Functions for getting meta information
 *
 * @author Yahya Batulu <admin@yahyabatulu.com>
 * @since  v1.0.0
 */

namespace Core\Meta;

/**
 * Returns the composer.json file as an array
 *
 * @return mixed Composer config
 * @since 1.0.0
 */
function getComposerConfig(): mixed
{
    $content = file_get_contents(__DIR__ . '/composer.json');
    return json_decode($content, true);
}

/**
 * Returns the current version of the application
 *
 * @return string Version
 * @since 1.0.0
 */
function getVersion(): string
{
    $composer = getComposerConfig();
    return $composer['version'];
}
