<?php
require_once '../core/bootstrap.php';
require_once '../site/register.php';
//$core = new  AreaCore();
$container = new Container();
register_core_types($container);
register_domain_types($container);
$container->build();
$provider = $container->resolve(CoresProvider::class);
//$provider = new CoresProvider();
