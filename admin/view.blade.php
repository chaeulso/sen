{{--
  Astro Theme - Admin Settings Page
  Main Blade template for the extension settings.
  Blueprint wraps this with its own admin template automatically.
  DO NOT add @extends or @section — Blueprint handles the layout.
--}}

<div class="astro-admin-settings" id="astro-settings-app">
  {{-- Header --}}
  <div class="astro-admin-header">
    <div>
      <h2 class="astro-admin-header-title">Astro Theme Settings</h2>
      <p style="color: var(--astro-text-muted); font-size: 0.875rem; margin-top: 0.25rem;">
        Version 1.0.0 · Glassmorphism UI for Pterodactyl Panel
      </p>
    </div>
    <div class="astro-admin-header-version">v1.0.0</div>
  </div>

  {{-- Success/Error Messages --}}
  @if(session('success'))
    <div class="alert alert-success" style="border-radius:12px;margin-bottom:1.5rem;">
      {{ session('success') }}
    </div>
  @endif

  {{-- Tabs --}}
  <div class="astro-admin-tabs" role="tablist">
    <button class="astro-admin-tab active" data-tab="general" type="button">General</button>
    <button class="astro-admin-tab" data-tab="branding" type="button">Branding</button>
    <button class="astro-admin-tab" data-tab="colors" type="button">Colors</button>
    <button class="astro-admin-tab" data-tab="background" type="button">Background</button>
    <button class="astro-admin-tab" data-tab="svg" type="button">SVG Customizer</button>
    <button class="astro-admin-tab" data-tab="custom-code" type="button">Custom Code</button>
    <button class="astro-admin-tab" data-tab="preview" type="button">Live Preview</button>
  </div>

  {{-- Settings Form --}}
  <form method="POST" action="{{ $root }}" enctype="multipart/form-data" id="astro-settings-form">
    @method('PATCH')
    @csrf

    {{-- ============================================ --}}
    {{-- TAB: GENERAL --}}
    {{-- ============================================ --}}
    <div class="astro-tab-content" id="tab-general">
      <div class="astro-admin-section">
        <h3 class="astro-admin-section-title">Theme Settings</h3>
        <p class="astro-admin-section-desc">Control the core appearance and behavior of Astro Theme.</p>

        {{-- Enable Theme --}}
        <div class="astro-admin-toggle">
          <div class="astro-admin-toggle-info">
            <span class="astro-admin-toggle-label">Enable Theme</span>
            <span class="astro-admin-toggle-desc">Toggle the Astro Theme on/off</span>
          </div>
          <div class="astro-switch-track {{ ($settings['enabled'] ?? '1') === '1' ? '' : '' }}" 
               data-on="{{ ($settings['enabled'] ?? '1') === '1' ? 'true' : 'false' }}" 
               onclick="toggleSwitch(this, 'enabled')">
            <div class="astro-switch-thumb"></div>
          </div>
          <input type="hidden" name="enabled" id="field-enabled" value="{{ $settings['enabled'] ?? '1' }}">
        </div>

        {{-- Dark Mode --}}
        <div class="astro-admin-toggle" style="margin-top: 0.75rem;">
          <div class="astro-admin-toggle-info">
            <span class="astro-admin-toggle-label">Dark Mode</span>
            <span class="astro-admin-toggle-desc">Enable dark color scheme</span>
          </div>
          <div class="astro-switch-track" 
               data-on="{{ ($settings['dark_mode'] ?? '0') === '1' ? 'true' : 'false' }}" 
               onclick="toggleSwitch(this, 'dark_mode')">
            <div class="astro-switch-thumb"></div>
          </div>
          <input type="hidden" name="dark_mode" id="field-dark_mode" value="{{ $settings['dark_mode'] ?? '0' }}">
        </div>

        {{-- Blur Strength --}}
        <div class="astro-admin-field" style="margin-top: 1.25rem;">
          <label class="astro-admin-field-label">Blur Strength: <span id="blur-val">{{ $settings['blur_strength'] ?? '20' }}</span>px</label>
          <div class="astro-admin-range-field">
            <input type="range" name="blur_strength" class="astro-slider astro-admin-range-slider"
                   min="0" max="40" value="{{ $settings['blur_strength'] ?? '20' }}"
                   oninput="updateRange(this, 'blur-val')"
                   data-preview="blur">
          </div>
        </div>

        {{-- Glass Opacity --}}
        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Glass Opacity: <span id="opacity-val">{{ $settings['glass_opacity'] ?? '0.60' }}</span></label>
          <div class="astro-admin-range-field">
            <input type="range" name="glass_opacity" class="astro-slider astro-admin-range-slider"
                   min="0" max="100" value="{{ intval(($settings['glass_opacity'] ?? 0.60) * 100) }}"
                   oninput="updateRangeDecimal(this, 'opacity-val')"
                   data-preview="opacity">
          </div>
        </div>

        {{-- Animation Speed --}}
        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Animation Speed</label>
          <select name="animation_speed" class="astro-input" data-preview="speed" style="max-width: 200px;">
            <option value="none" {{ ($settings['animation_speed'] ?? '') === 'none' ? 'selected' : '' }}>None (Disable)</option>
            <option value="fast" {{ ($settings['animation_speed'] ?? '') === 'fast' ? 'selected' : '' }}>Fast</option>
            <option value="normal" {{ ($settings['animation_speed'] ?? 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
            <option value="slow" {{ ($settings['animation_speed'] ?? '') === 'slow' ? 'selected' : '' }}>Slow</option>
          </select>
        </div>

        {{-- Border Radius --}}
        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Border Radius: <span id="radius-val">{{ $settings['border_radius'] ?? '24' }}</span>px</label>
          <div class="astro-admin-range-field">
            <input type="range" name="border_radius" class="astro-slider astro-admin-range-slider"
                   min="0" max="40" value="{{ $settings['border_radius'] ?? '24' }}"
                   oninput="updateRange(this, 'radius-val')"
                   data-preview="radius">
          </div>
        </div>

        {{-- Accent Color --}}
        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Accent Color 1 (Primary)</label>
          <div class="astro-admin-color-picker">
            <div class="astro-admin-color-swatch">
              <input type="color" name="accent_color_1" value="{{ $settings['accent_color_1'] ?? '#4f7cff' }}"
                     oninput="updateColorPreview(this, 'accent1')" data-preview="accent1">
            </div>
            <span class="astro-admin-color-value" id="accent1-val">{{ $settings['accent_color_1'] ?? '#4f7cff' }}</span>
          </div>
        </div>

        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Accent Color 2 (Secondary)</label>
          <div class="astro-admin-color-picker">
            <div class="astro-admin-color-swatch">
              <input type="color" name="accent_color_2" value="{{ $settings['accent_color_2'] ?? '#7cc2ff' }}"
                     oninput="updateColorPreview(this, 'accent2')" data-preview="accent2">
            </div>
            <span class="astro-admin-color-value" id="accent2-val">{{ $settings['accent_color_2'] ?? '#7cc2ff' }}</span>
          </div>
        </div>

        {{-- Background Opacity --}}
        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Background Opacity: <span id="bgopacity-val">{{ $settings['background_opacity'] ?? '0' }}</span></label>
          <div class="astro-admin-range-field">
            <input type="range" name="background_opacity" class="astro-slider astro-admin-range-slider"
                   min="0" max="100" value="{{ $settings['background_opacity'] ?? '0' }}"
                   oninput="updateRange(this, 'bgopacity-val')">
          </div>
        </div>

        {{-- Sidebar Width --}}
        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Sidebar Width: <span id="sidebar-val">{{ $settings['sidebar_width'] ?? '280' }}</span>px</label>
          <div class="astro-admin-range-field">
            <input type="range" name="sidebar_width" class="astro-slider astro-admin-range-slider"
                   min="200" max="400" value="{{ $settings['sidebar_width'] ?? '280' }}"
                   oninput="updateRange(this, 'sidebar-val')"
                   data-preview="sidebar">
          </div>
        </div>

        {{-- Compact Mode --}}
        <div class="astro-admin-toggle" style="margin-top: 0.75rem;">
          <div class="astro-admin-toggle-info">
            <span class="astro-admin-toggle-label">Compact Mode</span>
            <span class="astro-admin-toggle-desc">Reduce spacing and card sizes</span>
          </div>
          <div class="astro-switch-track"
               data-on="{{ ($settings['compact_mode'] ?? '0') === '1' ? 'true' : 'false' }}"
               onclick="toggleSwitch(this, 'compact_mode')">
            <div class="astro-switch-thumb"></div>
          </div>
          <input type="hidden" name="compact_mode" id="field-compact_mode" value="{{ $settings['compact_mode'] ?? '0' }}">
        </div>
      </div>
    </div>

    {{-- ============================================ --}}
    {{-- TAB: BRANDING --}}
    {{-- ============================================ --}}
    <div class="astro-tab-content" id="tab-branding" style="display:none;">
      <div class="astro-admin-section">
        <h3 class="astro-admin-section-title">Branding</h3>
        <p class="astro-admin-section-desc">Customize logos, backgrounds, and text.</p>

        {{-- Logo Upload --}}
        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Logo</label>
          <p class="astro-admin-field-hint">Upload an image or paste a URL for your panel logo.</p>
          <div class="astro-admin-upload">
            <div class="astro-admin-upload-zone" onclick="document.getElementById('logo-upload').click()">
              @if($settings['logo_url'] ?? '')
                <img src="{{ $settings['logo_url'] }}" class="astro-admin-upload-preview" alt="Logo">
              @else
                <svg class="astro-admin-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="17 8 12 3 7 8"/>
                  <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <p class="astro-admin-upload-text">Click to upload logo</p>
                <p class="astro-admin-upload-hint">PNG, SVG, JPG up to 2MB</p>
              @endif
            </div>
            <input type="file" id="logo-upload" name="logo_url" accept="image/*" style="display:none;">
            <input type="text" name="logo_url_text" class="astro-input" placeholder="Or paste logo URL here..."
                   value="{{ ($settings['logo_url'] ?? '') && !str_starts_with($settings['logo_url'] ?? '', '/storage') ? ($settings['logo_url'] ?? '') : '' }}">
          </div>
        </div>

        {{-- Logo Dimensions --}}
        <div class="astro-admin-field-row">
          <div class="astro-admin-field" style="flex:1;">
            <label class="astro-admin-field-label">Logo Width</label>
            <input type="text" name="logo_width" class="astro-input" value="{{ $settings['logo_width'] ?? '180' }}" placeholder="180">
          </div>
          <div class="astro-admin-field" style="flex:1;">
            <label class="astro-admin-field-label">Logo Height</label>
            <input type="text" name="logo_height" class="astro-input" value="{{ $settings['logo_height'] ?? 'auto' }}" placeholder="auto">
          </div>
        </div>

        {{-- Login Background --}}
        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Login Background</label>
          <p class="astro-admin-field-hint">Custom background for the login page.</p>
          <div class="astro-admin-upload">
            <div class="astro-admin-upload-zone" onclick="document.getElementById('loginbg-upload').click()">
              @if($settings['login_bg'] ?? '')
                <img src="{{ $settings['login_bg'] }}" class="astro-admin-upload-preview" alt="Login BG">
              @else
                <svg class="astro-admin-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                  <circle cx="8.5" cy="8.5" r="1.5"/>
                  <polyline points="21 15 16 10 5 21"/>
                </svg>
                <p class="astro-admin-upload-text">Click to upload login background</p>
                <p class="astro-admin-upload-hint">Image or video URL supported</p>
              @endif
            </div>
            <input type="file" id="loginbg-upload" name="login_bg" accept="image/*,video/*" style="display:none;">
          </div>
        </div>

        {{-- Dashboard Background --}}
        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Dashboard Background</label>
          <p class="astro-admin-field-hint">Override the default dashboard background.</p>
          <div class="astro-admin-upload">
            <div class="astro-admin-upload-zone" onclick="document.getElementById('dashbg-upload').click()">
              @if($settings['dashboard_bg'] ?? '')
                <img src="{{ $settings['dashboard_bg'] }}" class="astro-admin-upload-preview" alt="Dashboard BG">
              @else
                <svg class="astro-admin-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                  <circle cx="8.5" cy="8.5" r="1.5"/>
                  <polyline points="21 15 16 10 5 21"/>
                </svg>
                <p class="astro-admin-upload-text">Click to upload dashboard background</p>
              @endif
            </div>
            <input type="file" id="dashbg-upload" name="dashboard_bg" accept="image/*" style="display:none;">
          </div>
        </div>

        {{-- Footer Text --}}
        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Footer Text</label>
          <input type="text" name="footer_text" class="astro-input" value="{{ $settings['footer_text'] ?? '' }}" placeholder="Custom footer text...">
        </div>

        {{-- Copyright --}}
        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Custom Copyright</label>
          <input type="text" name="copyright_text" class="astro-input" value="{{ $settings['copyright_text'] ?? '' }}" placeholder="© 2026 Your Company">
        </div>
      </div>
    </div>

    {{-- ============================================ --}}
    {{-- TAB: COLORS --}}
    {{-- ============================================ --}}
    <div class="astro-tab-content" id="tab-colors" style="display:none;">
      <div class="astro-admin-section">
        <h3 class="astro-admin-section-title">Custom Colors</h3>
        <p class="astro-admin-section-desc">Override individual color tokens across the theme.</p>

        @php
          $colorFields = [
            ['key' => 'color_primary',    'label' => 'Primary'],
            ['key' => 'color_secondary',  'label' => 'Secondary'],
            ['key' => 'color_success',    'label' => 'Success'],
            ['key' => 'color_warning',    'label' => 'Warning'],
            ['key' => 'color_danger',     'label' => 'Danger'],
            ['key' => 'color_info',       'label' => 'Info'],
            ['key' => 'color_text',       'label' => 'Text'],
            ['key' => 'color_muted',      'label' => 'Muted Text'],
            ['key' => 'color_border',     'label' => 'Border'],
            ['key' => 'color_hover',      'label' => 'Hover'],
            ['key' => 'color_buttons',    'label' => 'Buttons'],
            ['key' => 'color_links',      'label' => 'Links'],
          ];
        @endphp

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem;">
          @foreach($colorFields as $cf)
            <div class="astro-admin-field">
              <label class="astro-admin-field-label">{{ $cf['label'] }}</label>
              <div class="astro-admin-color-picker">
                <div class="astro-admin-color-swatch">
                  <input type="color" name="{{ $cf['key'] }}" 
                         value="{{ str_starts_with($settings[$cf['key']] ?? '', '#') ? $settings[$cf['key']] : '#4f7cff' }}"
                         oninput="document.getElementById('{{ $cf['key'] }}-val').textContent = this.value"
                         data-preview="{{ $cf['key'] }}">
                </div>
                <span class="astro-admin-color-value" id="{{ $cf['key'] }}-val">{{ $settings[$cf['key']] ?? '' }}</span>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- ============================================ --}}
    {{-- TAB: BACKGROUND --}}
    {{-- ============================================ --}}
    <div class="astro-tab-content" id="tab-background" style="display:none;">
      <div class="astro-admin-section">
        <h3 class="astro-admin-section-title">Background System</h3>
        <p class="astro-admin-section-desc">Configure the dashboard background type and effects.</p>

        {{-- Background Type --}}
        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Background Type</label>
          <select name="bg_type" class="astro-input" style="max-width: 250px;" onchange="toggleBgOptions(this.value)">
            <option value="gradient" {{ ($settings['bg_type'] ?? '') === 'gradient' ? 'selected' : '' }}>Default Gradient</option>
            <option value="solid" {{ ($settings['bg_type'] ?? '') === 'solid' ? 'selected' : '' }}>Solid Color</option>
            <option value="gradient-custom" {{ ($settings['bg_type'] ?? '') === 'gradient-custom' ? 'selected' : '' }}>Custom Gradient</option>
            <option value="image" {{ ($settings['bg_type'] ?? '') === 'image' ? 'selected' : '' }}>Image</option>
            <option value="video" {{ ($settings['bg_type'] ?? '') === 'video' ? 'selected' : '' }}>Video</option>
          </select>
        </div>

        {{-- Solid Color --}}
        <div class="astro-admin-field" id="bg-solid" style="display: {{ ($settings['bg_type'] ?? '') === 'solid' ? 'flex' : 'none' }};">
          <label class="astro-admin-field-label">Background Color</label>
          <div class="astro-admin-color-picker">
            <div class="astro-admin-color-swatch">
              <input type="color" name="bg_color" value="{{ $settings['bg_color'] ?? '#edf3ff' }}">
            </div>
            <span class="astro-admin-color-value">{{ $settings['bg_color'] ?? '#edf3ff' }}</span>
          </div>
        </div>

        {{-- Custom Gradient --}}
        <div class="astro-admin-field" id="bg-gradient" style="display: {{ ($settings['bg_type'] ?? '') === 'gradient-custom' ? 'flex' : 'none' }};">
          <label class="astro-admin-field-label">Custom Gradient CSS</label>
          <input type="text" name="bg_gradient" class="astro-input" 
                 value="{{ $settings['bg_gradient'] ?? '' }}"
                 placeholder="linear-gradient(135deg, #667eea 0%, #764ba2 100%)">
          <span class="astro-admin-field-hint">Enter any valid CSS gradient value.</span>
        </div>

        {{-- Image BG --}}
        <div class="astro-admin-field" id="bg-image" style="display: {{ ($settings['bg_type'] ?? '') === 'image' ? 'flex' : 'none' }};">
          <label class="astro-admin-field-label">Background Image URL</label>
          <input type="text" name="bg_image" class="astro-input" 
                 value="{{ $settings['bg_image'] ?? '' }}"
                 placeholder="https://example.com/background.jpg">
        </div>

        {{-- Video BG --}}
        <div class="astro-admin-field" id="bg-video" style="display: {{ ($settings['bg_type'] ?? '') === 'video' ? 'flex' : 'none' }};">
          <label class="astro-admin-field-label">Background Video URL (MP4)</label>
          <input type="text" name="bg_video" class="astro-input"
                 value="{{ $settings['bg_video'] ?? '' }}"
                 placeholder="https://example.com/background.mp4">
        </div>

        {{-- Particles Toggle --}}
        <div class="astro-admin-toggle" style="margin-top: 1rem;">
          <div class="astro-admin-toggle-info">
            <span class="astro-admin-toggle-label">Particles</span>
            <span class="astro-admin-toggle-desc">Enable animated floating particles</span>
          </div>
          <div class="astro-switch-track"
               data-on="{{ ($settings['particles'] ?? '0') === '1' ? 'true' : 'false' }}"
               onclick="toggleSwitch(this, 'particles')">
            <div class="astro-switch-thumb"></div>
          </div>
          <input type="hidden" name="particles" id="field-particles" value="{{ $settings['particles'] ?? '0' }}">
        </div>

        {{-- Overlay Opacity --}}
        <div class="astro-admin-field" style="margin-top: 1.25rem;">
          <label class="astro-admin-field-label">Overlay Opacity: <span id="overlay-val">{{ $settings['overlay_opacity'] ?? '0' }}</span>%</label>
          <div class="astro-admin-range-field">
            <input type="range" name="overlay_opacity" class="astro-slider astro-admin-range-slider"
                   min="0" max="100" value="{{ $settings['overlay_opacity'] ?? '0' }}"
                   oninput="updateRange(this, 'overlay-val')">
          </div>
        </div>

        {{-- Background Blur --}}
        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Background Blur: <span id="bgblur-val">{{ $settings['bg_blur'] ?? '0' }}</span>px</label>
          <div class="astro-admin-range-field">
            <input type="range" name="bg_blur" class="astro-slider astro-admin-range-slider"
                   min="0" max="30" value="{{ $settings['bg_blur'] ?? '0' }}"
                   oninput="updateRange(this, 'bgblur-val')">
          </div>
        </div>
      </div>
    </div>

    {{-- ============================================ --}}
    {{-- TAB: SVG CUSTOMIZER --}}
    {{-- ============================================ --}}
    <div class="astro-tab-content" id="tab-svg" style="display:none;">
      <div class="astro-admin-section">
        <h3 class="astro-admin-section-title">SVG Customizer</h3>
        <p class="astro-admin-section-desc">Add decorative SVG layers to your panel background.</p>

        {{-- SVG Input Methods --}}
        <div class="astro-admin-svg-editor">
          <div class="astro-admin-svg-input">
            {{-- Upload SVG --}}
            <div>
              <label class="astro-admin-field-label">Upload SVG</label>
              <div class="astro-admin-upload-zone" onclick="document.getElementById('svg-upload').click()">
                <svg class="astro-admin-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                  <polyline points="14 2 14 8 20 8"/>
                </svg>
                <p class="astro-admin-upload-text">Upload SVG file</p>
              </div>
              <input type="file" id="svg-upload" accept=".svg" style="display:none;" onchange="handleSvgUpload(this)">
            </div>

            {{-- Paste SVG --}}
            <div>
              <label class="astro-admin-field-label">Or paste SVG code</label>
              <textarea class="astro-admin-code-editor" id="svg-code-input" rows="6"
                        placeholder="<svg>...</svg>"></textarea>
            </div>

            {{-- SVG URL --}}
            <div>
              <label class="astro-admin-field-label">Or enter SVG URL</label>
              <input type="text" id="svg-url-input" class="astro-input" placeholder="https://example.com/decoration.svg">
            </div>

            {{-- SVG Options --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
              <div>
                <label class="astro-admin-field-label">Scale</label>
                <input type="number" id="svg-scale" class="astro-input" value="1" min="0.1" max="5" step="0.1">
              </div>
              <div>
                <label class="astro-admin-field-label">Opacity</label>
                <input type="number" id="svg-opacity" class="astro-input" value="1" min="0" max="1" step="0.05">
              </div>
              <div>
                <label class="astro-admin-field-label">Color Override</label>
                <input type="color" id="svg-color" value="#4f7cff">
              </div>
              <div>
                <label class="astro-admin-field-label">Position</label>
                <select id="svg-position" class="astro-input">
                  <option value="top">Top</option>
                  <option value="bottom">Bottom</option>
                  <option value="left">Left</option>
                  <option value="right">Right</option>
                  <option value="center" selected>Center</option>
                </select>
              </div>
            </div>

            <div class="astro-admin-toggle" style="margin-top: 0.5rem;">
              <div class="astro-admin-toggle-info">
                <span class="astro-admin-toggle-label">Enable Animation</span>
              </div>
              <div class="astro-switch-track" id="svg-animate"
                   data-on="false"
                   onclick="toggleSvgAnimate(this)">
                <div class="astro-switch-thumb"></div>
              </div>
            </div>

            <button type="button" class="astro-admin-save-btn" style="margin-top: 1rem; width: 100%;"
                    onclick="addSvgLayer()">Add SVG Layer</button>
          </div>

          {{-- SVG Preview --}}
          <div>
            <label class="astro-admin-field-label">Preview</label>
            <div class="astro-admin-svg-preview-area" id="svg-preview-area">
              <p style="color: var(--astro-text-muted); font-size: 0.8125rem;">Preview will appear here</p>
            </div>
          </div>
        </div>

        {{-- Existing SVG Layers --}}
        <div style="margin-top: 2rem;">
          <h4 style="font-weight: 700; margin-bottom: 1rem;">Active Layers</h4>
          <div id="svg-layers-list">
            @php
              $svgLayers = json_decode($settings['svg_layers'] ?? '[]', true) ?: [];
            @endphp
            @forelse($svgLayers as $layer)
              <div class="astro-admin-toggle" style="margin-bottom: 0.5rem;" id="svg-layer-{{ $layer['id'] }}">
                <div class="astro-admin-toggle-info">
                  <span class="astro-admin-toggle-label">SVG Layer ({{ $layer['position'] ?? 'center' }})</span>
                  <span class="astro-admin-toggle-desc">Opacity: {{ $layer['opacity'] ?? 1 }} · Scale: {{ $layer['scale'] ?? 1 }}</span>
                </div>
                <a href="{{ $root }}/{{ $layer['id'] }}" class="astro-btn astro-btn-danger" 
                   style="padding: 0.375rem 0.75rem; font-size: 0.75rem;"
                   onclick="event.preventDefault(); if(confirm('Remove this layer?')) window.location.href=this.href + '?_method=DELETE';">
                  Remove
                </a>
              </div>
            @empty
              <p style="color: var(--astro-text-muted); font-size: 0.8125rem;">No SVG layers added yet.</p>
            @endforelse
          </div>
        </div>

        {{-- Reset SVG --}}
        <button type="button" class="astro-admin-reset-btn" style="margin-top: 1.5rem;" onclick="if(confirm('Remove all SVG layers?')) resetSvg()">Reset All SVG</button>
      </div>
    </div>

    {{-- ============================================ --}}
    {{-- TAB: CUSTOM CODE --}}
    {{-- ============================================ --}}
    <div class="astro-tab-content" id="tab-custom-code" style="display:none;">
      <div class="astro-admin-section">
        <h3 class="astro-admin-section-title">Custom Code</h3>
        <p class="astro-admin-section-desc">Inject custom HTML, CSS, and JavaScript. Use with caution.</p>

        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Custom CSS</label>
          <span class="astro-admin-field-hint">Additional CSS injected into every page.</span>
          <textarea name="custom_css" class="astro-admin-code-editor" rows="8" 
                    placeholder="/* Your custom CSS here */">{{ $settings['custom_css'] ?? '' }}</textarea>
        </div>

        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Custom JavaScript</label>
          <span class="astro-admin-field-hint">JavaScript injected before closing body tag.</span>
          <textarea name="custom_js" class="astro-admin-code-editor" rows="8"
                    placeholder="// Your custom JS here">{{ $settings['custom_js'] ?? '' }}</textarea>
        </div>

        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Custom Head HTML</label>
          <span class="astro-admin-field-hint">HTML injected into the &lt;head&gt; section.</span>
          <textarea name="custom_head" class="astro-admin-code-editor" rows="6"
                    placeholder="<!-- Additional head content -->">{{ $settings['custom_head'] ?? '' }}</textarea>
        </div>

        <div class="astro-admin-field">
          <label class="astro-admin-field-label">Custom Footer HTML</label>
          <span class="astro-admin-field-hint">HTML injected before closing body tag.</span>
          <textarea name="custom_footer" class="astro-admin-code-editor" rows="6"
                    placeholder="<!-- Additional footer content -->">{{ $settings['custom_footer'] ?? '' }}</textarea>
        </div>
      </div>
    </div>

    {{-- ============================================ --}}
    {{-- TAB: LIVE PREVIEW --}}
    {{-- ============================================ --}}
    <div class="astro-tab-content" id="tab-preview" style="display:none;">
      <div class="astro-admin-section">
        <h3 class="astro-admin-section-title">Live Preview</h3>
        <p class="astro-admin-section-desc">See your changes in real-time. Adjust settings in other tabs and watch the preview update.</p>

        <div class="astro-admin-preview">
          <div class="astro-admin-preview-bar">
            <div class="astro-admin-preview-device-btns">
              <button type="button" class="astro-admin-preview-device-btn active" onclick="setPreviewDevice('desktop', this)">Desktop</button>
              <button type="button" class="astro-admin-preview-device-btn" onclick="setPreviewDevice('tablet', this)">Tablet</button>
              <button type="button" class="astro-admin-preview-device-btn" onclick="setPreviewDevice('mobile', this)">Mobile</button>
            </div>
            <button type="button" class="astro-admin-preview-device-btn" onclick="refreshPreview()">↻ Refresh</button>
          </div>
          <iframe id="astro-preview-frame" class="astro-admin-preview-frame desktop" 
                  srcdoc=""></iframe>
        </div>
      </div>
    </div>

    {{-- ============================================ --}}
    {{-- SAVE BAR --}}
    {{-- ============================================ --}}
    <div class="astro-admin-save-bar">
      <button type="button" class="astro-admin-reset-btn" name="action" value="reset"
              onclick="if(confirm('Reset all settings to defaults? This cannot be undone.')) { document.getElementById('action-field').value='reset'; document.getElementById('astro-settings-form').submit(); }">
        Reset to Defaults
      </button>
      <input type="hidden" name="action" id="action-field" value="save">
      <button type="submit" class="astro-admin-save-btn">Save Changes</button>
    </div>
  </form>
</div>

{{-- ============================================================ --}}
{{-- Admin Settings JavaScript --}}
{{-- ============================================================ --}}
<link rel="stylesheet" href="/extensions/astrotheme/css/astro-theme.css">
<link rel="stylesheet" href="/extensions/astrotheme/css/astro-animations.css">
<link rel="stylesheet" href="/extensions/astrotheme/css/astro-admin.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Apply astro admin class to body
  document.body.classList.add('astro-admin-active');
});

// Tab switching
document.querySelectorAll('.astro-admin-tab').forEach(function(tab) {
  tab.addEventListener('click', function() {
    document.querySelectorAll('.astro-admin-tab').forEach(t => t.classList.remove('active'));
    this.classList.add('active');
    
    document.querySelectorAll('.astro-tab-content').forEach(c => c.style.display = 'none');
    document.getElementById('tab-' + this.dataset.tab).style.display = 'block';
  });
});

// Toggle switches
function toggleSwitch(el, fieldName) {
  var isOn = el.getAttribute('data-on') === 'true';
  el.setAttribute('data-on', isOn ? 'false' : 'true');
  document.getElementById('field-' + fieldName).value = isOn ? '0' : '1';
  triggerPreviewUpdate();
}

function toggleSvgAnimate(el) {
  var isOn = el.getAttribute('data-on') === 'true';
  el.setAttribute('data-on', isOn ? 'false' : 'true');
}

// Range slider updates
function updateRange(input, valId) {
  document.getElementById(valId).textContent = input.value;
  var fill = ((input.value - input.min) / (input.max - input.min)) * 100;
  input.style.setProperty('--fill', fill + '%');
  triggerPreviewUpdate();
}

function updateRangeDecimal(input, valId) {
  var val = (input.value / 100).toFixed(2);
  document.getElementById(valId).textContent = val;
  var fill = input.value;
  input.style.setProperty('--fill', fill + '%');
  triggerPreviewUpdate();
}

// Color preview
function updateColorPreview(input, valId) {
  document.getElementById(valId + '-val').textContent = input.value;
  triggerPreviewUpdate();
}

// Background type toggle
function toggleBgOptions(type) {
  ['solid', 'gradient', 'image', 'video'].forEach(function(opt) {
    var el = document.getElementById('bg-' + opt);
    if (el) el.style.display = 'none';
  });
  
  var map = { 'solid': 'solid', 'gradient-custom': 'gradient', 'image': 'image', 'video': 'video' };
  var show = map[type];
  if (show) {
    var el = document.getElementById('bg-' + show);
    if (el) el.style.display = 'flex';
  }
}

// Preview device switching
function setPreviewDevice(device, btn) {
  document.querySelectorAll('.astro-admin-preview-device-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  
  var frame = document.getElementById('astro-preview-frame');
  frame.className = 'astro-admin-preview-frame ' + device;
  refreshPreview();
}

function refreshPreview() {
  // Re-generate preview from current settings state
  triggerPreviewUpdate();
}

// Live preview update
var previewDebounce = null;
function triggerPreviewUpdate() {
  clearTimeout(previewDebounce);
  previewDebounce = setTimeout(function() {
    updatePreviewFrame();
  }, 150);
}

function updatePreviewFrame() {
  var frame = document.getElementById('astro-preview-frame');
  if (!frame) return;
  
  var accent1 = document.querySelector('[name="accent_color_1"]')?.value || '#4f7cff';
  var accent2 = document.querySelector('[name="accent_color_2"]')?.value || '#7cc2ff';
  var blur = document.querySelector('[name="blur_strength"]')?.value || '20';
  var opacity = (document.querySelector('[name="glass_opacity"]')?.value || 60) / 100;
  var radius = document.querySelector('[name="border_radius"]')?.value || '24';
  var sidebarW = document.querySelector('[name="sidebar_width"]')?.value || '280';
  var darkMode = document.getElementById('field-dark_mode')?.value === '1';
  
  var pageBg = darkMode ? '#0f1729' : '#edf3ff';
  var textColor = darkMode ? '#e2e8f0' : '#2b3a67';
  var glassBg = darkMode 
    ? 'rgba(30,41,59,' + opacity + ')' 
    : 'rgba(255,255,255,' + opacity + ')';
  
  var previewHtml = '<!DOCTYPE html><html><head><style>' +
    'body{margin:0;font-family:Inter,system-ui,sans-serif;background:' + pageBg + ';' +
    'background-image:radial-gradient(1100px 560px at 88% -12%,rgba(124,194,255,0.38),transparent 60%),' +
    'radial-gradient(900px 520px at -12% 18%,rgba(79,124,255,0.22),transparent 55%),' +
    'linear-gradient(180deg,' + (darkMode ? '#0f1729' : '#f4f8ff') + ' 0%,' + (darkMode ? '#1a2744' : '#e9f0fe') + ' 100%);' +
    'color:' + textColor + ';display:flex;min-height:100vh;}' +
    '.sidebar{width:' + sidebarW + 'px;background:' + glassBg + ';backdrop-filter:blur(' + blur + 'px);' +
    'border:1px solid rgba(255,255,255,0.75);border-radius:' + radius + 'px;padding:20px;margin:16px;' +
    'box-shadow:0 8px 32px -8px rgba(48,87,196,0.1);}' +
    '.sidebar .logo{font-family:Outfit,sans-serif;font-weight:800;font-size:20px;margin-bottom:24px;' +
    'background:linear-gradient(135deg,' + accent1 + ',' + accent2 + ');-webkit-background-clip:text;' +
    '-webkit-text-fill-color:transparent;display:inline-block;}' +
    '.nav-item{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:' + (parseInt(radius) > 12 ? 12 : radius) + 'px;' +
    'font-size:13px;font-weight:600;margin-bottom:4px;cursor:pointer;transition:all 0.2s;}' +
    '.nav-item:hover{background:rgba(255,255,255,0.4);}' +
    '.nav-item.active{background:linear-gradient(135deg,' + accent1 + ',' + accent2 + ');color:#fff;' +
    'box-shadow:0 10px 22px -8px rgba(79,124,255,0.7);}' +
    '.main{flex:1;padding:20px;}' +
    '.card{background:' + glassBg + ';backdrop-filter:blur(' + blur + 'px);border:1px solid rgba(255,255,255,0.75);' +
    'border-radius:' + radius + 'px;padding:20px;margin-bottom:16px;' +
    'box-shadow:0 8px 32px -8px rgba(48,87,196,0.1);transition:transform 0.35s cubic-bezier(0.22,1,0.36,1);}' +
    '.card:hover{transform:translateY(-4px);box-shadow:0 20px 48px -14px rgba(48,87,196,0.22);}' +
    '.card h3{font-family:Outfit,sans-serif;margin:0 0 8px;font-weight:700;color:' + (darkMode ? '#f1f5f9' : '#1e2a52') + ';}' +
    '.card p{margin:0;font-size:13px;color:' + (darkMode ? '#94a3b8' : '#8ba0d8') + ';}' +
    '.btn{display:inline-flex;padding:8px 16px;border-radius:' + (parseInt(radius) > 12 ? 12 : radius) + 'px;' +
    'background:linear-gradient(135deg,' + accent1 + ',' + accent2 + ');color:#fff;font-size:12px;' +
    'font-weight:700;border:none;cursor:pointer;box-shadow:0 8px 20px -8px rgba(79,124,255,0.7);}' +
    '.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;}' +
    '.status{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;' +
    'font-size:11px;font-weight:700;}' +
    '.status-online{color:#059669;background:rgba(52,211,153,0.1);border:1px solid rgba(52,211,153,0.35);}' +
    '.status-online::before{content:"";width:6px;height:6px;border-radius:50%;background:currentColor;}' +
    '</style></head><body>' +
    '<div class="sidebar">' +
      '<div class="logo">Astro Panel</div>' +
      '<div class="nav-item active">⊞ Dashboard</div>' +
      '<div class="nav-item">⊟ Servers</div>' +
      '<div class="nav-item">▶ Console</div>' +
      '<div class="nav-item">⊡ Files</div>' +
      '<div class="nav-item">⊕ Settings</div>' +
    '</div>' +
    '<div class="main">' +
      '<div class="grid">' +
        '<div class="card"><h3>Server Alpha</h3><p>Minecraft · 12 players</p><br><span class="status status-online">Online</span></div>' +
        '<div class="card"><h3>Server Beta</h3><p>Valheim · 8 players</p><br><span class="status status-online">Online</span></div>' +
        '<div class="card"><h3>Server Gamma</h3><p> Terraria · 0 players</p><br><button class="btn">Manage</button></div>' +
      '</div>' +
    '</div>' +
    '</body></html>';
  
  frame.srcdoc = previewHtml;
}

// Initialize range sliders fill
document.querySelectorAll('.astro-slider').forEach(function(slider) {
  var fill = ((slider.value - slider.min) / (slider.max - slider.min)) * 100;
  slider.style.setProperty('--fill', fill + '%');
});

// SVG handling
function handleSvgUpload(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      var svg = e.target.result;
      document.getElementById('svg-code-input').value = svg;
      updateSvgPreview(svg);
    };
    reader.readAsText(input.files[0]);
  }
}

function addSvgLayer() {
  var svgCode = document.getElementById('svg-code-input').value.trim();
  var svgUrl = document.getElementById('svg-url-input').value.trim();
  
  if (!svgCode && !svgUrl) {
    alert('Please provide SVG code or a URL.');
    return;
  }
  
  // Create hidden form and submit
  var form = document.createElement('form');
  form.method = 'POST';
  form.action = '{{ $root }}';
  form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
    '<input type="hidden" name="action" value="upload_svg">' +
    '<input type="hidden" name="svg_code" value="' + encodeURIComponent(svgCode) + '">' +
    '<input type="hidden" name="svg_url" value="' + encodeURIComponent(svgUrl) + '">' +
    '<input type="hidden" name="position" value="' + document.getElementById('svg-position').value + '">' +
    '<input type="hidden" name="opacity" value="' + document.getElementById('svg-opacity').value + '">' +
    '<input type="hidden" name="scale" value="' + document.getElementById('svg-scale').value + '">';
  document.body.appendChild(form);
  form.submit();
}

function updateSvgPreview(svg) {
  var area = document.getElementById('svg-preview-area');
  var color = document.getElementById('svg-color').value;
  var scale = document.getElementById('svg-scale').value;
  var opacity = document.getElementById('svg-opacity').value;
  
  area.innerHTML = '<div style="transform:scale(' + scale + ');opacity:' + opacity + ';color:' + color + ';">' + svg + '</div>';
}

function resetSvg() {
  var form = document.createElement('form');
  form.method = 'POST';
  form.action = '{{ $root }}';
  form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
    '<input type="hidden" name="action" value="save">' +
    '<input type="hidden" name="svg_layers" value="[]">';
  document.body.appendChild(form);
  form.submit();
}

// Live preview SVG code input listener
document.getElementById('svg-code-input')?.addEventListener('input', function() {
  updateSvgPreview(this.value);
});

document.getElementById('svg-url-input')?.addEventListener('change', function() {
  if (this.value) {
    fetch(this.value).then(r => r.text()).then(svg => {
      document.getElementById('svg-code-input').value = svg;
      updateSvgPreview(svg);
    });
  }
});

// Initial preview render
setTimeout(updatePreviewFrame, 500);
</script>
