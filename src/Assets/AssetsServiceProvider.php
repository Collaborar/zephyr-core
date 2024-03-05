<?php

declare(strict_types=1);

namespace ZephyrCore\Assets;

use DI\Container;
use Zephyr\Filesystem\Filesystem;
use Zephyr\ServiceProviders\ServiceProviderInterface;
use ZephyrCore\Config\Config;

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
            )
        );

        $container->set(
            Assets::class,
            fn (Container $c) => new Assets(
                $c->get(ZEPHYR_CONFIG_KEY)['core']['path'],
                $c->get(ZEPHYR_CONFIG_KEY)['core']['url'],
                $c->get(Config::class),
                $c->get(Manifest::class),
                $c->get(Filesystem::class)
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
