<?php

declare(strict_types=1);

namespace ZephyrCore\Core;

use Zephyr\Application\Application;
use ZephyrCore\Assets\Assets;
use ZephyrCore\Config\Config;

/**
 * Main communication channel with the tools.
 */
class Core
{
    /**
     * Constructor.
     *
     * @param Application $app
     */
    public function __construct(
        protected Application $app
    ) {
    }

    /**
     * Shortcut to \ZephyrCore\Assets\Assets
     *
     * @return Assets
     */
    public function assets(): Assets
    {
        return $this->app->resolve(Assets::class);
    }

    /**
     * Shortcut to \ZephyrCore\Config\Config
     *
     * @return Config
     */
    public function config(): Config
    {
        return $this->app->resolve(Config::class);
    }
}
