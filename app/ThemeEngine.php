<?php
/**
 * Astro Theme - Theme Engine
 * Core class for managing theme configuration and rendering.
 * 
 * Symlinked to app/BlueprintFramework/Extensions/astrotheme/
 */

namespace BlueprintFramework\Extensions\astrotheme;

use BlueprintFramework\Services\Blueprint\BlueprintService;

/**
 * Class ThemeEngine
 * 
 * Central engine for the Astro Theme extension.
 * Manages reading/writing settings, generating dynamic CSS,
 * and providing theme data to views.
 */
class ThemeEngine
{
    /** @var BlueprintService */
    protected BlueprintService $blueprint;

    /** @var string Extension identifier */
    protected string $identifier = 'astrotheme';

    /** @var array Default settings */
    protected array $defaults = [
        'enabled'           => '1',
        'dark_mode'         => '0',
        'blur_strength'     => '20',
        'glass_opacity'     => '0.60',
        'animation_speed'   => 'normal',
        'border_radius'     => '24',
        'accent_color_1'    => '#4f7cff',
        'accent_color_2'    => '#7cc2ff',
        'page_bg'           => '#edf3ff',
        'background_opacity'=> '0',
        'sidebar_width'     => '280',
        'compact_mode'      => '0',
        'logo_url'          => '',
        'logo_width'        => '180',
        'logo_height'       => 'auto',
        'login_bg'          => '',
        'dashboard_bg'      => '',
        'footer_text'       => '',
        'copyright_text'    => '',
        'svg_layers'        => '[]',
        'custom_css'        => '',
        'custom_js'         => '',
        'custom_head'       => '',
        'custom_footer'     => '',
        'color_primary'     => '#4f7cff',
        'color_secondary'   => '#7cc2ff',
        'color_success'     => '#34d399',
        'color_warning'     => '#fbbf24',
        'color_danger'      => '#fb7185',
        'color_info'        => '#60a5fa',
        'color_sidebar'     => 'rgba(255,255,255,0.60)',
        'color_background'  => '#edf3ff',
        'color_cards'       => 'rgba(255,255,255,0.60)',
        'color_text'        => '#2b3a67',
        'color_muted'       => '#8ba0d8',
        'color_border'      => 'rgba(255,255,255,0.75)',
        'color_hover'       => 'rgba(79,124,255,0.10)',
        'color_buttons'     => '#4f7cff',
        'color_links'       => '#4f7cff',
        'bg_type'           => 'gradient',
        'bg_color'          => '#edf3ff',
        'bg_gradient'       => '',
        'bg_image'          => '',
        'bg_video'          => '',
        'particles'         => '0',
        'overlay_opacity'   => '0',
        'bg_blur'           => '0',
    ];

    /**
     * Constructor
     */
    public function __construct(BlueprintService $blueprint)
    {
        $this->blueprint = $blueprint;
    }

    /**
     * Check if the theme is enabled
     */
    public function isEnabled(): bool
    {
        $enabled = $this->get('enabled');
        return $enabled === '1' || $enabled === true;
    }

    /**
     * Get a single setting value
     */
    public function get(string $key, $default = null)
    {
        $fallback = $default ?? ($this->defaults[$key] ?? null);
        return $this->blueprint->dbGet($this->identifier, $key, $fallback);
    }

    /**
     * Set a single setting value
     */
    public function set(string $key, $value): void
    {
        $this->blueprint->dbSet($this->identifier, $key, $value);
    }

    /**
     * Get all settings with defaults applied
     */
    public function getAllSettings(): array
    {
        $settings = [];
        foreach ($this->defaults as $key => $default) {
            $settings[$key] = $this->get($key, $default);
        }
        return $settings;
    }

    /**
     * Get settings as CSS custom properties string
     */
    public function getCssVariables(): string
    {
        $settings = $this->getAllSettings();
        $css = ':root {' . "\n";
        
        $css .= '  --astro-accent-1: ' . $this->sanitize($settings['accent_color_1']) . ';' . "\n";
        $css .= '  --astro-accent-2: ' . $this->sanitize($settings['accent_color_2']) . ';' . "\n";
        $css .= '  --astro-accent-grad: linear-gradient(135deg, ' . 
                $this->sanitize($settings['accent_color_1']) . ', ' . 
                $this->sanitize($settings['accent_color_2']) . ');' . "\n";
        $css .= '  --astro-page-bg: ' . $this->sanitize($settings['page_bg']) . ';' . "\n";
        $css .= '  --astro-glass-bg: rgba(255,255,255,' . floatval($settings['glass_opacity']) . ');' . "\n";
        $css .= '  --astro-glass-blur: blur(' . intval($settings['blur_strength']) . 'px) saturate(1.5);' . "\n";
        $css .= '  --astro-radius-card: ' . intval($settings['border_radius']) . 'px;' . "\n";
        $css .= '  --astro-sidebar-width: ' . intval($settings['sidebar_width']) . 'px;' . "\n";
        
        // Custom colors
        $css .= '  --astro-color-primary: ' . $this->sanitize($settings['color_primary']) . ';' . "\n";
        $css .= '  --astro-color-secondary: ' . $this->sanitize($settings['color_secondary']) . ';' . "\n";
        $css .= '  --astro-color-success: ' . $this->sanitize($settings['color_success']) . ';' . "\n";
        $css .= '  --astro-color-warning: ' . $this->sanitize($settings['color_warning']) . ';' . "\n";
        $css .= '  --astro-color-danger: ' . $this->sanitize($settings['color_danger']) . ';' . "\n";
        $css .= '  --astro-color-info: ' . $this->sanitize($settings['color_info']) . ';' . "\n";
        $css .= '  --astro-color-text: ' . $this->sanitize($settings['color_text']) . ';' . "\n";
        $css .= '  --astro-color-muted: ' . $this->sanitize($settings['color_muted']) . ';' . "\n";
        $css .= '  --astro-color-border: ' . $this->sanitize($settings['color_border']) . ';' . "\n";
        
        $css .= '}' . "\n";
        
        return $css;
    }

    /**
     * Get the background CSS based on settings
     */
    public function getBackgroundCss(): string
    {
        $settings = $this->getAllSettings();
        $type = $settings['bg_type'] ?? 'gradient';
        $css = '';
        
        switch ($type) {
            case 'solid':
                $css = 'body{background:' . $this->sanitize($settings['bg_color']) . ' !important;background-image:none !important;}';
                break;
            case 'gradient-custom':
                if (!empty($settings['bg_gradient'])) {
                    $css = 'body{background:' . $settings['bg_gradient'] . ' !important;background-attachment:fixed !important;}';
                }
                break;
            case 'image':
                if (!empty($settings['bg_image'])) {
                    $css = 'body{background:url("' . $this->sanitize($settings['bg_image']) . '") center/cover fixed no-repeat !important;}';
                }
                break;
            case 'video':
                // Video is handled in the wrapper template
                break;
            default:
                // Default gradient - no override needed
                break;
        }
        
        return $css;
    }

    /**
     * Reset all settings to defaults
     */
    public function resetToDefaults(): void
    {
        foreach ($this->defaults as $key => $default) {
            $this->set($key, $default);
        }
    }

    /**
     * Get SVG layers as array
     */
    public function getSvgLayers(): array
    {
        $json = $this->get('svg_layers', '[]');
        return json_decode($json, true) ?: [];
    }

    /**
     * Add an SVG layer
     */
    public function addSvgLayer(array $layer): void
    {
        $layers = $this->getSvgLayers();
        $layer['id'] = uniqid('svg_');
        $layers[] = $layer;
        $this->set('svg_layers', json_encode($layers));
    }

    /**
     * Remove an SVG layer by ID
     */
    public function removeSvgLayer(string $id): void
    {
        $layers = $this->getSvgLayers();
        $layers = array_values(array_filter($layers, fn($l) => ($l['id'] ?? '') !== $id));
        $this->set('svg_layers', json_encode($layers));
    }

    /**
     * Sanitize a value for safe CSS output
     */
    private function sanitize(string $value): string
    {
        // Remove anything that could be used for CSS injection
        return preg_replace('/[^a-zA-Z0-9#.,\s\(\)\-%]/', '', $value);
    }

    /**
     * Get the defaults array
     */
    public function getDefaults(): array
    {
        return $this->defaults;
    }
}
