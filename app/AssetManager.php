<?php
/**
 * Astro Theme - Asset Manager
 * Handles asset paths, caching, and versioning.
 */

namespace BlueprintFramework\Extensions\astrotheme;

/**
 * Class AssetManager
 * 
 * Manages public asset paths and provides versioned URLs
 * for cache-busting when theme updates occur.
 */
class AssetManager
{
    /** @var string Extension identifier */
    protected string $identifier = 'astrotheme';

    /** @var string Extension version */
    protected string $version = '1.0.0';

    /** @var array CSS assets to load */
    protected array $cssAssets = [
        'css/astro-theme.css',
        'css/astro-animations.css',
        'css/astro-responsive.css',
        'css/astro-login.css',
        'css/astro-sidebar.css',
        'css/astro-server-cards.css',
        'css/astro-console.css',
    ];

    /** @var array JS assets to load */
    protected array $jsAssets = [
        'js/astro-theme.js',
    ];

    /**
     * Get the public URL base for extension assets
     */
    public function getAssetBaseUrl(): string
    {
        return '/extensions/' . $this->identifier;
    }

    /**
     * Get a versioned asset URL
     */
    public function asset(string $path): string
    {
        return $this->getAssetBaseUrl() . '/' . ltrim($path, '/') . '?v=' . $this->version;
    }

    /**
     * Get all CSS asset URLs
     */
    public function getCssUrls(): array
    {
        return array_map(fn($css) => $this->asset($css), $this->cssAssets);
    }

    /**
     * Get all JS asset URLs
     */
    public function getJsUrls(): array
    {
        return array_map(fn($js) => $this->asset($js), $this->jsAssets);
    }

    /**
     * Generate HTML link tags for all CSS
     */
    public function renderCssTags(): string
    {
        $tags = '';
        foreach ($this->getCssUrls() as $url) {
            $tags .= '<link rel="stylesheet" href="' . $url . '">' . "\n";
        }
        return $tags;
    }

    /**
     * Generate HTML script tags for all JS
     */
    public function renderJsTags(): string
    {
        $tags = '';
        foreach ($this->getJsUrls() as $url) {
            $tags .= '<script defer src="' . $url . '"></script>' . "\n";
        }
        return $tags;
    }

    /**
     * Get the version string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Get all asset file paths (for build verification)
     */
    public function getAllAssetPaths(): array
    {
        return array_merge($this->cssAssets, $this->jsAssets, [
            'images/icon.png',
            'images/logo-default.svg',
        ]);
    }
}
