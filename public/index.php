<?php
require_once '../core/bootstrap.php';
//$core = new  AreaCore();
$container = new Container();
$container->provide(CoresProvider::class);
$container->provide(ControllerBase::class);
$container->provide(Core::class);
$container->provide(Model::class);
$container->provide(AreaControllerBase::class);
$container->provide(AreaCore::class);
$container->provide(AreaModel::class);
$container->provide(PdoDatabase::class);

$provider = $container->resolve(CoresProvider::class);
//$provider = new CoresProvider();
