<?php if (!defined(basename(__FILE__) . '-included')) return http_response_code(404); ?>
    <script src="<?= JS_PATH ?>jquery-3.3.1.min.js"></script>
    <script src="/user/src/js/sweetalert2.min.js"></script>

<?php if (isset($this->page_data['name']) && $this->page_data['name'] === PAGE_CHECKOUT && !$this->has_already_paid): # Load stripe's JS ?>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="/user/src/js/charge.js"></script>
<?php endif; ?>
    <script src="/user/src/js/user.js?<?= time() ?>"></script>
<?php if ($this->user || !empty($this->user)): ?>
<?php if (array_key_exists('id', $this->user) || array_key_exists('mentorID', $this->user)): ?>
    <script src="/user/src/js/exif.min.js"></script>
    <script src="/user/src/trumbowyg/trumbowyg.min.js"></script>
    <script src="/user/src/js/croppie.min.js"></script>
    <script>
      window._USER_DATA = <?= json_encode([
        'id' => $id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'name' => $name,
        'avatar_url' =>  $avatar_url,
        'username' => $username,
        'token' => $uid_token,
        'is_admin' => (bool) $is_admin,
        'mentorID' => $mentor_id]) ?>;
    </script>
    <script>
      var navCollapse = $('.nl-collapse');

      if (elementExists('#nav-toggler')) {
        var $nav = $('#nav'),
            $navBackdrop = $('#nav-backdrop');
        $('#nav-toggler,#nav-backdrop').on('click', function() {
          $nav.toggleClass('active');
          $navBackdrop.toggleClass('active');
        });
      }

      $(window).on('load', function() {
        navCollapse.each(function() {
          var $nav = $(this);
          $nav.children('.nav-link').each(function() {
            if ($(this).hasClass('active'))
              $nav.addClass('expanded');
          });
        });
      });          

      navCollapse.each(function(idx) {
        var $nav = $(this);
        $nav.on('click', function() {
          removeToggledMenu($nav);
          $nav.toggleClass('expanded');
        });
      });

      function removeToggledMenu(exception) {
        navCollapse.each(function() {
          var collapsible = $(this);
          if (collapsible !== exception && !exception.hasClass('expanded') && collapsible.hasClass('expanded'))
            collapsible.removeClass('expanded');
        });
      }

      var $dropdownToggle = $('.dropdown-toggle'),
          $dropdown = $('.dropdown');

      $dropdownToggle.each(function() {
        var $toggler = $(this);
        $toggler.on('click', function (e) {
          e.stopPropagation();
          $($toggler.data('target')).toggleClass('active');
        });
      });

      function removeDropdowns() {
        $dropdown.each(function () {
          var $d = $(this);
          if ($d.hasClass('active'))
            $d.removeClass('active');
        });
      }

      $(document).on('click', function() {
        removeDropdowns();
      });

      $(window).on('resize', function() {
        removeDropdowns();
      });

      $dropdown.on('click', function(e) {
        e.stopPropagation();
      });
    </script>
<?php endif; ?>
<?php endif; ?>
  </body>
</html>