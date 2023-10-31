<?php

/**
 * Build page. Callback should print page content.
 * @param callable $callback Callback function
 */
function buildPage(callable $callback): void
{

    ?>
    <html lang="en">
    <head>
        <?php
            include COMPONENTS_DIR . '/head.php';
        ?>
    </head>
    <body>
        <?php
            include COMPONENTS_DIR . '/header.php';
            $callback();
            include COMPONENTS_DIR . '/footer.php';
        ?>
    </body>
    </html>
    <?php
}
