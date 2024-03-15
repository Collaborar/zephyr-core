<?php

declare(strict_types=1);

namespace ZephyrCore\Config;

use Zephyr\Helpers\MixedType;
use ZephyrCore\Concerns\ReadsJsonTrait;

/**
 * Access data in config.json
 */
class Config
{
    use ReadsJsonTrait {
        load as traitLoad;
    }

    /**
     * Constructor
     *
     * @param string $path App root path.
     */
    public function __construct(protected string $path)
    {
    }

    /**
     * {@inheritDoc}
     */
    protected function getJsonPath(): string
    {
        return MixedType::normalizePath($this->path.DIRECTORY_SEPARATOR.'config.json');
    }
}
