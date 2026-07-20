<?php
/**
 * Astro Theme - Admin Controller
 * Handles settings page rendering and updates.
 * Namespace: Pterodactyl\Http\Controllers\Admin\Extensions\astrotheme
 */

namespace Pterodactyl\Http\Controllers\Admin\Extensions\astrotheme;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\Factory;
use Pterodactyl\Http\Controllers\Admin\AdminController;
use Pterodactyl\Http\Requests\Admin\AdminFormRequest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use BlueprintFramework\Services\Blueprint\BlueprintService;

class astrothemeExtensionController extends AdminController
{
    /** @var BlueprintService */
    protected BlueprintService $blueprint;

    /** @var Factory */
    protected Factory $view;

    /** Default settings values */
    private const DEFAULTS = [
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

    public function __construct(BlueprintService $blueprint, Factory $view)
    {
        $this->blueprint = $blueprint;
        $this->view = $view;
    }

    /**
     * Display the settings page (GET /admin/extensions/astrotheme)
     */
    public function index(Request $request)
    {
        $this->assertRootAdmin($request);

        $settings = [];
        foreach (self::DEFAULTS as $key => $default) {
            $settings[$key] = $this->blueprint->dbGet('astrotheme', $key, $default);
        }

        return $this->view->make('admin.extensions.astrotheme.index', [
            'settings'  => $settings,
            'defaults'  => self::DEFAULTS,
            'root'      => '/admin/extensions/astrotheme',
        ]);
    }

    /**
     * Update settings (PATCH /admin/extensions/astrotheme)
     */
    public function update(Request $request): RedirectResponse
    {
        $this->assertRootAdmin($request);

        $identifier = 'astrotheme';
        $allKeys = array_keys(self::DEFAULTS);

        foreach ($allKeys as $key) {
            if ($request->has($key)) {
                $value = $request->input($key);
                
                // Handle file uploads for images
                if (in_array($key, ['logo_url', 'login_bg', 'dashboard_bg', 'bg_image']) && $request->hasFile($key)) {
                    $file = $request->file($key);
                    $path = $file->store('astro-theme', 'public');
                    $value = '/storage/' . $path;
                }

                $this->blueprint->dbSet($identifier, $key, $value);
            }
        }

        return redirect()
            ->route('admin.extensions.' . $identifier . '.index')
            ->with('success', 'Astro Theme settings updated successfully.');
    }

    /**
     * POST handler for specific actions (save SVG, upload, etc.)
     */
    public function post(Request $request): RedirectResponse
    {
        $this->assertRootAdmin($request);

        $action = $request->input('action', 'save');

        if ($action === 'reset') {
            $this->resetToDefaults();
            return redirect()
                ->route('admin.extensions.astrotheme.index')
                ->with('success', 'Astro Theme settings reset to defaults.');
        }

        if ($action === 'upload_svg') {
            if ($request->hasFile('svg_file')) {
                $file = $request->file('svg_file');
                $svgContent = file_get_contents($file->getRealPath());
                $layers = json_decode($this->blueprint->dbGet('astrotheme', 'svg_layers', '[]'), true) ?: [];
                $layers[] = [
                    'id' => uniqid('svg_'),
                    'svg' => $svgContent,
                    'position' => 'center',
                    'opacity' => 1,
                    'scale' => 1,
                    'color' => '',
                    'animate' => false,
                    'enabled' => true,
                    'width' => '300px',
                ];
                $this->blueprint->dbSet('astrotheme', 'svg_layers', json_encode($layers));
            }
        }

        return redirect()
            ->route('admin.extensions.astrotheme.index')
            ->with('success', 'Action completed successfully.');
    }

    /**
     * DELETE handler for removing SVG layers
     */
    public function delete(Request $request, string $target, string $id): RedirectResponse
    {
        $this->assertRootAdmin($request);

        if ($target === 'svg_layer') {
            $layers = json_decode($this->blueprint->dbGet('astrotheme', 'svg_layers', '[]'), true) ?: [];
            $layers = array_values(array_filter($layers, fn($l) => ($l['id'] ?? '') !== $id));
            $this->blueprint->dbSet('astrotheme', 'svg_layers', json_encode($layers));
        }

        return redirect()
            ->route('admin.extensions.astrotheme.index')
            ->with('success', 'Item removed successfully.');
    }

    /**
     * Reset all settings to defaults
     */
    private function resetToDefaults(): void
    {
        foreach (self::DEFAULTS as $key => $default) {
            $this->blueprint->dbSet('astrotheme', $key, $default);
        }
    }

    /**
     * Ensure the requesting user is a root admin
     */
    private function assertRootAdmin(Request $request): void
    {
        if (!$request->user() || !$request->user()->root_admin) {
            throw new AccessDeniedHttpException();
        }
    }
}
