<?php
// ecs.php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\NormalizeIndexBraceFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;

/**
 * Configure ecs config.
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $parameters = $containerConfigurator->parameters();
    $parameters->set('cache_directory', __DIR__ . '.ecs_cache');

    $parameters->set(
        Option::PATHS, [
            __DIR__ . '/lib',
            // Explicit set committed vendor folder since ecs excludes this by default.
            __DIR__ . '/lib/vendor',
            __DIR__ . '/ecs.php',
            __DIR__ . '/rector.php'
        ]
    );

    $parameters->set(Option::LINE_ENDING, "\n");

    $services->set(NormalizeIndexBraceFixer::class);
};
