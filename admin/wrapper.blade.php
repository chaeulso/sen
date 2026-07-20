{{--
  Astro Theme - Admin Wrapper
  Injects admin-specific CSS variables and assets.
  Gets copied to resources/views/blueprint/admin/wrappers/astrotheme.blade.php
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

  $accent1 = app('blueprint')->dbGet('astrotheme', 'accent_color_1', '#4f7cff');
  $accent2 = app('blueprint')->dbGet('astrotheme', 'accent_color_2', '#7cc2ff');
  $darkMode = app('blueprint')->dbGet('astrotheme', 'dark_mode', '0');
  $darkClass = ($darkMode === '1' || $darkMode === true) ? 'astro-dark' : '';
@endphp

<style>
:root {
  --astro-accent-1: {{ $accent1 }};
  --astro-accent-2: {{ $accent2 }};
  --astro-accent-grad: linear-gradient(135deg, {{ $accent1 }}, {{ $accent2 }});
}

@verbatim
/* Admin panel astro overrides */
.astro-admin-active {
  font-family: 'Inter', system-ui, sans-serif;
}

.astro-admin-active .content-header > section > h1,
.astro-admin-active .content-header > section > h3 {
  font-family: 'Outfit', 'Inter', sans-serif;
}

.astro-admin-active .box,
.astro-admin-active .box-body,
.astro-admin-active .box-header {
  border-radius: 12px !important;
}

.astro-admin-active .btn-primary {
  background-image: linear-gradient(135deg, var(--astro-accent-1), var(--astro-accent-2)) !important;
  border: none !important;
  border-radius: 8px !important;
}

.astro-admin-active .btn-primary:hover {
  filter: brightness(1.08);
}
@endverbatim
</style>
