<?php
require_once '../core/bootstrap.php';
//$core = new  AreaCore();
$container = new Container();
$container->provide(CoresProvider::class);
$provider = $container->resolve(CoresProvider::class);
//$provider = new CoresProvider();
