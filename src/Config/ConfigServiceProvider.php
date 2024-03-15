<?php

declare(strict_types=1);

namespace ZephyrCore\Config;

use DI\Container;
use Zephyr\ServiceProviders\ServiceProviderInterface;

/**
 * Provide app dependencies.
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Container $container): void
    {
        $container->set(
            Config::class,
            fn (Container $c) => new Config(
                $c->get(ZEPHYR_CONFIG_KEY)['core']['path'],
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
