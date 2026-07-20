<?php
/**
 * Astro Theme - Settings Manager
 * Handles validation, sanitization, and batch operations for settings.
 */

namespace BlueprintFramework\Extensions\astrotheme;

use BlueprintFramework\Services\Blueprint\BlueprintService;

/**
 * Class SettingsManager
 * 
 * Provides validation, sanitization, and batch operations
 * for Astro Theme settings.
 */
class SettingsManager
{
    /** @var BlueprintService */
    protected BlueprintService $blueprint;

    /** @var string */
    protected string $identifier = 'astrotheme';

    /**
     * Validation rules for each setting
     */
    protected array $rules = [
        'enabled'           => ['type' => 'toggle'],
        'dark_mode'         => ['type' => 'toggle'],
        'compact_mode'      => ['type' => 'toggle'],
        'particles'         => ['type' => 'toggle'],
        'blur_strength'     => ['type' => 'integer', 'min' => 0, 'max' => 40],
        'glass_opacity'     => ['type' => 'decimal', 'min' => 0, 'max' => 1],
        'border_radius'     => ['type' => 'integer', 'min' => 0, 'max' => 40],
        'sidebar_width'     => ['type' => 'integer', 'min' => 200, 'max' => 400],
        'overlay_opacity'   => ['type' => 'integer', 'min' => 0, 'max' => 100],
        'bg_blur'           => ['type' => 'integer', 'min' => 0, 'max' => 30],
        'accent_color_1'    => ['type' => 'color'],
        'accent_color_2'    => ['type' => 'color'],
        'page_bg'           => ['type' => 'color'],
        'animation_speed'   => ['type' => 'enum', 'values' => ['none', 'fast', 'normal', 'slow']],
        'bg_type'           => ['type' => 'enum', 'values' => ['gradient', 'solid', 'gradient-custom', 'image', 'video']],
        'bg_color'          => ['type' => 'color'],
        'bg_gradient'       => ['type' => 'string', 'max' => 1000],
        'bg_image'          => ['type' => 'url'],
        'bg_video'          => ['type' => 'url'],
        'logo_url'          => ['type' => 'string', 'max' => 500],
        'logo_width'        => ['type' => 'string', 'max' => 10],
        'logo_height'       => ['type' => 'string', 'max' => 10],
        'login_bg'          => ['type' => 'string', 'max' => 500],
        'dashboard_bg'      => ['type' => 'string', 'max' => 500],
        'footer_text'       => ['type' => 'string', 'max' => 200],
        'copyright_text'    => ['type' => 'string', 'max' => 200],
        'svg_layers'        => ['type' => 'json'],
        'custom_css'        => ['type' => 'code', 'max' => 50000],
        'custom_js'         => ['type' => 'code', 'max' => 50000],
        'custom_head'       => ['type' => 'code', 'max' => 10000],
        'custom_footer'     => ['type' => 'code', 'max' => 10000],
        'color_primary'     => ['type' => 'color'],
        'color_secondary'   => ['type' => 'color'],
        'color_success'     => ['type' => 'color'],
        'color_warning'     => ['type' => 'color'],
        'color_danger'      => ['type' => 'color'],
        'color_info'        => ['type' => 'color'],
        'color_sidebar'     => ['type' => 'string', 'max' => 100],
        'color_background'  => ['type' => 'color'],
        'color_cards'       => ['type' => 'string', 'max' => 100],
        'color_text'        => ['type' => 'color'],
        'color_muted'       => ['type' => 'color'],
        'color_border'      => ['type' => 'string', 'max' => 100],
        'color_hover'       => ['type' => 'string', 'max' => 100],
        'color_buttons'     => ['type' => 'color'],
        'color_links'       => ['type' => 'color'],
    ];

    public function __construct(BlueprintService $blueprint)
    {
        $this->blueprint = $blueprint;
    }

    /**
     * Validate and sanitize a setting value
     */
    public function validate(string $key, $value): ?string
    {
        if (!isset($this->rules[$key])) {
            return null; // Unknown key, skip
        }

        $rule = $this->rules[$key];

        switch ($rule['type']) {
            case 'toggle':
                return in_array($value, ['0', '1', true, false, 0, 1]) ? (string)(int)(bool)$value : null;
            
            case 'integer':
                $intVal = intval($value);
                if (isset($rule['min']) && $intVal < $rule['min']) $intVal = $rule['min'];
                if (isset($rule['max']) && $intVal > $rule['max']) $intVal = $rule['max'];
                return (string)$intVal;
            
            case 'decimal':
                $floatVal = floatval($value);
                if (isset($rule['min']) && $floatVal < $rule['min']) $floatVal = $rule['min'];
                if (isset($rule['max']) && $floatVal > $rule['max']) $floatVal = $rule['max'];
                return number_format($floatVal, 2, '.', '');
            
            case 'color':
                // Accept hex colors or rgba
                if (preg_match('/^#[0-9a-fA-F]{3,8}$/', $value)) {
                    return $value;
                }
                if (preg_match('/^rgba?\(.+\)$/', $value)) {
                    return $value;
                }
                return null;
            
            case 'enum':
                return in_array($value, $rule['values']) ? $value : null;
            
            case 'url':
                if (empty($value)) return '';
                return filter_var($value, FILTER_SANITIZE_URL) ?: null;
            
            case 'json':
                $decoded = json_decode($value, true);
                return $decoded !== null ? $value : null;
            
            case 'code':
                $maxLen = $rule['max'] ?? 50000;
                if (strlen($value) > $maxLen) {
                    $value = substr($value, 0, $maxLen);
                }
                return $value;
            
            case 'string':
            default:
                $maxLen = $rule['max'] ?? 500;
                if (strlen($value) > $maxLen) {
                    $value = substr($value, 0, $maxLen);
                }
                return $value;
        }
    }

    /**
     * Save multiple settings at once with validation
     */
    public function saveBatch(array $settings): array
    {
        $saved = [];
        $errors = [];

        foreach ($settings as $key => $value) {
            $validated = $this->validate($key, $value);
            
            if ($validated !== null) {
                $this->blueprint->dbSet($this->identifier, $key, $validated);
                $saved[] = $key;
            } elseif (isset($this->rules[$key])) {
                $errors[$key] = 'Invalid value';
            }
        }

        return ['saved' => $saved, 'errors' => $errors];
    }

    /**
     * Get validation rules for a specific key
     */
    public function getRule(string $key): ?array
    {
        return $this->rules[$key] ?? null;
    }

    /**
     * Get all validation rules
     */
    public function getAllRules(): array
    {
        return $this->rules;
    }
}
