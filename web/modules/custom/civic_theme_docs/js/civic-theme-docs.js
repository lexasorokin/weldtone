(function($, Drupal){
  Drupal.behaviors.civicThemeDocs = {
    attach: function(context) {
      $(window).once('civicThemeDocsWindowLoad').on('load', function() {
        var $iframeContainer = $('div[data-iframe-append-src');

        if ($iframeContainer.length) {
          var src = $iframeContainer.data().iframeAppendSrc;
          var $iframe = $('<iframe width="560" height="315" src="' + src + '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
          $iframeContainer.append($iframe);
        }
      });
    }
  }
})(jQuery, Drupal);