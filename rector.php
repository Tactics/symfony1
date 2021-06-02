<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php74\Rector\FuncCall\ArrayKeyExistsOnPropertyRector;
use Rector\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector;
use Rector\Php74\Rector\MethodCall\ChangeReflectionTypeToStringToGetNameRector;
use Rector\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector;
use Rector\PSR4\Rector\Namespace_\MultipleClassFileToPsr4ClassesRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    set_include_path(
        __DIR__ . DIRECTORY_SEPARATOR.'lib'.PATH_SEPARATOR.
        __DIR__ . DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'vendor'.PATH_SEPARATOR.
        __DIR__ . DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'propel'.PATH_SEPARATOR.
        __DIR__ . DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'propel-generator'.DIRECTORY_SEPARATOR.'classes'.PATH_SEPARATOR.
        get_include_path()
    );

    $parameters->set(Option::PATHS, [
        __DIR__ . '/lib',
        // Explicit set committed vendor folder since rector excludes this by default.
        __DIR__ . '/lib/vendor',
    ]);

    // get services (needed for register a single rule)
    $services = $containerConfigurator->services();

    // PSR-4
    // register a single rule
    $services->set(MultipleClassFileToPsr4ClassesRector::class);
    $services->set(NormalizeNamespaceByPSR4ComposerAutoloadRector::class);

    // PHP 7.4
    //$services->set(ArrayKeyExistsOnPropertyRector::class);
    //$services->set(ArraySpreadInsteadOfArrayMergeRector::class);
    //$services->set(ChangeReflectionTypeToStringToGetNameRector::class);

};


