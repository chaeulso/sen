{{--
  Astro Theme - Dashboard Wrapper
  Injects theme CSS, JS, and SVG layers into the Pterodactyl dashboard.
  This file is placed at resources/views/blueprint/dashboard/wrappers/astrotheme.blade.php
--}}

@php
  // Safely check if blueprint service exists
  $blueprintAvailable = app()->bound('blueprint');
  
  if (!$blueprintAvailable) {
    // Blueprint service not available, skip theme loading
    return;
  }
  
  $astroEnabled = app('blueprint')->dbGet('astrotheme', 'enabled', '1');
  if ($astroEnabled !== '1' && $astroEnabled !== true) return;

  // Read all settings
  $accent1       = app('blueprint')->dbGet('astrotheme', 'accent_color_1', '#4f7cff');
  $accent2       = app('blueprint')->dbGet('astrotheme', 'accent_color_2', '#7cc2ff');
  $pageBg        = app('blueprint')->dbGet('astrotheme', 'page_bg', '#edf3ff');
  $glassOpacity  = app('blueprint')->dbGet('astrotheme', 'glass_opacity', '0.60');
  $blurStrength  = app('blueprint')->dbGet('astrotheme', 'blur_strength', '20');
  $radiusCard    = app('blueprint')->dbGet('astrotheme', 'border_radius', '24');
  $animSpeed     = app('blueprint')->dbGet('astrotheme', 'animation_speed', 'normal');
  $sidebarWidth  = app('blueprint')->dbGet('astrotheme', 'sidebar_width', '280');
  $compactMode   = app('blueprint')->dbGet('astrotheme', 'compact_mode', '0');
  $darkMode      = app('blueprint')->dbGet('astrotheme', 'dark_mode', '0');
  $fontDisplay   = 'Outfit';
  $fontBody      = 'Inter';
  $fontMono      = 'JetBrains Mono';

  // Background settings
  $bgType        = app('blueprint')->dbGet('astrotheme', 'bg_type', 'gradient');
  $bgImage       = app('blueprint')->dbGet('astrotheme', 'bg_image', '');
  $bgVideo       = app('blueprint')->dbGet('astrotheme', 'bg_video', '');
  $bgGradient    = app('blueprint')->dbGet('astrotheme', 'bg_gradient', '');
  $bgColor       = app('blueprint')->dbGet('astrotheme', 'bg_color', '#edf3ff');
  $particlesOn   = app('blueprint')->dbGet('astrotheme', 'particles', '0');
  $overlayOpacity= app('blueprint')->dbGet('astrotheme', 'overlay_opacity', '0');
  $bgBlur        = app('blueprint')->dbGet('astrotheme', 'bg_blur', '0');

  // Custom colors
  $colorPrimary   = app('blueprint')->dbGet('astrotheme', 'color_primary', $accent1);
  $colorSecondary = app('blueprint')->dbGet('astrotheme', 'color_secondary', $accent2);
  $colorSuccess   = app('blueprint')->dbGet('astrotheme', 'color_success', '#34d399');
  $colorWarning   = app('blueprint')->dbGet('astrotheme', 'color_warning', '#fbbf24');
  $colorDanger    = app('blueprint')->dbGet('astrotheme', 'color_danger', '#fb7185');
  $colorInfo      = app('blueprint')->dbGet('astrotheme', 'color_info', '#60a5fa');
  $colorText      = app('blueprint')->dbGet('astrotheme', 'color_text', '#2b3a67');
  $colorMuted     = app('blueprint')->dbGet('astrotheme', 'color_muted', '#8ba0d8');
  $colorBorder    = app('blueprint')->dbGet('astrotheme', 'color_border', 'rgba(255,255,255,0.75)');

  // SVG layers
  $svgLayersJson = app('blueprint')->dbGet('astrotheme', 'svg_layers', '[]');

  // Custom code
  $customHead    = app('blueprint')->dbGet('astrotheme', 'custom_head', '');
  $customFooter  = app('blueprint')->dbGet('astrotheme', 'custom_footer', '');
  $customCSS     = app('blueprint')->dbGet('astrotheme', 'custom_css', '');
  $customJS      = app('blueprint')->dbGet('astrotheme', 'custom_js', '');

  // Login branding
  $logoUrl       = app('blueprint')->dbGet('astrotheme', 'logo_url', '');
  $footerText    = app('blueprint')->dbGet('astrotheme', 'footer_text', '');
  $copyrightText = app('blueprint')->dbGet('astrotheme', 'copyright_text', '');

  // Compute glass bg from opacity
  $glassBgHex = 'rgba(255,255,255,' . $glassOpacity . ')';

  // Animation speed modifier class
  $speedClass = 'astro-speed-' . $animSpeed;

  // Dark mode class
  $darkClass = $darkMode === '1' || $darkMode === true ? 'astro-dark' : '';

  // Compact mode class
  $compactClass = $compactMode === '1' || $compactMode === true ? 'astro-compact' : '';

  // Saturation for blur
  $blurValue = 'blur(' . $blurStrength . 'px) saturate(1.5)';
@endphp

{{-- Custom Head HTML --}}
{!! $customHead !!}

<style>
/* Astro Theme - Dynamic CSS Variables */
:root {
  --astro-accent-1: {{ $accent1 }};
  --astro-accent-2: {{ $accent2 }};
  --astro-accent-grad: linear-gradient(135deg, {{ $accent1 }}, {{ $accent2 }});
  --astro-page-bg: {{ $pageBg }};
  --astro-glass-bg: {{ $glassBgHex }};
  --astro-glass-blur: {{ $blurValue }};
  --astro-radius-card: {{ $radiusCard }}px;
  --astro-sidebar-width: {{ $sidebarWidth }}px;
  --astro-color-primary: {{ $colorPrimary }};
  --astro-color-secondary: {{ $colorSecondary }};
  --astro-color-success: {{ $colorSuccess }};
  --astro-color-warning: {{ $colorWarning }};
  --astro-color-danger: {{ $colorDanger }};
  --astro-color-info: {{ $colorInfo }};
  --astro-color-text: {{ $colorText }};
  --astro-color-muted: {{ $colorMuted }};
  --astro-color-border: {{ $colorBorder }};
}

/* Background Type */
@if($bgType === 'solid')
  body, .astro-page-bg {
    background: {{ $bgColor }} !important;
    background-image: none !important;
  }
@elseif($bgType === 'gradient' && $bgGradient)
  body, .astro-page-bg {
    background: {{ $bgGradient }} !important;
    background-attachment: fixed !important;
  }
@elseif($bgType === 'image' && $bgImage)
  body, .astro-page-bg {
    background: url('{{ $bgImage }}') center/cover fixed no-repeat !important;
  }
@endif

/* Overlay */
@if($overlayOpacity > 0)
  .astro-bg-overlay {
    position: fixed;
    inset: 0;
    z-index: 0;
    background: rgba(0,0,0,{{ $overlayOpacity }});
    pointer-events: none;
  }
@endif

/* Background Blur */
@if($bgBlur > 0)
  body {
    -webkit-backdrop-filter: blur({{ $bgBlur }}px);
    backdrop-filter: blur({{ $bgBlur }}px);
  }
@endif

/* Custom CSS */
{!! $customCSS !!}
</style>

{{-- SVG Layers --}}
@php
  $svgLayers = json_decode($svgLayersJson, true) ?: [];
@endphp

@foreach($svgLayers as $layer)
  @if(!empty($layer['enabled']) || ($layer['enabled'] ?? true))
    <div style="
      position: fixed;
      {{ $layer['position'] ?? 'top' }}: 0;
      {{ $layer['position'] ?? 'top' === 'left' ? 'left: 0;' : ($layer['position'] ?? 'top' === 'right' ? 'right: 0;' : 'left: 50%; transform: translateX(-50%);') }}
      z-index: {{ $layer['zindex'] ?? 0 }};
      pointer-events: none;
      opacity: {{ $layer['opacity'] ?? 1 }};
      {{ !empty($layer['color']) ? 'color: ' . $layer['color'] . ';' : '' }}
      {{ !empty($layer['scale']) ? 'transform: scale(' . $layer['scale'] . ');' : '' }}
    " class="{{ !empty($layer['animate']) ? 'astro-float' : '' }}">
      @if(!empty($layer['url']))
        <img src="{{ $layer['url'] }}" alt="" style="max-width: {{ $layer['width'] ?? '300px' }}; height: auto;">
      @elseif(!empty($layer['svg']))
        {!! $layer['svg'] !!}
      @endif
    </div>
  @endif
@endforeach

{{-- Particles Background --}}
@if($particlesOn === '1' || $particlesOn === true)
<div class="astro-particles-bg" id="astro-particles"></div>
@endif

{{-- Video Background --}}
@if($bgType === 'video' && $bgVideo)
<div style="position:fixed;inset:0;z-index:0;overflow:hidden;pointer-events:none;">
  <video autoplay muted loop playsinline style="width:100%;height:100%;object-fit:cover;">
    <source src="{{ $bgVideo }}" type="video/mp4">
  </video>
</div>
@endif

{{-- Load Astro Theme Assets --}}
<link rel="stylesheet" href="/extensions/astrotheme/css/astro-theme.css">
<link rel="stylesheet" href="/extensions/astrotheme/css/astro-animations.css">
<link rel="stylesheet" href="/extensions/astrotheme/css/astro-responsive.css">
<link rel="stylesheet" href="/extensions/astrotheme/css/astro-login.css">
<link rel="stylesheet" href="/extensions/astrotheme/css/astro-sidebar.css">
<link rel="stylesheet" href="/extensions/astrotheme/css/astro-server-cards.css">
<link rel="stylesheet" href="/extensions/astrotheme/css/astro-console.css">
<link rel="stylesheet" href="/extensions/astrotheme/css/astro-components.css">

<script>
  // Astro Theme Configuration
  window.__ASTRO_THEME__ = {
    enabled: true,
    darkMode: {{ $darkMode === '1' || $darkMode === true ? 'true' : 'false' }},
    compactMode: {{ $compactMode === '1' || $compactMode === true ? 'true' : 'false' }},
    particles: {{ $particlesOn === '1' || $particlesOn === true ? 'true' : 'false' }},
    accentColor1: @json($accent1),
    accentColor2: @json($accent2),
    logoUrl: @json($logoUrl),
    footerText: @json($footerText),
    copyrightText: @json($copyrightText)
  };
</script>

<script defer src="/extensions/astrotheme/js/astro-theme.js"></script>

{{-- Custom Footer JS --}}
@if($customJS)
<script>{!! $customJS !!}</script>
@endif

{{-- Custom Footer HTML --}}
{!! $customFooter !!}
