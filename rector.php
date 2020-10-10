<?php
// rector.php

declare(strict_types=1);

use Kinderopvang\Core\KinderopvangKernel;
use Kinderopvang\Core\Bridge\LegacyKernel;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {

    // Both Kernels are bootstrapped to handle autoloading
    // this same principal is used in batch tasks
    // since we don't pass our front controller.
    $env = 'dev';

    define('SF_ROOT_DIR', realpath(__DIR__));
    define('SF_APP', 'admin');
    define('SF_ENVIRONMENT', $env);
    define('SF_DEBUG', TRUE);

    // Bootstrap Kernel
    $kernel = new KinderopvangKernel($env, TRUE);
    $kernel->boot();

    // Bootstrap LegacyKernel
    $legacyKernel = new LegacyKernel();
    $legacyKernel->boot($kernel->getContainer());
};
