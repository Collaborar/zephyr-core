<?php

declare(strict_types=1);

namespace ZephyrCore\Assets;

use Zephyr\Helpers\MixedType;
use ZephyrCore\Concerns\JsonFileNotFoundException;
use ZephyrCore\Concerns\ReadsJsonTrait;

/**
 * Manifest
 */
class Manifest
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
        return MixedType::normalizePath($this->path.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'manifest.json');
    }

    /**
     * {@inheritDoc}
     */
    protected function load(string $file): array
    {
        try {
            return $this->traitLoad($file);
        } catch (JsonFileNotFoundException) {
            // We used to throw an exception here but it just causes confusion for new users.
        }

        return [];
    }
}
