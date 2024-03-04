<?php

declare(strict_types=1);

namespace ZephyrCore\Assets;

use Zephyr\Helpers\{ MixedType, Url };

/**
 * Assets
 */
class Assets
{
    /**
     * Constructor.
     *
     * @param string              $path App root path.
     * @param string              $url  App root URL.
     * @param Config              $config Plugin/Theme config (config.json | theme.json)
     * @param Manifest            $manifest Manifest
     * @param \WP_Filesystem_Base $filesystem Filesystem
     */
    public function __construct(
        protected string $path,
        protected string $url,
        protected Config $config,
        protected Manifest $manifest,
        protected \WP_Filesystem_Base $filesystem
    ) {
        $this->path = MixedType::removeTrailingSlash($path);
        $this->url = Url::removeTrailingSlash($url);
    }

    /**
     * Remove the protocol from an http/https url.
     *
     * @param  string $url
     *
     * @return string
     */
    protected function removeProtocol(string $url): string
    {
        return preg_replace('~^https?:~i', '', $url);
    }

    /**
     * Get if a url is external or not.
     *
     * @param  string  $url
     * @param  string  $homeUrl
     *
     * @return bool
     */
    protected function isExternalUrl(string $url, string $homeUrl): bool
    {
        $delimiter = '~';
        $patternHomeUrl = preg_quote($homeUrl, $delimiter);
        $pattern = $delimiter.'^'.$patternHomeUrl.$delimiter.'i';

        return !preg_match($pattern, $url);
    }

    /**
     * Generate a version for a given asset src.
     *
     * @param  string          $src
     *
     * @return int|bool
     */
    protected function generateFileVersion(string $src): int|bool
    {
        // Normalize both URLs in order to avoid problems with http, https
        // and protocol-less cases.
        $src = $this->removeProtocol($src);
        $homeUrl = $this->removeProtocol(WP_CONTENT_URL);
        $version = false;

        if (!$this->isExternalUrl($src, $homeUrl)) {
            // Generate the absolute path to the file.
            $filePath = MixedType::normalizePath(str_replace(
                [$homeUrl, '/'],
                [WP_CONTENT_DIR, DIRECTORY_SEPARATOR],
                $src
            ));

            if ($this->filesystem->exists($filePath)) {
                // Use the last modified time of the file as a version.
                $version = $this->filesystem->mtime($filePath);
            }
        }

        return $version;
    }

    /**
     * Get the public URL to the app root.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get the public URL to a generated asset based on manifest.json.
     *
     * @param string $asset
     *
     * @return string
     */
    public function getAssetUrl(string $asset): string
    {
        // Path with unix-style slashes.
        $path = $this->manifest->get($asset, '');

        if (!$path) {
            return '';
        }

        $url = \wp_parse_url($path);

        if (isset($url['scheme'])) {
            // Path is an absolute URL.
            return $path;
        }

        // Path is relative.
        return $this->getUrl().'/dist/'.$path;
    }

    /**
     * Get the public URL to a generated JS or CSS bundle.
     * Handles SCRIPT_DEBUG and hot reloading.
     *
     * @param string  $name Source basename (no extension).
     * @param string  $extension Source extension - '.js' or '.css'.
     *
     * @return string
     */
    public function getBundleUrl(string $name, string $extension): string
    {
        $development = implode(DIRECTORY_SEPARATOR, [$this->path, 'dist', 'development.json']);
        $isDevelopment = $this->filesystem->exists($development);
        $isHot = false;
        $isDebug = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG;

        if ($isDevelopment) {
            $json = json_decode($this->filesystem->get_contents($development));
            $isHot = $json->hot;
        }

        $urlPath = '.css' === $extension ? "styles/{$name}" : $name;
        $suffix = $isDevelopment || $isDebug ? '' : '.min';

        if ($isHot) {
            $hotUrl = wp_parse_url($this->config->get('development.hotUrl', 'http://localhost/'));
            $hotPort = $this->config->get('development.port', 3000);

            return "${hotUrl['scheme']}://{$hotUrl['host']}:{$hotPort}/{$urlPath}{$suffix}{$extension}";
        }

        return "{$this->getUrl()}/dist/{$urlPath}{$suffix}{$extension}";
    }

    /**
     * Enqueue a style, dynamically generating a version for it.
     *
     * @param  string        $handle
     * @param  string        $src
     * @param  array<string> $dependencies
     * @param  string        $media
     *
     * @return void
     */
    public function enqueueStyle(string $handle, string $src, array $dependencies = [], string $media = 'all' ): void
    {
        \wp_enqueue_style($handle, $src, $dependencies, $this->generateFileVersion($src), $media);
    }

    /**
     * Enqueue a script, dynamically generating a version for it.
     *
     * @param  string        $handle
     * @param  string        $src
     * @param  array<string> $dependencies
     * @param  boolean       $in_footer
     *
     * @return void
     */
    public function enqueueScript(string $handle, string $src, array $dependencies = [], bool $in_footer = false ): void
    {
        \wp_enqueue_script($handle, $src, $dependencies, $this->generateFileVersion($src), $in_footer);
    }

    /**
     * Add favicon meta.
     *
     * @return void
     */
    public function addFavicon(): void
    {
        if (function_exists('has_site_icon') && has_site_icon()) {
            // allow users to override the favicon using the WordPress Customizer
            return;
        }

        $faviconUrl = apply_filters('zephyr_core_favicon_url', $this->getAssetUrl('images/favicon.ico'));

        echo '<link rel="shortcut icon" href="'.$faviconUrl.'" />'."\n";
    }
}
