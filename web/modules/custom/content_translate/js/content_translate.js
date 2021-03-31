(function($, Drupal) { 
  
  function ContentTranslate() {
    this.aPIKey = drupalSettings.contentTranslate.aPIKey

    this.init = function() {
      this.createTranslateButton();
    }

    this.createTranslateButton = function() {
      var button = $('<a href="?translatecontent=true" class="button button--primary">Translate Content</a>');
      $('form.node-form').prepend(button);
    }
  }

  Drupal.behaviors.contentTranslateBehavior = {
    attach: function(context, settings) {
      if (context == document) {
        var contentTranslate = new ContentTranslate();
        contentTranslate.init();
      }
    }
  }
})(jQuery, Drupal);