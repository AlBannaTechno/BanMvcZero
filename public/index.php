<?php
require_once '../core/bootstrap.php';
//$core = new  AreaCore();
$container = new Container();
register_core_types($container);
$container->build();
$provider = $container->resolve(CoresProvider::class);
//$provider = new CoresProvider();
