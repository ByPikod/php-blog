<?php

/**
 * Main layout file
 */

require __DIR__ . '/../vendor/autoload.php';

use Core\Meta;

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>First</title>
</head>
<body>
    <footer>
        Project version: <?php echo Meta\getVersion() ?>
    </footer>
</body>
</html>