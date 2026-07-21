/**
 * Astro Theme - Main JavaScript
 * Handles theme initialization, custom sidebar injection, heartbeat loader,
 * dynamic styling, particles, and live interactions.
 */
(function() {
  'use strict';

  var config = window.__ASTRO_THEME__ || {};

  function init() {
    if (!config.enabled) return;

    applyDarkMode();
    applyCompactMode();
    injectHeartbeatLoader();
    injectCustomSidebar();
    initParticles();
    initScrollAnimations();
    injectBranding();
    initToasts();
    observeDynamicContent();
  }

  /**
   * Inject heartbeat loading animation
   */
  function injectHeartbeatLoader() {
    if (document.getElementById('astro-heartbeat-loader')) return;

    var accent1 = config.accentColor1 || '#4f7cff';
    var accent2 = config.accentColor2 || '#7cc2ff';
    var logoUrl = config.logoUrl || '';

    var style = document.createElement('style');
    style.id = 'astro-heartbeat-style';
    style.textContent = [
      '/* Astro Heartbeat Loader */',
      '@keyframes astro-heartbeat {',
      '  0%, 100% { transform: scale(1); opacity: 0.8; }',
      '  14% { transform: scale(1.15); opacity: 1; }',
      '  21% { transform: scale(1); opacity: 0.8; }',
      '  28% { transform: scale(1.1); opacity: 1; }',
      '  35% { transform: scale(1); opacity: 0.8; }',
      '}',
      '@keyframes astro-pulse-ring {',
      '  0% { transform: scale(0.8); opacity: 0.6; }',
      '  50% { transform: scale(1.4); opacity: 0; }',
      '  100% { transform: scale(0.8); opacity: 0; }',
      '}',
      '@keyframes astro-glow-pulse {',
      '  0%, 100% { box-shadow: 0 0 20px ' + accent1 + '40, 0 0 40px ' + accent1 + '20; }',
      '  50% { box-shadow: 0 0 40px ' + accent1 + '60, 0 0 80px ' + accent1 + '30; }',
      '}',
      '.astro-heartbeat-loader {',
      '  position: fixed;',
      '  top: 0; left: 0; right: 0; bottom: 0;',
      '  z-index: 99999;',
      '  display: flex;',
      '  flex-direction: column;',
      '  align-items: center;',
      '  justify-content: center;',
      '  background: linear-gradient(180deg, #f4f8ff 0%, #e9f0fe 100%);',
      '  transition: opacity 0.5s ease, visibility 0.5s ease;',
      '}',
      '.astro-heartbeat-loader.hidden {',
      '  opacity: 0;',
      '  visibility: hidden;',
      '  pointer-events: none;',
      '}',
      '.astro-heartbeat-icon {',
      '  width: 80px;',
      '  height: 80px;',
      '  border-radius: 24px;',
      '  background: linear-gradient(135deg, ' + accent1 + ', ' + accent2 + ');',
      '  display: flex;',
      '  align-items: center;',
      '  justify-content: center;',
      '  animation: astro-heartbeat 1.4s ease-in-out infinite, astro-glow-pulse 2s ease-in-out infinite;',
      '  position: relative;',
      '}',
      '.astro-heartbeat-icon::before {',
      '  content: \'\';',
      '  position: absolute;',
      '  inset: -8px;',
      '  border-radius: 28px;',
      '  border: 2px solid ' + accent1 + ';',
      '  opacity: 0;',
      '  animation: astro-pulse-ring 2s ease-out infinite;',
      '}',
      '.astro-heartbeat-icon::after {',
      '  content: \'\';',
      '  position: absolute;',
      '  inset: -16px;',
      '  border-radius: 32px;',
      '  border: 1px solid ' + accent2 + ';',
      '  opacity: 0;',
      '  animation: astro-pulse-ring 2s ease-out infinite 0.5s;',
      '}',
      '.astro-heartbeat-icon svg {',
      '  width: 40px;',
      '  height: 40px;',
      '  color: white;',
      '}',
      '.astro-heartbeat-icon img {',
      '  width: 48px;',
      '  height: 48px;',
      '  object-fit: contain;',
      '}',
      '.astro-heartbeat-text {',
      '  margin-top: 24px;',
      '  font-family: Outfit, Inter, sans-serif;',
      '  font-weight: 700;',
      '  font-size: 14px;',
      '  letter-spacing: 0.08em;',
      '  text-transform: uppercase;',
      '  background: linear-gradient(135deg, ' + accent1 + ', ' + accent2 + ');',
      '  -webkit-background-clip: text;',
      '  -webkit-text-fill-color: transparent;',
      '  animation: astro-heartbeat 1.4s ease-in-out infinite 0.2s;',
      '}',
      '.astro-heartbeat-dots {',
      '  display: flex;',
      '  gap: 6px;',
      '  margin-top: 16px;',
      '}',
      '.astro-heartbeat-dot {',
      '  width: 6px;',
      '  height: 6px;',
      '  border-radius: 50%;',
      '  background: ' + accent1 + ';',
      '  animation: astro-heartbeat 1.4s ease-in-out infinite;',
      '}',
      '.astro-heartbeat-dot:nth-child(2) { animation-delay: 0.15s; }',
      '.astro-heartbeat-dot:nth-child(3) { animation-delay: 0.3s; }',
    ].join('\n');
    document.head.appendChild(style);

    var loader = document.createElement('div');
    loader.className = 'astro-heartbeat-loader';
    loader.id = 'astro-heartbeat-loader';

    var logoContent = logoUrl
      ? '<img src="' + logoUrl + '" alt="Logo">'
      : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';

    loader.innerHTML = 
      '<div class="astro-heartbeat-icon">' + logoContent + '</div>' +
      '<div class="astro-heartbeat-text">Loading</div>' +
      '<div class="astro-heartbeat-dots">' +
        '<div class="astro-heartbeat-dot"></div>' +
        '<div class="astro-heartbeat-dot"></div>' +
        '<div class="astro-heartbeat-dot"></div>' +
      '</div>';

    document.body.prepend(loader);

    // Hide loader when page is ready
    function hideLoader() {
      var el = document.getElementById('astro-heartbeat-loader');
      if (el) {
        el.classList.add('hidden');
        setTimeout(function() { el.remove(); }, 600);
      }
    }

    if (document.readyState === 'complete') {
      setTimeout(hideLoader, 800);
    } else {
      window.addEventListener('load', function() {
        setTimeout(hideLoader, 800);
      });
    }
  }

  /**
   * Inject custom Astro sidebar - replaces Pterodactyl's topbar
   */
  function injectCustomSidebar() {
    if (document.getElementById('astro-custom-sidebar')) return;

    var accent1 = config.accentColor1 || '#4f7cff';
    var accent2 = config.accentColor2 || '#7cc2ff';
    var logoUrl = config.logoUrl || '';

    // Inject comprehensive styles
    if (!document.getElementById('astro-sidebar-style')) {
      var style = document.createElement('style');
      style.id = 'astro-sidebar-style';
      style.textContent = [
        '/* ===== ASTRO CUSTOM SIDEBAR ===== */',
        
        /* Hide Pterodactyl\'s topbar and adjust layout */
        '#app > div > div:first-child > div:first-child,', /* Top bar container */
        'header[role="banner"],',
        'nav[role="navigation"]:first-of-type,',
        '.navbar,',
        '[class*="topbar"],',
        '[class*="TopBar"],',
        '[class*="header-bar"],',
        'div[style*="position: sticky"][style*="top: 0"],',
        'div[style*="position:fixed"][style*="top:0"] {',
        '  display: none !important;',
        '  visibility: hidden !important;',
        '  height: 0 !important;',
        '  max-height: 0 !important;',
        '  overflow: hidden !important;',
        '}',
        
        /* Adjust main content to account for sidebar */
        '#app > div {',
        '  margin-left: 280px !important;',
        '  transition: margin-left 0.3s ease;',
        '}',
        
        /* Custom sidebar styles */
        '#astro-custom-sidebar {',
        '  position: fixed !important;',
        '  top: 0 !important;',
        '  left: 0 !important;',
        '  bottom: 0 !important;',
        '  width: 280px !important;',
        '  background: rgba(255, 255, 255, 0.60) !important;',
        '  -webkit-backdrop-filter: blur(20px) saturate(1.5) !important;',
        '  backdrop-filter: blur(20px) saturate(1.5) !important;',
        '  border: 1px solid rgba(255, 255, 255, 0.75) !important;',
        '  border-radius: 0 24px 24px 0 !important;',
        '  box-shadow: 0 8px 32px -8px rgba(48, 87, 196, 0.10), inset 0 1px 0 rgba(255, 255, 255, 0.7) !important;',
        '  z-index: 9999 !important;',
        '  display: flex !important;',
        '  flex-direction: column !important;',
        '  padding: 20px !important;',
        '  overflow-y: auto !important;',
        '  overflow-x: hidden !important;',
        '  font-family: Inter, system-ui, sans-serif !important;',
        '}',
        '#astro-custom-sidebar::-webkit-scrollbar { width: 4px; }',
        '#astro-custom-sidebar::-webkit-scrollbar-thumb { background: rgba(96, 132, 220, 0.2); border-radius: 99px; }',
        
        /* Logo section */
        '.astro-sidebar-logo {',
        '  display: flex;',
        '  align-items: center;',
        '  gap: 12px;',
        '  margin-bottom: 28px;',
        '  padding: 8px 0;',
        '}',
        '.astro-sidebar-logo-icon {',
        '  width: 44px;',
        '  height: 44px;',
        '  border-radius: 12px;',
        '  background: linear-gradient(135deg, ' + accent1 + ', ' + accent2 + ');',
        '  display: flex;',
        '  align-items: center;',
        '  justify-content: center;',
        '  flex-shrink: 0;',
        '  box-shadow: 0 8px 20px -8px rgba(79, 124, 255, 0.7);',
        '}',
        '.astro-sidebar-logo-icon img { width: 28px; height: 28px; object-fit: contain; }',
        '.astro-sidebar-logo-icon svg { width: 24px; height: 24px; color: white; }',
        '.astro-sidebar-logo-text {',
        '  font-family: Outfit, Inter, sans-serif;',
        '  font-weight: 800;',
        '  font-size: 20px;',
        '  background: linear-gradient(135deg, ' + accent1 + ', ' + accent2 + ');',
        '  -webkit-background-clip: text;',
        '  -webkit-text-fill-color: transparent;',
        '  letter-spacing: -0.02em;',
        '  line-height: 1.2;',
        '}',
        '.astro-sidebar-logo-sub {',
        '  font-size: 11px;',
        '  color: #8ba0d8;',
        '  font-weight: 500;',
        '  letter-spacing: 0.04em;',
        '  text-transform: uppercase;',
        '  margin-top: 2px;',
        '}',
        
        /* Navigation */
        '.astro-sidebar-nav {',
        '  display: flex;',
        '  flex-direction: column;',
        '  gap: 4px;',
        '  flex: 1;',
        '}',
        '.astro-sidebar-label {',
        '  font-size: 10px;',
        '  font-weight: 700;',
        '  color: #8ba0d8;',
        '  text-transform: uppercase;',
        '  letter-spacing: 0.08em;',
        '  padding: 16px 14px 8px;',
        '}',
        '.astro-sidebar-link {',
        '  display: flex;',
        '  align-items: center;',
        '  gap: 12px;',
        '  padding: 11px 14px;',
        '  border-radius: 12px;',
        '  font-size: 14px;',
        '  font-weight: 600;',
        '  color: #2b3a67;',
        '  text-decoration: none !important;',
        '  transition: all 0.2s cubic-bezier(0.22, 1, 0.36, 1);',
        '  position: relative;',
        '  cursor: pointer;',
        '}',
        '.astro-sidebar-link:hover {',
        '  background: rgba(255, 255, 255, 0.5);',
        '  color: #1e2a52;',
        '  transform: translateX(4px);',
        '}',
        '.astro-sidebar-link.active {',
        '  background: linear-gradient(135deg, ' + accent1 + ', ' + accent2 + ') !important;',
        '  color: #fff !important;',
        '  box-shadow: 0 10px 22px -8px rgba(79, 124, 255, 0.7);',
        '}',
        '.astro-sidebar-link.active:hover {',
        '  transform: none;',
        '}',
        '.astro-sidebar-link svg {',
        '  width: 20px;',
        '  height: 20px;',
        '  flex-shrink: 0;',
        '  opacity: 0.7;',
        '}',
        '.astro-sidebar-link.active svg { opacity: 1; }',
        '.astro-sidebar-divider {',
        '  height: 1px;',
        '  background: rgba(120, 145, 220, 0.15);',
        '  margin: 12px 0;',
        '}',
        
        /* User section at bottom */
        '.astro-sidebar-user {',
        '  background: rgba(249, 251, 255, 0.86);',
        '  border-radius: 16px;',
        '  padding: 14px;',
        '  display: flex;',
        '  align-items: center;',
        '  gap: 12px;',
        '  border: 1px solid rgba(255, 255, 255, 0.5);',
        '  margin-top: auto;',
        '  box-shadow: 0 4px 16px -4px rgba(48, 87, 196, 0.08);',
        '}',
        '.astro-sidebar-avatar {',
        '  width: 40px;',
        '  height: 40px;',
        '  border-radius: 10px;',
        '  background: linear-gradient(135deg, ' + accent1 + ', ' + accent2 + ');',
        '  display: flex;',
        '  align-items: center;',
        '  justify-content: center;',
        '  color: #fff;',
        '  font-weight: 700;',
        '  font-size: 14px;',
        '  flex-shrink: 0;',
        '}',
        '.astro-sidebar-user-info { flex: 1; min-width: 0; }',
        '.astro-sidebar-user-name {',
        '  font-size: 14px;',
        '  font-weight: 700;',
        '  color: #1e2a52;',
        '  white-space: nowrap;',
        '  overflow: hidden;',
        '  text-overflow: ellipsis;',
        '}',
        '.astro-sidebar-user-role {',
        '  font-size: 11px;',
        '  color: #8ba0d8;',
        '  margin-top: 2px;',
        '}',
        '.astro-sidebar-logout {',
        '  padding: 8px;',
        '  border-radius: 8px;',
        '  background: transparent;',
        '  border: none;',
        '  color: #8ba0d8;',
        '  cursor: pointer;',
        '  transition: all 0.2s ease;',
        '  display: flex;',
        '  align-items: center;',
        '  justify-content: center;',
        '}',
        '.astro-sidebar-logout:hover {',
        '  background: rgba(244, 63, 94, 0.1);',
        '  color: #e11d48;',
        '}',
        '.astro-sidebar-logout svg { width: 18px; height: 18px; }',
        
        /* Mobile responsive */
        '@media (max-width: 1023px) {',
        '  #astro-custom-sidebar {',
        '    transform: translateX(-100%);',
        '    transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);',
        '  }',
        '  #astro-custom-sidebar.open {',
        '    transform: translateX(0);',
        '  }',
        '  #app > div {',
        '    margin-left: 0 !important;',
        '  }',
        '  .astro-sidebar-toggle-mobile {',
        '    display: flex !important;',
        '  }',
        '}',
        
        /* Mobile toggle button */
        '.astro-sidebar-toggle-mobile {',
        '  display: none;',
        '  position: fixed;',
        '  top: 16px;',
        '  left: 16px;',
        '  z-index: 10000;',
        '  width: 44px;',
        '  height: 44px;',
        '  border-radius: 12px;',
        '  background: rgba(255, 255, 255, 0.6);',
        '  -webkit-backdrop-filter: blur(20px);',
        '  backdrop-filter: blur(20px);',
        '  border: 1px solid rgba(255, 255, 255, 0.75);',
        '  box-shadow: 0 8px 32px -8px rgba(48, 87, 196, 0.1);',
        '  align-items: center;',
        '  justify-content: center;',
        '  cursor: pointer;',
        '  color: #2b3a67;',
        '  transition: all 0.2s ease;',
        '}',
        '.astro-sidebar-toggle-mobile:hover {',
        '  background: rgba(255, 255, 255, 0.8);',
        '}',
        '.astro-sidebar-toggle-mobile svg { width: 20px; height: 20px; }',
        
        /* Mobile overlay */
        '.astro-sidebar-overlay {',
        '  display: none;',
        '  position: fixed;',
        '  inset: 0;',
        '  background: rgba(15, 23, 42, 0.3);',
        '  -webkit-backdrop-filter: blur(4px);',
        '  backdrop-filter: blur(4px);',
        '  z-index: 9998;',
        '}',
        '.astro-sidebar-overlay.active { display: block; }',
      ].join('\n');
      document.head.appendChild(style);
    }

    // Create sidebar element
    var sidebar = document.createElement('nav');
    sidebar.id = 'astro-custom-sidebar';

    // Get user info
    var userName = 'Admin';
    var userRole = 'Administrator';
    var userInitials = 'AD';
    var logoutUrl = '/auth/logout';

    // Try to extract user info from page
    var userInfo = document.querySelector('[class*="user-info"], [class*="UserInfo"], header span');
    if (userInfo) {
      var nameText = userInfo.textContent || userInfo.innerText;
      if (nameText && nameText.trim()) {
        userName = nameText.trim().split('\n')[0].trim();
        userInitials = userName.split(' ').map(function(w) { return w[0]; }).join('').substring(0, 2).toUpperCase();
      }
    }

    // Get logout URL
    var logoutLink = document.querySelector('a[href*="logout"], button[onclick*="logout"]');
    if (logoutLink) {
      logoutUrl = logoutLink.getAttribute('href') || logoutLink.getAttribute('onclick') || '/auth/logout';
    }

    // Determine current path
    var currentPath = window.location.pathname;

    var logoContent = logoUrl
      ? '<img src="' + logoUrl + '" alt="Logo">'
      : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';

    // Navigation items
    var navItems = [
      { 
        label: 'Dashboard', 
        icon: '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>', 
        href: '/' 
      },
      { 
        label: 'Servers', 
        icon: '<rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/>', 
        href: '/server' 
      },
    ];

    // Check if we're on a server page and add server-specific items
    var serverItems = [];
    if (currentPath.indexOf('/server/') === 0) {
      var serverId = currentPath.split('/')[2];
      serverItems = [
        { label: 'Console', icon: '<polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/>', href: '/server/' + serverId },
        { label: 'Files', icon: '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>', href: '/server/' + serverId + '/files' },
        { label: 'Databases', icon: '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>', href: '/server/' + serverId + '/databases' },
        { label: 'Schedules', icon: '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>', href: '/server/' + serverId + '/schedules' },
        { label: 'Users', icon: '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>', href: '/server/' + serverId + '/users' },
        { label: 'Backups', icon: '<polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>', href: '/server/' + serverId + '/backups' },
        { label: 'Network', icon: '<circle cx="12" cy="12" r="2"/><path d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14"/>', href: '/server/' + serverId + '/network' },
        { label: 'Startup', icon: '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>', href: '/server/' + serverId + '/startup' },
        { label: 'Settings', icon: '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>', href: '/server/' + serverId + '/settings' },
      ];
    }

    function isActive(href) {
      if (href === '/') return currentPath === '/' || currentPath === '';
      return currentPath.indexOf(href) === 0;
    }

    function buildLink(item) {
      var activeClass = isActive(item.href) ? ' active' : '';
      return '<a href="' + item.href + '" class="astro-sidebar-link' + activeClass + '">' +
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' + item.icon + '</svg>' +
        '<span>' + item.label + '</span>' +
      '</a>';
    }

    var html = '<div class="astro-sidebar-logo">' +
      '<div class="astro-sidebar-logo-icon">' + logoContent + '</div>' +
      '<div>' +
        '<div class="astro-sidebar-logo-text">Astro Panel</div>' +
        '<div class="astro-sidebar-logo-sub">Game Server Management</div>' +
      '</div>' +
    '</div>';

    html += '<div class="astro-sidebar-nav">';
    html += '<div class="astro-sidebar-label">Navigation</div>';
    navItems.forEach(function(item) { html += buildLink(item); });

    if (serverItems.length > 0) {
      html += '<div class="astro-sidebar-divider"></div>';
      html += '<div class="astro-sidebar-label">Server</div>';
      serverItems.forEach(function(item) { html += buildLink(item); });
    }

    html += '</div>';

    html += '<div class="astro-sidebar-user">' +
      '<div class="astro-sidebar-avatar">' + userInitials + '</div>' +
      '<div class="astro-sidebar-user-info">' +
        '<div class="astro-sidebar-user-name">' + userName + '</div>' +
        '<div class="astro-sidebar-user-role">' + userRole + '</div>' +
      '</div>' +
      '<a href="' + logoutUrl + '" class="astro-sidebar-logout" title="Logout">' +
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
          '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>' +
          '<polyline points="16 17 21 12 16 7"/>' +
          '<line x1="21" y1="12" x2="9" y2="12"/>' +
        '</svg>' +
      '</a>' +
    '</div>';

    sidebar.innerHTML = html;

    // Insert sidebar at the beginning of body
    document.body.prepend(sidebar);

    // Create mobile toggle button
    var toggle = document.createElement('button');
    toggle.className = 'astro-sidebar-toggle-mobile';
    toggle.setAttribute('aria-label', 'Toggle sidebar');
    toggle.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>';
    toggle.addEventListener('click', function() {
      sidebar.classList.toggle('open');
      var overlay = document.querySelector('.astro-sidebar-overlay');
      if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'astro-sidebar-overlay';
        overlay.addEventListener('click', function() {
          sidebar.classList.remove('open');
          overlay.classList.remove('active');
        });
        document.body.appendChild(overlay);
      }
      overlay.classList.toggle('active');
    });
    document.body.appendChild(toggle);
  }

  function applyDarkMode() {
    if (config.darkMode) {
      document.body.classList.add('astro-dark');
    }
  }

  function applyCompactMode() {
    if (config.compactMode) {
      document.body.classList.add('astro-compact');
    }
  }

  function initParticles() {
    if (!config.particles) return;
    var container = document.getElementById('astro-particles');
    if (!container) {
      container = document.createElement('div');
      container.id = 'astro-particles';
      container.style.cssText = 'position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden;';
      document.body.prepend(container);
    }
    var accentColor = config.accentColor1 || '#4f7cff';
    for (var i = 0; i < 15; i++) {
      var particle = document.createElement('div');
      var size = Math.random() * 8 + 4;
      particle.style.cssText = 
        'position:absolute;border-radius:50%;width:' + size + 'px;height:' + size + 'px;' +
        'left:' + (Math.random() * 100) + '%;top:' + (Math.random() * 100) + '%;' +
        'background:' + accentColor + ';opacity:0.15;' +
        'animation:astro-particle-float ' + (15 + Math.random() * 15) + 's linear infinite;' +
        'animation-delay:' + (Math.random() * 20) + 's;';
      container.appendChild(particle);
    }
  }

  function initScrollAnimations() {
    if (!('IntersectionObserver' in window)) return;
    var observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('astro-fade-in');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });
    var targets = document.querySelectorAll('[class*="card"], [class*="box"], [class*="server"]');
    targets.forEach(function(el, i) {
      el.style.animationDelay = (i * 0.05) + 's';
      observer.observe(el);
    });
  }

  function injectBranding() {
    if (config.logoUrl) {
      var logos = document.querySelectorAll('[class*="logo"] img, [class*="brand"] img');
      logos.forEach(function(img) { img.src = config.logoUrl; });
    }
  }

  function initToasts() {
    if (!document.querySelector('.astro-admin-toast-container')) {
      var container = document.createElement('div');
      container.className = 'astro-admin-toast-container';
      document.body.appendChild(container);
    }
    window.astroToast = function(type, message) {
      var container = document.querySelector('.astro-admin-toast-container');
      if (!container) return;
      var toast = document.createElement('div');
      toast.className = 'astro-admin-toast astro-admin-toast-' + type;
      toast.innerHTML = '<span>' + message + '</span>';
      container.appendChild(toast);
      setTimeout(function() {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(function() { toast.remove(); }, 300);
      }, 4000);
    };
  }

  function observeDynamicContent() {
    var observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(m) {
        if (m.type === 'childList' && m.addedNodes.length > 0) {
          m.addedNodes.forEach(function(node) {
            if (node.nodeType === 1) {
              var cards = node.querySelectorAll ? node.querySelectorAll('[class*="card"], [class*="box"]') : [];
              cards.forEach(function(card) { card.classList.add('astro-fade-in'); });
            }
          });
        }
      });
    });
    observer.observe(document.body, { childList: true, subtree: true });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
