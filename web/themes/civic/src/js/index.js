import Map from './classes/Map';
import Translation from './classes/Translations';
import FixedHeader from './classes/FixedHeader';
// Without this import, 'dist/main.css' will not be generated on build.
import '../scss/main.scss';
class CivicThemeIndex {
  /**
   * Functions here will be called when
   * the document is  ready.
   */
  initOnDocumentLoad() {
    this.instantiateClasses(FixedHeader, 'header.header-fixed');
  }

  /**
   * Functions here will be called when the
   * window is fully loaded. This is the final
   * step in the render process and ensures that 
   * all elements are now in the DOM.
   */
  initOnWindowLoad() {
    this.instantiateClasses(Map, '#map');
    this.instantiateClasses(Translation, '#block-languageswitcher');
  }

  /**
   * 
   * @param {Object} ClassName - Class constructor
   * @param {string} selector - The query selector associated with this class
   * 
   * This function instantiates classes and calls their init method.
   * 
   * If a selector is provided, the class will be instantiated once
   * for every element found in the DOM. If no elements are found,
   * then no classes will be instantiated. This helps prevent
   * unnecessary JavaScript from being added to the page.
   */
  instantiateClasses(ClassName, selector) {
    if (!selector) {
      new ClassName();
    } else {
      const elements = document.querySelectorAll(selector);
      elements.forEach((element, index) => {
        var classInstance = new ClassName(element, index);
        if (typeof classInstance.init === 'function') {
          classInstance.init();
        }
      });
    }
  }
}

// Polyfill for forEach method
(function () {
  if (typeof NodeList.prototype.forEach === "function")
    return false;
  NodeList.prototype.forEach = Array.prototype.forEach;
})();
/** 
 * Instatiate the CivicThemeIndex Class
 * 
 * This is the recommended format to add JavaScript in Drupal.
 * https://www.drupal.org/docs/8/api/javascript-api/javascript-api-overview
 */
(function ($, Drupal) {
  var index = null;
  Drupal.behaviors.civicTheme = {
    attach: function (context) {
      // console.log('[Context]', context);

      $('.layout-container select').select2({
        dropdownParent: $('.layout-container'),
      });
      if (context === document) {
        index = new CivicThemeIndex();
        index.initOnDocumentLoad();
        $(window).on('load', function () {
          index.initOnWindowLoad();
        });
      }

      console.log(333333333);

      if ($(window).width() <= 1000) {

        var item_width = $('.paragraph--type--embedded-blocks-section.bg-big-light-blue #block-views-block-featured-content-block-1 .news .news-items .views-row').width();
        var items_count = 0;
        $('.paragraph--type--embedded-blocks-section.bg-big-light-blue #block-views-block-featured-content-block-1 .news .news-items .views-row').each(function () {
          items_count++;
        });
        $('.paragraph--type--embedded-blocks-section.bg-big-light-blue #block-views-block-featured-content-block-1 .news .news-items').width(item_width * items_count);
      }

    }



  };
})(jQuery, Drupal);


