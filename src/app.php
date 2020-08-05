<?php

use App\Service\AssetsService;
use App\Service\BuildService;
use App\Service\DocsService;
use App\Service\ExtendedParsedown;
use App\Service\FilesService;
use App\Service\SidebarService;
use App\Service\TemplateService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

require __DIR__ . '/../vendor/autoload.php';

$basePath = realpath(dirname(__FILE__) . '/../') . DIRECTORY_SEPARATOR;

$paths = [
    'templates' => $basePath . 'templates',
    'assets' => $basePath . 'assets',
    'docs' => $basePath. 'docs',
    'build' => $basePath . 'build',
];

$container = new ContainerBuilder();

$container
    ->register('files_service', FilesService::class);

$container
    ->register('parser', ExtendedParsedown::class);

$container
    ->register('template_service', TemplateService::class)
    ->addArgument($paths['templates']);

$container
    ->register('assets_service', AssetsService::class)
    ->addArgument(new Reference('files_service'))
    ->addArgument($paths['assets'])
    ->addArgument($paths['build']);

$container
    ->register('sidebar_service', SidebarService::class)
    ->addArgument($paths['docs'])
    ->addArgument($paths['build']);

$container
    ->register('docs_service', DocsService::class)
    ->addArgument(new Reference('files_service'))
    ->addArgument(new Reference('template_service'))
    ->addArgument(new Reference('sidebar_service'))
    ->addArgument(new Reference('parser'))
    ->addArgument($paths['docs'])
    ->addArgument($paths['build']);

$container
    ->register('build_service', BuildService::class)
    ->addArgument(new Reference('assets_service'))
    ->addArgument(new Reference('docs_service'))
    ->addArgument(new Reference('files_service'))
    ->addArgument($paths['build']);
