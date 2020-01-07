<?php
// Although the current directory is app/views , but in real this page rendered from public/index.php
// so we can define it in bootstrap.php
//include '../app/helpers/layoutHelpers.php';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php Helpers\Layout\print_mvc_title(); ?></title>
</head>
<body>
<h1>Header</h1>
<div>
    <?php
    echo $GLOBALS[__GLOB__BODY__];
    ?>
</div>
<h1>Footer</h1>

</body>
</html>
