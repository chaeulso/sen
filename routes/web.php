<?php
/**
 * Astro Theme - Web Routes
 * Custom routes for the extension settings API
 * Gets copied to routes/blueprint/web/astrotheme.php
 */

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Astro Theme Extension Routes
|--------------------------------------------------------------------------
|
| These routes are prefixed with /extensions/astrotheme/
| They handle AJAX settings operations for the live preview
| and other dynamic functionality.
|
*/

Route::middleware(['web', 'auth'])->group(function () {
    
    /**
     * Get current theme settings as JSON (for live preview AJAX)
     */
    Route::get('/api/settings', function () {
        $blueprint = app('blueprint');
        
        $settings = [
            'enabled'          => $blueprint->dbGet('astrotheme', 'enabled', '1'),
            'accent_color_1'   => $blueprint->dbGet('astrotheme', 'accent_color_1', '#4f7cff'),
            'accent_color_2'   => $blueprint->dbGet('astrotheme', 'accent_color_2', '#7cc2ff'),
            'dark_mode'        => $blueprint->dbGet('astrotheme', 'dark_mode', '0'),
            'blur_strength'    => $blueprint->dbGet('astrotheme', 'blur_strength', '20'),
            'glass_opacity'    => $blueprint->dbGet('astrotheme', 'glass_opacity', '0.60'),
            'border_radius'    => $blueprint->dbGet('astrotheme', 'border_radius', '24'),
            'animation_speed'  => $blueprint->dbGet('astrotheme', 'animation_speed', 'normal'),
            'sidebar_width'    => $blueprint->dbGet('astrotheme', 'sidebar_width', '280'),
            'compact_mode'     => $blueprint->dbGet('astrotheme', 'compact_mode', '0'),
            'particles'        => $blueprint->dbGet('astrotheme', 'particles', '0'),
            'bg_type'          => $blueprint->dbGet('astrotheme', 'bg_type', 'gradient'),
            'bg_color'         => $blueprint->dbGet('astrotheme', 'bg_color', '#edf3ff'),
            'bg_gradient'      => $blueprint->dbGet('astrotheme', 'bg_gradient', ''),
            'bg_image'         => $blueprint->dbGet('astrotheme', 'bg_image', ''),
            'overlay_opacity'  => $blueprint->dbGet('astrotheme', 'overlay_opacity', '0'),
            'logo_url'         => $blueprint->dbGet('astrotheme', 'logo_url', ''),
        ];
        
        return response()->json($settings);
    })->name('extensions.astrotheme.api.settings');
    
    /**
     * Save a single setting via AJAX (for live preview save)
     */
    Route::post('/api/settings/save', function (\Illuminate\Http\Request $request) {
        $user = $request->user();
        if (!$user || !$user->root_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $blueprint = app('blueprint');
        $key = $request->input('key');
        $value = $request->input('value');
        
        // Whitelist allowed keys
        $allowed = [
            'accent_color_1', 'accent_color_2', 'dark_mode', 'blur_strength',
            'glass_opacity', 'border_radius', 'animation_speed', 'sidebar_width',
            'compact_mode', 'particles', 'bg_type', 'bg_color', 'bg_gradient',
            'bg_image', 'overlay_opacity', 'enabled',
        ];
        
        if (!in_array($key, $allowed)) {
            return response()->json(['error' => 'Invalid setting key'], 400);
        }
        
        $blueprint->dbSet('astrotheme', $key, $value);
        
        return response()->json(['success' => true]);
    })->name('extensions.astrotheme.api.save');
});
