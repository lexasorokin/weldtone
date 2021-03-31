(function($, Drupal) {
  Drupal.behaviors.textAlert = {
    attach: function(context) {
      if (context === document) {
        var $banner = $('#emergency-banner-wrapper');
        if ($banner.length) {
          var $header = $('header.santacityheader');
          var $mainCanvas = $('.dialog-off-canvas-main-canvas');
          
          function toggleAbsoluteAndFixedPositioning() {
            var topOfCanvas = $mainCanvas.offset().top;
            var bottomOfBanner = $banner.offset().top + $banner.height();
            var shouldBeFixed = window.pageYOffset + topOfCanvas >= bottomOfBanner;

            if (shouldBeFixed && !$header.hasClass('is-fixed')) {
              $header.css('top', topOfCanvas + 'px');
              $header.addClass('is-fixed');
            } else if (!shouldBeFixed && $header.hasClass('is-fixed')) {
              $header.css('top', 'auto');
              $header.removeClass('is-fixed');
            }
          }

          $(window).on('scroll', toggleAbsoluteAndFixedPositioning);
        }
      }
    }
  }
})(jQuery, Drupal);