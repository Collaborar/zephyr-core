<?php

declare(strict_types=1);

namespace ZephyrCore\Core;

use DI\Container;
use Zephyr\Application\Application;
use Zephyr\ServiceProviders\ServiceProviderInterface;

class CoreServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Container $container): void
    {
        $container->set(
            Core::class,
            fn (Container $c) => new Core($c->get(Application::class))
        );

        $app = $container->get(Application::class);
        $app->alias('core', Core::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bootstrap(Container $container): void
    {
        // Nothing to bootstrap.
    }
}
