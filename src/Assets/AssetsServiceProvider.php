<?php

declare(strict_types=1);

namespace ZephyrCore\Assets;

use DI\Container;
use Zephyr\ServiceProviders\ServiceProviderInterface;

/**
 * Provide assets dependencies
 */
class AssetsServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Container $container): void
    {
        $container->set(
            Manifest::class,
            fn (Container $c) => new Manifest(
                $c->get(ZEPHYR_CONFIG_KEY)['core']['path'],
                $c->get(ZEPHYR_CONFIG_KEY)['core']['dist'],
                $c->get(ZEPHYR_CONFIG_KEY)['core']['manifest'],
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function bootstrap(Container $container): void
    {
        // Nothing to bootstrap.
    }
}
