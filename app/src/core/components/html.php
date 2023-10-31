<?php
if (!isset($render) || !is_callable($render))
    throw new Exception('Render function needed for this component');
?>
<html lang="en">
    <?php
    include('head.php');
    ?>
<body>
    <?php
    include('header.php');
    $render();
    include('footer.php');
    ?>
</body>
</html>