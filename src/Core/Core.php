<?php

declare(strict_types=1);

namespace ZephyrCore\Core;

use Zephyr\Application\Application;
use Zephyr\Helpers\MixedType;
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
     * @param string      $path
     * @param string      $i18nPath
     * @param Application $app
     */
    public function __construct(
        protected string $path,
        protected string $i18nPath,
        protected Application $app
    ) {
        $this->path = MixedType::removeTrailingSlash($path);
        $this->i18nPath = MixedType::removeTrailingSlash($i18nPath);
    }

    /**
     * Retrieves the path location for i18n.
     *
     * @return string
     */
    public function i18nPath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->path, $this->i18nPath]);
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
