/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/dist";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/classes/FixedHeader.js":
/*!***************************************!*\
  !*** ./src/js/classes/FixedHeader.js ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var FixedHeader = /*#__PURE__*/function () {
  function FixedHeader(header) {
    _classCallCheck(this, FixedHeader);

    this.header = header;
    this.layoutContainer = document.querySelector('.layout-container');
    this.swapAbsoluteAndFixed = this.swapAbsoluteAndFixed.bind(this);
  }

  _createClass(FixedHeader, [{
    key: "init",
    value: function init() {
      window.addEventListener('scroll', this.swapAbsoluteAndFixed);
      this.swapAbsoluteAndFixed();
    }
  }, {
    key: "swapAbsoluteAndFixed",
    value: function swapAbsoluteAndFixed() {
      var top = this.layoutContainer.getBoundingClientRect().top;
      var isCurrentlyFixed = this.header.classList.contains('is-fixed');

      if (top >= 0 && isCurrentlyFixed) {
        this.header.classList.remove('is-fixed');
      } else if (top < 0 && !isCurrentlyFixed) {
        this.header.classList.add('is-fixed');
      }
    }
  }]);

  return FixedHeader;
}();

/* harmony default export */ __webpack_exports__["default"] = (FixedHeader);

/***/ }),

/***/ "./src/js/classes/Map.js":
/*!*******************************!*\
  !*** ./src/js/classes/Map.js ***!
  \*******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Map = /*#__PURE__*/function () {
  function Map() {
    _classCallCheck(this, Map);

    this.mapLocations = drupalSettings.mapLocations;
    this.filterZip = document.getElementById('filter-zip');
    this.filterCity = document.getElementById('filter-city');
    this.filterTax1 = document.getElementById('filter-taxonomy-1');
    this.filterTax2 = document.getElementById('filter-taxonomy-2');
    this.filtersSubmit = document.getElementById('map-filter-submit');
    this.filterMarkers = this.filterMarkers.bind(this);
  }

  _createClass(Map, [{
    key: "init",
    value: function init() {
      var _this = this;

      this.mapLocations.forEach(function (location) {
        return _this.createLocation(location);
      });
      this.filtersSubmit.addEventListener('click', this.filterMarkers); // console.log('[Locations]', this.mapLocations);
    }
  }, {
    key: "filterMarkers",
    value: function filterMarkers(e) {
      var _this2 = this;

      e.preventDefault();
      this.mapLocations.forEach(function (location) {
        if (_this2.zipFilter(location) && _this2.cityFilter(location) && _this2.taxonomyFilter(location, _this2.filterTax1) && _this2.taxonomyFilter(location, _this2.filterTax2)) {
          location.marker.setMap(map);
        } else {
          location.marker.setMap(null);
        }
      });
    }
  }, {
    key: "strip",
    value: function strip(string) {
      return string.toLowerCase().replace(/\s/g, '');
    }
  }, {
    key: "zipFilter",
    value: function zipFilter(location) {
      if (this.filterZip === null) {
        return true;
      }

      return this.filterZip.value === '' || this.filterZip.value === location.zip;
    }
  }, {
    key: "cityFilter",
    value: function cityFilter(location) {
      if (this.filterCity === null) {
        return true;
      }

      return this.filterCity.value === '' || this.strip(this.filterCity.value) === this.strip(location.city);
    }
  }, {
    key: "taxonomyFilter",
    value: function taxonomyFilter(location, taxFilter) {
      if (taxFilter === null || taxFilter.value === '0') {
        return true;
      }

      for (var i = 0; i < location.tags.length; i += 1) {
        if (location.tags[i].tid === taxFilter.value) {
          return true;
        }
      }

      return false;
    }
  }, {
    key: "createLocation",
    value: function createLocation(location) {
      var infowindow = new google.maps.InfoWindow({
        content: this.createInfoWindow(location)
      });
      var marker = new google.maps.Marker({
        position: {
          lat: parseFloat(location.lat),
          lng: parseFloat(location.lng)
        },
        map: map,
        title: location.name // animation: google.maps.Animation.DROP,

      });
      marker.addListener('click', function () {
        infowindow.open(map, marker);
      });
      location.marker = marker;
    }
  }, {
    key: "generateDirectionsUrl",
    value: function generateDirectionsUrl(location) {
      var directionsUrl = 'https://google.com/maps/dir//';
      directionsUrl += location.addr.split(' ').join('+') + ',+';
      directionsUrl += location.city.split(' ').join('+') + ',+';
      directionsUrl += location.state + '+';
      directionsUrl += location.zip;
      directionsUrl += '/@' + location.lat + ',' + location.lng + ',15z';
      return directionsUrl;
    }
  }, {
    key: "renderTagIcons",
    value: function renderTagIcons(tags) {
      return tags.reduce(function (html, tag) {
        switch (tag.iconType) {
          case 'icon':
            html += "<div class=\"pl-3\"><i class=\"".concat(tag.iconValue, " fa-3x\"></i></div>");
            return html;

          case 'image':
            html += "<img class=\"pl-3\" src=\"".concat(tag.iconValue, "\">");
            return html;

          default:
            return html;
        }
      }, '');
    }
  }, {
    key: "createInfoWindow",
    value: function createInfoWindow(location) {
      return "\n      <div class=\"map-info-window\">\n        <div class=\"address-container\">\n          <h5>".concat(location.name, "</h5>\n          <p>\n            ").concat(location.addr, "<br>\n            ").concat(location.city, ", ").concat(location.state, " ").concat(location.zip, "\n          </p>\n        </div>\n        <div class=\"d-flex\">\n          <div>\n            <button class=\"btn btn-primary btn-sm mb-1\">View &#8227;</button>\n            <br>\n            <a class=\"btn btn-primary btn-sm\" target=\"_blank\" href=\"").concat(this.generateDirectionsUrl(location), "\">\n              Get Directions &#8227;\n            </a>\n          </div>\n          <div class=\"\">\n            <div class=\"flex justify-start align-center map-icons-container\">\n              ").concat(this.renderTagIcons(location.tags), "\n            </div>\n          </div>\n        </div>\n      </div>");
    }
  }]);

  return Map;
}();

/* harmony default export */ __webpack_exports__["default"] = (Map);

/***/ }),

/***/ "./src/js/classes/Translations.js":
/*!****************************************!*\
  !*** ./src/js/classes/Translations.js ***!
  \****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Translations = /*#__PURE__*/function () {
  function Translations(dropdownWrapper) {
    _classCallCheck(this, Translations);

    this.translateLanguageCodes = ['es', 'ru', 'ar', 'zh-hans'];
    this.dropdownWrapper = dropdownWrapper;
    this.dropdownButton = dropdownWrapper.querySelector('.button.language-link');
    this.linkEndingsToNotTranslate = ['.pdf', '.doc', '.docx', '.png', '.jpg', '.jpeg', '.xls', '.psd', '.csv'];
    this.anchorTags = document.querySelectorAll('.layout-container main a');
  }

  _createClass(Translations, [{
    key: "init",
    value: function init() {
      this.updateLinksToMatchLanguageCode();
      this.performGTranslate();
    }
  }, {
    key: "updateLinksToMatchLanguageCode",
    value: function updateLinksToMatchLanguageCode() {
      var _this = this;

      var pathPrefix = location.pathname.split('/')[1]; // Check if the pathname starts with a langcode

      if (pathPrefix.length === 2 && this.translateLanguageCodes.indexOf(pathPrefix) >= 0) {
        var origin = location.origin;
        var originWithLangCode = location.origin + '/' + pathPrefix;
        var originLength = location.origin.length;
        this.anchorTags.forEach(function (anchor) {
          // Loop through the links to see if they're pointing to a file, in which case we don't change the link.
          var ignoreLink = false;

          _this.linkEndingsToNotTranslate.forEach(function (linkEnding) {
            if (anchor.href.indexOf(linkEnding) === anchor.href.length - linkEnding.length) {
              ignoreLink = true;
            }
          }); // Check that the link is internal and that it doesn't already have the langcode prefix


          if (!ignoreLink && anchor.href.indexOf(origin) === 0 && anchor.href.indexOf(originWithLangCode) === -1) {
            anchor.href = originWithLangCode + anchor.href.slice(originLength);
          }
        });
      }
    }
  }, {
    key: "toggleTranslateStatus",
    value: function toggleTranslateStatus(selector, shouldTranslate) {
      var element = document.querySelector(selector);

      if (element) {
        if (shouldTranslate) {
          element.classList.remove('notranslate');
        } else {
          element.classList.add('notranslate');
        }
      }
    }
  }, {
    key: "performGTranslate",
    value: function performGTranslate() {
      if (drupalSettings.doGoogleTranslate === 'yes') {
        if (drupalSettings.defaultLangCode !== drupalSettings.selectedLangCode) {
          this.dropdownWrapper.classList.add('notranslate');
          this.toggleTranslateStatus('#toolbar-administration', false);
          this.toggleTranslateStatus('body', true);
          this.toggleTranslateStatus('html', true);

          if (drupalSettings.proTranslationAvailable) {
            
            if(jQuery('.main-image .breadcrumbs').length>=1) {
                            
              this.toggleTranslateStatus('.main-image .content-top', false);
                            
              jQuery('.field-name--field-sections>div').not(":first-child").addClass('notranslate');
            }
            else {
              this.toggleTranslateStatus('.region--content', false);
            }
            
          }

          try {
            if (drupalSettings.selectedLangCode == 'zh-hans') {
              doGTranslate(drupalSettings.defaultLangCode + '|zh-CN');
            } else {
              doGTranslate(drupalSettings.defaultLangCode + '|' + drupalSettings.selectedLangCode);
            }
          } catch (e) {
            console.log('Translation Error.', e);
          }
        }
      } else {
        // This helps prevents other languages from getting stuck
        setTimeout(function () {
          doGTranslate('en|en');
        }, 5000);
      }
    }
  }]);

  return Translations;
}();

/* harmony default export */ __webpack_exports__["default"] = (Translations);

/***/ }),

/***/ "./src/js/index.js":
/*!*************************!*\
  !*** ./src/js/index.js ***!
  \*************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _classes_Map__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./classes/Map */ "./src/js/classes/Map.js");
/* harmony import */ var _classes_Translations__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./classes/Translations */ "./src/js/classes/Translations.js");
/* harmony import */ var _classes_FixedHeader__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./classes/FixedHeader */ "./src/js/classes/FixedHeader.js");
/* harmony import */ var _scss_main_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../scss/main.scss */ "./src/scss/main.scss");
/* harmony import */ var _scss_main_scss__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_scss_main_scss__WEBPACK_IMPORTED_MODULE_3__);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



 //import HeaderSearch from './classes/HeaderSearch';
// Without this import, 'dist/main.css' will not be generated on build.



var CivicThemeIndex = /*#__PURE__*/function () {
  function CivicThemeIndex() {
    _classCallCheck(this, CivicThemeIndex);
  }

  _createClass(CivicThemeIndex, [{
    key: "initOnDocumentLoad",

    /**
     * Functions here will be called when
     * the document is  ready.
     */
    value: function initOnDocumentLoad() {
      this.instantiateClasses(_classes_FixedHeader__WEBPACK_IMPORTED_MODULE_2__["default"], 'header.header-fixed'); //this.instatiateClasses(HeaderSearch, '#header-search-container');
    }
    /**
     * Functions here will be called when the
     * window is fully loaded. This is the final
     * step in the render process and ensures that
     * all elements are now in the DOM.
     */

  }, {
    key: "initOnWindowLoad",
    value: function initOnWindowLoad() {
      this.instantiateClasses(_classes_Map__WEBPACK_IMPORTED_MODULE_0__["default"], '#map');
      this.instantiateClasses(_classes_Translations__WEBPACK_IMPORTED_MODULE_1__["default"], '#block-languageswitcher');
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

  }, {
    key: "instantiateClasses",
    value: function instantiateClasses(ClassName, selector) {
      if (!selector) {
        new ClassName();
      } else {
        var elements = document.querySelectorAll(selector);
        elements.forEach(function (element, index) {
          var classInstance = new ClassName(element, index);

          if (typeof classInstance.init === 'function') {
            classInstance.init();
          }
        });
      }
    }
  }]);

  return CivicThemeIndex;
}(); // Polyfill for forEach method


(function () {
  if (typeof NodeList.prototype.forEach === "function") return false;
  NodeList.prototype.forEach = Array.prototype.forEach;
})();
/**
 * Instatiate the CivicThemeIndex Class
 *
 * This is the recommended format to add JavaScript in Drupal.
 * https://www.drupal.org/docs/8/api/javascript-api/javascript-api-overview
 */


(function ($, Drupal) {
  $(document).ready(function () {
         setTimeout(function() {
           var base = $('.owl-carousel').data('owlCarousel');
        if(base) {
            //owl.calculateWidth();
            //owl.appendItemsSizes();
            base.updateItems();
            base.calculateAll();
            //base.buildControls();
            base.updateControls();
            base.response();
            //base.moveEvents();
            //base.stopOnHover();
            //base.owlStatus();
            }
           
         }, 300);
    
  });
  
  //$('img.align-left').removeClass('align-left').addClass('align-left-ex').wrap('<div class="align-left clearfix">');
  function screen_resize_events() {
    
    
    
      if ($(window).width() <= 1000) {
      
         var total_width = 0;
        var item_width = $('.paragraph--type--embedded-blocks-section .news .news-items .views-row').outerWidth(true);
        
        
        var items_count = 0;
        $('.paragraph--type--embedded-blocks-section .news .news-items .views-row').each(function () {
          total_width+=$(this).outerWidth(true);
        });
        $('.paragraph--type--embedded-blocks-section .news .news-items').width(total_width);
      }
      else {
        $('.paragraph--type--embedded-blocks-section  .news .news-items').css('width', 'auto');
      }
  }
  function news_filters_process() {
      $('.view-id-news .views-exposed-form .form-actions').hide();
     // $('.view-id-news .views-exposed-form [data-drupal-selector="edit-created"]').attr('type', 'date');
      $('.view-id-news .views-exposed-form [data-drupal-selector="edit-created"]').not('.form-date').addClass('form-date').datepicker({
          dateFormat: 'mm/dd/yy',
        });
      $('.view-id-news .views-exposed-form').not('.autosubmit-processed').addClass('autosubmit-processed').find('input[type="text"], input[type="date"]').on('change', function() {
        $(this).closest('form').find('input[type="submit"]').trigger('click');
           
      });
  }
  
  $(window).on('resize', function() {
      
    screen_resize_events();
  });
  
  
  var index = null;
  Drupal.behaviors.civicTheme = {
    attach: function attach(context) {
      // console.log('[Context]', context);
      // $('.layout-container select').select2({
      //   dropdownParent: $('.layout-container'),
      // });
      news_filters_process();
      if (context === document) {
        $('#block-views-block-news-block-1 header').parent().addClass('news-items');
        $("#block-views-block-news-block-1 .views-row, #block-views-block-news-news-block-6items .views-row , #block-views-block-featured-content-block-1 .views-row").wrapAll("<div class='news'><div class='news-items'></div></div>");
        $("#header-search-button").click(function () {
          $("#block-civic-search").slideToggle("slow");
        });
        $("#block-topmenu-3 li span").click(function () {
          $("#block-topmenu-3 li ul").slideToggle("slow");
        });

        if ($('.content-top .elements-wrapper .field-name--field-icon-with-link').length) // use this if you are using id to check
          {
            $('.paragraph--type--image-with-icon-and-link').removeClass('no-links');
          } else {
          $('.paragraph--type--image-with-icon-and-link').addClass('no-links');
        }

        $('.js-form-wrapper .paragraphs-badge').hide();
        $('#dynamic_select').on('change', function () {
          var url = $(this).val(); // get selected value

          if (url) {
            // require a URL
            window.location = url; // redirect
          }
        }); //$('.paragraph--type--bg-image-section').addClass('shadow-text');

        index = new CivicThemeIndex();
        index.initOnDocumentLoad();
        $(window).on('load', function () {
          index.initOnWindowLoad();
        });
      }
      
      
     screen_resize_events();
      
    }
  };
})(jQuery, Drupal);

/***/ }),

/***/ "./src/scss/main.scss":
/*!****************************!*\
  !*** ./src/scss/main.scss ***!
  \****************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ })

/******/ });
//# sourceMappingURL=bundle.js.map