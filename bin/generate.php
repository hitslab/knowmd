<?php

use App\Service\BuildService;

include (dirname(__FILE__) . '/../src/app.php');

/** @var BuildService $builder */
$builder = $container->get('build_service');

$builder->build();
