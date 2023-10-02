<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); ?>
<?php if (!defined('HIDE_HEADER_AND_FOOTER')): # Don't show footer for the login, registration or other pages ?>
    <footer class="footer">
      <div class="wrapper">
        <div class="footer-contents">
          <div class="footer-container">
            <div class="footer-brand">
              <a href="/" title="<?= SITE_NAME ?>" class="footer-brand-icon">
                <img src="<?= ICONS_PATH ?>logo.png" class="footer-brand-icon-img" alt="<?= SITE_NAME ?>" />
              </a>
              <p class="footer-paragraph">We strive to provide a quality trading experience for both our students and investors.</p>
              <p class="footer-paragraph">&copy; <?= date('Y') ?> - All Rights Reserved</p>
              <div class="footer-icons">
                <a href="https://www.facebook.com/goldenrules.trade/" class="footer-icon-link" title="Like us on Facebook" target="_blank">
                  <svg class="footer-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path class="footer-icon-fill" d="m15.997 3.985h3.191v-3.816c-.378-.052-1.678-.169-3.192-.169-3.159 0-5.323 1.987-5.323 5.639v3.361h-3.486v4.266h3.486v10.734h4.274v-10.733h3.345l.531-4.266h-3.877v-2.939c.001-1.233.333-2.077 2.051-2.077z" />
                  </svg>
                </a>
                <!--<a href="https://twitter.com/GoldenrulesSup" class="footer-icon-link" title="Follow us on Twitter" target="_blank">
                  <svg class="footer-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17 15">
                    <path class="footer-icon-fill" d="M15.1453 4.32361V4.76161C15.1453 6.37661 14.7963 7.93561 14.0983 9.43961C13.4003 10.9426 12.3223 12.2156 10.8643 13.2586C9.40529 14.3026 7.71829 14.8236 5.80029 14.8236C3.94629 14.8236 2.27029 14.2966 0.769287 13.2416C0.936287 13.2646 1.19629 13.2756 1.55029 13.2756C3.11329 13.2756 4.47729 12.7706 5.64429 11.7616C4.93629 11.7616 4.30029 11.5316 3.73829 11.0716C3.17625 10.6126 2.76285 9.9974 2.55029 9.30361C2.69629 9.34861 2.90529 9.37061 3.17529 9.37061C3.48829 9.37061 3.77929 9.33661 4.05029 9.27061C3.30029 9.09061 2.67529 8.67561 2.17529 8.02461C1.67529 7.37461 1.42529 6.63361 1.42529 5.80361V5.73661C1.90529 6.02761 2.39429 6.17361 2.89429 6.17361C1.91429 5.50061 1.42529 4.52461 1.42529 3.24561C1.42529 2.66261 1.58229 2.06761 1.89529 1.46161C3.62329 3.79561 5.87329 5.02961 8.64529 5.16361C8.58294 4.89911 8.55107 4.62835 8.55029 4.35661C8.55029 3.39161 8.86829 2.56161 9.50329 1.86661C10.1403 1.17061 10.9163 0.823608 11.8333 0.823608C12.7913 0.823608 13.5933 1.19361 14.2393 1.93361C15.0513 1.75461 15.7493 1.47361 16.3323 1.09361C16.0823 1.92361 15.6033 2.57361 14.8953 3.04461C15.6253 2.93261 16.2493 2.74161 16.7703 2.47261C16.3417 3.18034 15.7916 3.80694 15.1453 4.32361Z" />
                  </svg>
                </a>-->
                <a href="https://www.instagram.com/goldenrules.trade/" class="footer-icon-link" title="Follow us on Instagram" target="_blank">
                  <svg class="footer-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path class="footer-icon-fill" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                  </svg>
                </a>
              </div>
            </div>
            <div class="footer-list-container">
              <ul class="footer-list">
                <h4 class="footer-list-title">Navigate</h4>
                <li class="footer-list-item"><a href="/" class="footer-link">Home</a></li>
                <li class="footer-list-item"><a href="/news" class="footer-link">News</a></li>
                <li class="footer-list-item"><a href="/about" class="footer-link">About</a></li>
                <li class="footer-list-item"><a href="/trading-analysis" class="footer-link">Analysis</a></li>
              </ul>
              <ul class="footer-list">
                <h4 class="footer-list-title">legal</h4>
                <li class="footer-list-item"><a href="/legal/terms-and-conditions" class="footer-link" target="_blank">Terms</a></li>
                <li class="footer-list-item"><a href="/legal/privacy-policy" class="footer-link" target="_blank">Privacy Policy</a></li>
                <li class="footer-list-item"><a href="/legal/privacy-policy/#cookies" class="footer-link" target="_blank">Cookies Policy</a></li>
                <li class="footer-list-item"><a href="/legal/refund-policy" class="footer-link" target="_blank">Refund Policy</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <div class="sticky-banner" id="cb">
      <div class="wrapper">
        <p><span class="txt-gold"><?= SITE_NAME ?></span> uses cookies to ensure you get the best experience on our site. By continuing to browse this site, you agree to our <a href="/legal/privacy-policy/#cookies" class="link-gold">Cookie Policy</a>.</p>
        <button class="btn btn-md btn-gold txt-uppercase">got it</button>
      </div>
    </div>
<?php endif; ?>

    <script src="<?= JS_PATH ?>jquery-3.3.1.min.js"></script>
    <script src="<?= JS_PATH ?>jquery.lazy.min.js"></script>
    <script src="<?= JS_PATH ?>core.js?<?= time() ?>"></script>
    <script>
      var $body = $([document.documentElement, document.body]),
          $w = $(window);

      /** ---------------------------------------------------------------------
        * Scroll to cookies section on load if the #cookies hash is found
        * ---------------------------------------------------------------------
        */
      if ($('#cookie-section').length) {
        $w.on('load', function() {
          if ($(this)[0].location.hash === '#cookies') {
            var element = $('#cookie-section');

            if (!element.hasClass('highlighted')) {
              element.addClass('highlighted');
              $body.animate({
                scrollTop: element.offset().top + -100
              }, 500);
            }
          }
        });
      }

      $('#learn-more').on('click', function() {
        $body.animate({
          scrollTop: $('#mentorship').offset().top
        }, 500);
      });

      $(document).ready(function() {
        /** ---------------------------------------------------------------------
          * Image lazy loading
          * ---------------------------------------------------------------------
          */

        $('.grt-lzy').lazy();

        /** ---------------------------------------------------------------------
          * Navigation bar script
          * ---------------------------------------------------------------------
          */

        var $navToggler = $('#navbar-toggler');

        if ($navToggler.length) {
          $navToggler.on('click', function() {
            var $toggler = $(this);

            $toggler.toggleClass('is-active');
            $body.css('overflowY', $toggler.hasClass('is-active') ? 'hidden' : 'auto');
            $('#navbar,#navbar-backdrop').toggleClass('is-active');
          });
        }

        /** ---------------------------------------------------------------------
          * Cookie consent banner script
          * ---------------------------------------------------------------------
          */

        if ($('#cb').length) {
          var c = 'grt_cookies_consent_WxLqR7cdZ';

          if (!localStorage.getItem(c)) {
            var $cb = $('#cb');
            $cb.show()
               .find('button')
               .on('click', function() {
                  $cb.hide();
                  localStorage.setItem(c, true);
            });
          }
        }

        /** ---------------------------------------------------------------------
          * Dropdown script
          * ---------------------------------------------------------------------
          */

        var $dropdown = $('.dropdown');

        $('.dropdown-toggle').each(function() {
          var $toggler = $(this);

          $toggler.on('click', function(e) {
            e.stopPropagation();
            $($toggler.data('target')).toggleClass('active');
          });
        });

        function removeDropdowns() {
          $dropdown.each(function() {
            var $d = $(this);

            if ($d.hasClass('active'))
              $d.removeClass('active');
          });
        }

        $dropdown.on('click', function(e) {
          e.stopPropagation();
        });

        /** ---------------------------------------------------------------------
          * Collapsible script
          * ---------------------------------------------------------------------
          */

        var $collapseToggle = $('.toggle-collapse');

        if ($collapseToggle.length > 0) {
          $collapseToggle.each(function() {
            var $toggler = $(this);

            $toggler.on('click', function(e) {
              var $collapseID = $($toggler.data('collapse'));

              $collapseID.toggleClass('collapsed');

              var title = (!$collapseID.hasClass('collapsed')) ? 'Show less' : 'Show more';
              $toggler.html(title).attr('title', title);
            });
          });
        }

        $w.on('click resize', function() {
          removeDropdowns();
        });
      });
    </script>
  </body>
</html>