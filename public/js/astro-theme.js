/**
 * Astro Theme - Main JavaScript
 * Handles theme initialization, dynamic styling, particles, 
 * sidebar behavior, toast notifications, and live interactions.
 * 
 * Loaded via dashboard wrapper on every page.
 */
(function() {
  'use strict';

  // Theme configuration injected by the wrapper
  var config = window.__ASTRO_THEME__ || {};

  /**
   * Initialize theme on DOM ready
   */
  function init() {
    if (!config.enabled) return;

    applyDarkMode();
    applyCompactMode();
    initSidebar();
    initParticles();
    initScrollAnimations();
    initConsoleEnhancements();
    initServerCardAnimations();
    injectBranding();
    initToasts();
    
    // Observe DOM changes for React-rendered content
    observeDynamicContent();
  }

  /**
   * Apply dark mode class
   */
  function applyDarkMode() {
    if (config.darkMode) {
      document.body.classList.add('astro-dark');
    }
  }

  /**
   * Apply compact mode class
   */
  function applyCompactMode() {
    if (config.compactMode) {
      document.body.classList.add('astro-compact');
    }
  }

  /**
   * Initialize responsive sidebar behavior
   */
  function initSidebar() {
    // Create mobile toggle button if it doesn't exist
    if (!document.querySelector('.astro-sidebar-toggle')) {
      var toggle = document.createElement('button');
      toggle.className = 'astro-sidebar-toggle';
      toggle.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>';
      toggle.setAttribute('aria-label', 'Toggle sidebar');
      toggle.addEventListener('click', function() {
        var sidebar = document.querySelector('.astro-sidebar') || 
                      document.querySelector('nav[class*="sidebar"]') ||
                      document.querySelector('aside');
        if (sidebar) {
          sidebar.classList.toggle('open');
          // Create overlay
          var overlay = document.querySelector('.astro-sidebar-overlay');
          if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'astro-sidebar-overlay';
            overlay.addEventListener('click', function() {
              sidebar.classList.remove('open');
              overlay.remove();
            });
            document.body.appendChild(overlay);
          }
        }
      });
      document.body.appendChild(toggle);
    }
  }

  /**
   * Initialize floating particles background
   */
  function initParticles() {
    if (!config.particles) return;

    var container = document.getElementById('astro-particles');
    if (!container) {
      container = document.createElement('div');
      container.id = 'astro-particles';
      container.className = 'astro-login-particles';
      container.style.cssText = 'position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden;';
      document.body.prepend(container);
    }

    // Create particles
    var particleCount = 15;
    var accentColor = config.accentColor1 || '#4f7cff';
    
    for (var i = 0; i < particleCount; i++) {
      var particle = document.createElement('div');
      particle.className = 'astro-particle';
      var size = Math.random() * 8 + 4;
      particle.style.cssText = 
        'width:' + size + 'px;height:' + size + 'px;' +
        'left:' + (Math.random() * 100) + '%;' +
        'top:' + (Math.random() * 100) + '%;' +
        'background:' + accentColor + ';' +
        'animation-delay:' + (Math.random() * 20) + 's;' +
        'animation-duration:' + (15 + Math.random() * 15) + 's;';
      container.appendChild(particle);
    }
  }

  /**
   * Initialize scroll-triggered animations
   */
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

    // Observe cards and elements
    var targets = document.querySelectorAll('.astro-card, [class*="server-card"], .box, [class*="card"]');
    targets.forEach(function(el, index) {
      el.style.animationDelay = (index * 0.05) + 's';
      observer.observe(el);
    });
  }

  /**
   * Enhance console page with theme styling
   */
  function initConsoleEnhancements() {
    // Find console/terminal elements and apply theme classes
    var terminals = document.querySelectorAll('[class*="terminal"], [class*="console-output"], [class*="font-mono"]');
    terminals.forEach(function(el) {
      if (el.closest('.astro-console')) return; // Already themed
      el.classList.add('astro-console-output');
    });
  }

  /**
   * Add hover animations to server cards
   */
  function initServerCardAnimations() {
    var cards = document.querySelectorAll('[class*="server-row"], [class*="ServerRow"], [class*="server-card"]');
    cards.forEach(function(card) {
      card.classList.add('astro-card-hover');
      
      // Add subtle mouse tracking effect
      card.addEventListener('mousemove', function(e) {
        var rect = card.getBoundingClientRect();
        var x = ((e.clientX - rect.left) / rect.width - 0.5) * 2;
        var y = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
        card.style.transform = 'translateY(-4px) perspective(1000px) rotateY(' + (x * 1) + 'deg) rotateX(' + (-y * 1) + 'deg)';
      });
      
      card.addEventListener('mouseleave', function() {
        card.style.transform = '';
      });
    });
  }

  /**
   * Inject branding elements (logo, footer text)
   */
  function injectBranding() {
    // Logo replacement
    if (config.logoUrl) {
      var logos = document.querySelectorAll('[class*="logo"], [class*="brand"], header img');
      logos.forEach(function(logo) {
        if (logo.tagName === 'IMG') {
          logo.src = config.logoUrl;
        } else if (logo.tagName === 'A' || logo.tagName === 'DIV') {
          var img = logo.querySelector('img');
          if (img) {
            img.src = config.logoUrl;
          }
        }
      });
    }

    // Footer text
    if (config.footerText) {
      var footers = document.querySelectorAll('footer, [class*="footer"]');
      footers.forEach(function(footer) {
        if (!footer.querySelector('.astro-custom-footer')) {
          var footerEl = document.createElement('div');
          footerEl.className = 'astro-custom-footer';
          footerEl.style.cssText = 'font-size:0.75rem;color:var(--astro-color-muted, #8ba0d8);padding:0.5rem;text-align:center;';
          footerEl.textContent = config.footerText;
          footer.appendChild(footerEl);
        }
      });
    }
  }

  /**
   * Initialize toast notification system
   */
  function initToasts() {
    // Create toast container if needed
    if (!document.querySelector('.astro-admin-toast-container')) {
      var container = document.createElement('div');
      container.className = 'astro-admin-toast-container';
      document.body.appendChild(container);
    }

    // Expose toast function globally
    window.astroToast = function(type, message) {
      var container = document.querySelector('.astro-admin-toast-container');
      if (!container) return;

      var toast = document.createElement('div');
      toast.className = 'astro-admin-toast astro-admin-toast-' + type;
      toast.innerHTML = '<span>' + message + '</span>';
      container.appendChild(toast);

      // Auto-remove after 4 seconds
      setTimeout(function() {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(function() { toast.remove(); }, 300);
      }, 4000);
    };
  }

  /**
   * Observe React DOM changes and re-apply theme styles
   */
  function observeDynamicContent() {
    // Mutation observer for React content updates
    var observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
          // New content added by React - apply animations
          mutation.addedNodes.forEach(function(node) {
            if (node.nodeType === 1) { // Element node
              var cards = node.querySelectorAll ? 
                node.querySelectorAll('[class*="server-row"], [class*="card"], [class*="box"]') : [];
              cards.forEach(function(card) {
                card.classList.add('astro-fade-in');
              });
            }
          });
        }
      });
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  }

  /**
   * Utility: Debounce function
   */
  function debounce(fn, delay) {
    var timeout;
    return function() {
      var context = this;
      var args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(function() {
        fn.apply(context, args);
      }, delay);
    };
  }

  /**
   * Handle window resize for responsive sidebar
   */
  window.addEventListener('resize', debounce(function() {
    var width = window.innerWidth;
    var sidebar = document.querySelector('.astro-sidebar') || 
                  document.querySelector('aside');
    
    if (width >= 1024 && sidebar) {
      sidebar.classList.remove('open');
      var overlay = document.querySelector('.astro-sidebar-overlay');
      if (overlay) overlay.remove();
    }
  }, 250));

  // Initialize on DOMContentLoaded or immediately if already loaded
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
