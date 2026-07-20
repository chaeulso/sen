/**
 * Astro Theme - Main JavaScript
 * Handles theme initialization, custom sidebar injection, heartbeat loader,
 * dynamic styling, particles, and live interactions.
 */
(function() {
  'use strict';

  var config = window.__ASTRO_THEME__ || {};
  var sidebarInjected = false;
  var loaderInjected = false;

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
    if (loaderInjected) return;
    loaderInjected = true;

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
      '  ' + '21% { transform: scale(1); opacity: 0.8; }',
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
   * Inject custom Astro sidebar (replaces Pterodactyl's)
   */
  function injectCustomSidebar() {
    if (sidebarInjected) return;

    var accent1 = config.accentColor1 || '#4f7cff';
    var accent2 = config.accentColor2 || '#7cc2ff';
    var sidebarW = 280;
    var radius = 24;
    var blur = 20;
    var logoUrl = config.logoUrl || '';

    // Inject sidebar styles
    if (!document.getElementById('astro-sidebar-style')) {
      var style = document.createElement('style');
      style.id = 'astro-sidebar-style';
      style.textContent = [
        '/* Astro Custom Sidebar */',
        '#astro-custom-sidebar {',
        '  position: fixed;',
        '  top: 16px;',
        '  left: 16px;',
        '  bottom: 16px;',
        '  width: ' + sidebarW + 'px;',
        '  background: rgba(255, 255, 255, 0.60);',
        '  -webkit-backdrop-filter: blur(' + blur + 'px) saturate(1.5);',
        '  backdrop-filter: blur(' + blur + 'px) saturate(1.5);',
        '  border: 1px solid rgba(255, 255, 255, 0.75);',
        '  border-radius: ' + radius + 'px;',
        '  box-shadow: 0 8px 32px -8px rgba(48, 87, 196, 0.10), inset 0 1px 0 rgba(255, 255, 255, 0.7);',
        '  z-index: 1000;',
        '  display: flex;',
        '  flex-direction: column;',
        '  padding: 20px;',
        '  overflow-y: auto;',
        '  overflow-x: hidden;',
        '  transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);',
        '  font-family: Inter, system-ui, sans-serif;',
        '}',
        '#astro-custom-sidebar::-webkit-scrollbar { width: 4px; }',
        '#astro-custom-sidebar::-webkit-scrollbar-thumb { background: rgba(96, 132, 220, 0.2); border-radius: 99px; }',
        '.astro-sidebar-logo {',
        '  display: flex;',
        '  align-items: center;',
        '  gap: 12px;',
        '  margin-bottom: 24px;',
        '  padding: 4px 0;',
        '}',
        '.astro-sidebar-logo-icon {',
        '  width: 40px;',
        '  height: 40px;',
        '  border-radius: 12px;',
        '  background: linear-gradient(135deg, ' + accent1 + ', ' + accent2 + ');',
        '  display: flex;',
        '  align-items: center;',
        '  justify-content: center;',
        '  flex-shrink: 0;',
        '}',
        '.astro-sidebar-logo-icon img { width: 28px; height: 28px; object-fit: contain; }',
        '.astro-sidebar-logo-icon svg { width: 22px; height: 22px; color: white; }',
        '.astro-sidebar-logo-text {',
        '  font-family: Outfit, Inter, sans-serif;',
        '  font-weight: 800;',
        '  font-size: 18px;',
        '  background: linear-gradient(135deg, ' + accent1 + ', ' + accent2 + ');',
        '  -webkit-background-clip: text;',
        '  -webkit-text-fill-color: transparent;',
        '  letter-spacing: -0.02em;',
        '}',
        '.astro-sidebar-logo-sub {',
        '  font-size: 10px;',
        '  color: #8ba0d8;',
        '  font-weight: 500;',
        '  letter-spacing: 0.04em;',
        '  text-transform: uppercase;',
        '}',
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
        '  padding: 12px 14px 6px;',
        '}',
        '.astro-sidebar-link {',
        '  display: flex;',
        '  align-items: center;',
        '  gap: 12px;',
        '  padding: 10px 14px;',
        '  border-radius: 12px;',
        '  font-size: 13px;',
        '  font-weight: 600;',
        '  color: #2b3a67;',
        '  text-decoration: none;',
        '  transition: all 0.2s ease;',
        '  position: relative;',
        '}',
        '.astro-sidebar-link:hover {',
        '  background: rgba(255, 255, 255, 0.5);',
        '  color: #1e2a52;',
        '  transform: translateX(2px);',
        '}',
        '.astro-sidebar-link.active {',
        '  background: linear-gradient(135deg, ' + accent1 + ', ' + accent2 + ');',
        '  color: #fff;',
        '  box-shadow: 0 10px 22px -8px rgba(79, 124, 255, 0.7);',
        '}',
        '.astro-sidebar-link.active:hover {',
        '  transform: none;',
        '}',
        '.astro-sidebar-link svg {',
        '  width: 18px;',
        '  height: 18px;',
        '  flex-shrink: 0;',
        '  opacity: 0.7;',
        '}',
        '.astro-sidebar-link.active svg { opacity: 1; }',
        '.astro-sidebar-divider {',
        '  height: 1px;',
        '  background: rgba(120, 145, 220, 0.15);',
        '  margin: 8px 0;',
        '}',
        '.astro-sidebar-user {',
        '  background: rgba(249, 251, 255, 0.86);',
        '  border-radius: 16px;',
        '  padding: 12px;',
        '  display: flex;',
        '  align-items: center;',
        '  gap: 12px;',
        '  border: 1px solid rgba(255, 255, 255, 0.5);',
        '  margin-top: auto;',
        '}',
        '.astro-sidebar-avatar {',
        '  width: 36px;',
        '  height: 36px;',
        '  border-radius: 10px;',
        '  background: linear-gradient(135deg, ' + accent1 + ', ' + accent2 + ');',
        '  display: flex;',
        '  align-items: center;',
        '  justify-content: center;',
        '  color: #fff;',
        '  font-weight: 700;',
        '  font-size: 12px;',
        '  flex-shrink: 0;',
        '}',
        '.astro-sidebar-user-name {',
        '  font-size: 13px;',
        '  font-weight: 700;',
        '  color: #1e2a52;',
        '}',
        '.astro-sidebar-user-role {',
        '  font-size: 11px;',
        '  color: #8ba0d8;',
        '}',
        '/* Hide original Pterodactyl sidebar */',
        'nav[class*="fixed"][class*="inset"], aside[class*="fixed"][class*="inset"],',
        'div[data-sidebar], div[class*="sidebar-container"],',
        'nav[style*="position: fixed"], nav[style*="position:fixed"] {',
        '  display: none !important;',
        '  visibility: hidden !important;',
        '  opacity: 0 !important;',
        '  width: 0 !important;',
        '  overflow: hidden !important;',
        '}',
        '/* Offset main content for our sidebar */',
        '#app > div > div:first-child,',
        'main,',
        '[class*="content-wrapper"],',
        'div[style*="margin-left"] {',
        '  margin-left: ' + (sidebarW + 32) + 'px !important;',
        '}',
        '@media (max-width: 1023px) {',
        '  #astro-custom-sidebar { transform: translateX(-110%); }',
        '  #astro-custom-sidebar.open { transform: translateX(0); }',
        '  #app > div > div:first-child, main, [class*="content-wrapper"],',
        '  div[style*="margin-left"] { margin-left: 0 !important; }',
        '}',
      ].join('\n');
      document.head.appendChild(style);
    }

    // Wait for React to render, then inject sidebar
    function tryInject() {
      if (document.getElementById('astro-custom-sidebar')) {
        sidebarInjected = true;
        return;
      }

      var app = document.getElementById('app') || document.body;
      var sidebar = document.createElement('nav');
      sidebar.id = 'astro-custom-sidebar';

      // Get current user info if available
      var userName = 'Admin';
      var userRole = 'Administrator';
      var userInitials = 'AD';

      // Try to get user info from the page
      var userEl = document.querySelector('[class*="avatar"], [class*="user"]');
      if (userEl) {
        var nameEl = userEl.querySelector('[class*="name"]') || userEl.closest('div');
        if (nameEl && nameEl.textContent) {
          userName = nameEl.textContent.trim().split('\n')[0] || userName;
          userInitials = userName.split(' ').map(function(w) { return w[0]; }).join('').substring(0, 2).toUpperCase();
        }
      }

      // Determine current page for active state
      var currentPath = window.location.pathname;

      var logoContent = logoUrl
        ? '<img src="' + logoUrl + '" alt="Logo">'
        : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';

      var navItems = [
        { label: 'Overview', icon: '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>', href: '/' },
        { label: 'Servers', icon: '<rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/>', href: '/server' },
      ];

      var serverItems = [];
      // Check if we're on a server page
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
        '<div>' +
          '<div class="astro-sidebar-user-name">' + userName + '</div>' +
          '<div class="astro-sidebar-user-role">' + userRole + '</div>' +
        '</div>' +
      '</div>';

      sidebar.innerHTML = html;

      // Insert sidebar into body
      document.body.prepend(sidebar);

      // Add mobile toggle
      var toggle = document.createElement('button');
      toggle.className = 'astro-sidebar-toggle';
      toggle.setAttribute('aria-label', 'Toggle sidebar');
      toggle.style.cssText = 'position:fixed;top:16px;left:16px;z-index:1001;width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,0.6);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.75);box-shadow:0 8px 32px -8px rgba(48,87,196,0.1);display:none;align-items:center;justify-content:center;cursor:pointer;color:#2b3a67;transition:all 0.2s ease;';
      toggle.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>';
      toggle.addEventListener('click', function() {
        sidebar.classList.toggle('open');
      });
      document.body.appendChild(toggle);

      // Show toggle on mobile
      function checkMobile() {
        toggle.style.display = window.innerWidth < 1024 ? 'flex' : 'none';
      }
      checkMobile();
      window.addEventListener('resize', checkMobile);

      // Click outside to close on mobile
      document.addEventListener('click', function(e) {
        if (window.innerWidth < 1024 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
          sidebar.classList.remove('open');
        }
      });

      sidebarInjected = true;
    }

    // Try immediately and then retry until React renders
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function() { setTimeout(tryInject, 500); });
    } else {
      setTimeout(tryInject, 500);
    }

    // Also observe for React re-renders
    var observer = new MutationObserver(function() {
      if (!sidebarInjected) {
        tryInject();
      }
    });
    observer.observe(document.body, { childList: true, subtree: true });

    // Stop observing after success
    setTimeout(function() { observer.disconnect(); }, 10000);
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
