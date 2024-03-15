<?php

declare(strict_types=1);

namespace ZephyrCore\Concerns;

use Zephyr\Support\Arr;

/**
 * Allow read a JSON file.
 */
trait ReadsJsonTrait
{
    /**
     * Cache.
     *
     * @var array|null
     */
    protected ?array $cache = null;

    /**
     * Retrieves the path to the JSON that should be read.
     *
     * @return string
     */
    abstract protected function getJsonPath(): string;

    /**
     * Load the JSON file.
     *
     * @param string $file
     *
     * @return array
     */
    protected function load(string $file): array
    {
        /** @var \WP_Filesystem_Base $wp_filesystem */
        // phpcs:ignore
        global $wp_filesystem;

        require_once ABSPATH.'/wp-admin/includes/file.php';

        WP_Filesystem();

        // phpcs:ignore
        if (!$wp_filesystem->exists($file)) {
            throw new JsonFileNotFoundException(sprintf(
                'The required %s file is missing.',
                basename($file)
            ));
        }

        // phpcs:ignore
        $content = $wp_filesystem->get_contents($file);
        $json = json_decode($content, true);
        $jsonError = json_last_error();

        if (JSON_ERROR_NONE !== $jsonError) {
            throw new JsonFileInvalidException(sprintf(
                'The required %s file is not a valid JSON (error code %s).',
                basename($file),
                $jsonError
            ));
        }

        return $json;
    }

    /**
     * Retrieves the entire JSON array.
     *
     * @return array
     */
    protected function getAll(): array
    {
        if (null === $this->cache) {
            $this->cache = $this->load($this->getJsonPath());
        }

        return $this->cache;
    }

    /**
     * Retrieves a JSON value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->getAll(), $key, $default);
    }
}
