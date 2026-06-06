(function () {
   'use strict';

   const config = window.enfastoolConfig || null;
   if (!config || !config.enabled) {
      return;
   }

   const onReady = () => {
      document.body.classList.add('enfastool-login-enhanced');

      applyColors(config);
      applyBackground(config);
      renderHeader(config);
      renderFooter(config);
   };

   if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', onReady, { once: true });
   } else {
      onReady();
   }

   function applyColors(cfg) {
      setVar('--enfas-primary', cfg.primary_color);
      setVar('--enfas-accent', cfg.accent_color);
      setVar('--enfas-card-bg', cfg.card_background);
      setVar('--enfas-radius', cfg.card_border_radius);
   }

   function applyBackground(cfg) {
      if (cfg.background_type === 'image' && cfg.background_image) {
         document.body.style.backgroundImage = `url("${cssEscapeUrl(cfg.background_image)}")`;
         return;
      }

      if (cfg.background_type === 'solid') {
         document.body.style.background = cfg.background_solid_color;
         return;
      }

      document.body.style.background = `linear-gradient(140deg, ${cfg.background_gradient_start}, ${cfg.background_gradient_end})`;
   }

   function renderHeader(cfg) {
      const target = findLoginTarget();
      if (!target) {
         return;
      }

      const header = document.createElement('div');
      header.className = 'enfastool-login-header';

      if (cfg.logo_path) {
         const img = document.createElement('img');
         img.className = 'enfastool-login-logo';
         img.src = cfg.logo_path;
         img.alt = 'EnfasTool Logo';
         header.appendChild(img);
      }

      if (cfg.title) {
         const title = document.createElement('h1');
         title.className = 'enfastool-login-title';
         title.textContent = cfg.title;
         header.appendChild(title);
      }

      if (cfg.subtitle) {
         const subtitle = document.createElement('p');
         subtitle.className = 'enfastool-login-subtitle';
         subtitle.textContent = cfg.subtitle;
         header.appendChild(subtitle);
      }

      if (header.children.length > 0) {
         target.insertAdjacentElement('beforebegin', header);
      }
   }

   function renderFooter(cfg) {
      if (!cfg.footer_text) {
         return;
      }

      const footer = document.createElement('div');
      footer.className = 'enfastool-login-footer';
      footer.textContent = cfg.footer_text;

      const target = findLoginTarget();
      if (target) {
         target.insertAdjacentElement('afterend', footer);
      }
   }

   function setVar(name, value) {
      if (!value) {
         return;
      }

      document.documentElement.style.setProperty(name, value);
   }

   function findLoginTarget() {
      return document.querySelector('#firstboxlogin')
         || document.querySelector('.login-card')
         || document.querySelector('.card')
         || document.querySelector('form')
         || null;
   }

   function cssEscapeUrl(url) {
      return String(url).replace(/"/g, '\\"');
   }
})();
