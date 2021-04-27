// (function(Drupal, $) {
//   /**
//    * @constructor
//    * @param {Object} wrapper - The wrapping DOM element
//    */
//   function CivicMegaMenu(wrapper) {
//     this.wrapper = wrapper;
//     this.iScroll = new IScroll('#civic-mega-menu-mobile');
//
//     /**
//      * Initalize function called once the constructor is instatiated.
//      */
//     this.init = function() {
//       $('.civic-mega-menu a, .civic-mega-menu button, .civic-mega-menu span[tabindex]').on('focus', this.onItemFocus.bind(this));
//       $('.civic-mega-menu a, .civic-mega-menu button, .civic-mega-menu span[tabindex]').on('keydown', this.onItemKeyDown.bind(this));
//       $('.civic-mega-menu.layout-3 .cmm-li--level-2').on('mouseenter', this.sizeLevel3Menu.bind(this));
//       $('.civic-mega-menu.layout-3 .menu-item--level-2').on('focus', this.sizeLevel3Menu.bind(this));
//       $('#mobile-menu-open').on('click', this.openMobileMenu.bind(this));
//       $('#mobile-menu-close').on('click', this.closesMobileMenu.bind(this));
//       $('button.toggle-submenu').on('click', this.toggleMobileMenuSubmenu.bind(this));
//
//       if (drupalSettings.cmm.expandOnClick) {
//         $('.cmm-li--level-1').on('mousedown', this.liMouseDownHandler.bind(this));
//         $(this.wrapper).on('click', this.wrapperClickHandler.bind(this));
//       } else {
//         $(window).on('mousemove', this.handleMouseMove.bind(this));
//       }
//     }
//
//     /**
//      * The menu is keyboard accessible,
//      * Though once the mouse starts moving, the active classes are removed
//      * This prevents the menu from getting stuck in its open state.
//      */
//     this.handleMouseMove = function() {
//       $activeLi = $('.cmm-li--level-1.active');
//
//       if ($activeLi.length) {
//         $activeLi.removeClass('active');
//       }
//     }
//
//     /**
//      * Handles opening the mobile menu
//      *
//      * The scroll is removed from the page html and body
//      * Scrolling for the menu is then controlled by iScroll
//      */
//     this.openMobileMenu = function() {
//       var that = this;
//       $('#mobile-menu-mask').addClass('active');
//
//       setTimeout(function() {
//         $('.civic-mega-menu-mobile').addClass('open-mobile-menu');
//         $('html').css('overflow', 'hidden');
//         $('body').css('overflow', 'hidden');
//         that.iScroll.refresh();
//       }, 200);
//     }
//
//     /**
//      * Handles closing the mobile menu
//      */
//     this.closesMobileMenu = function() {
//       $('html').css('overflow', '');
//       $('body').css('overflow', '');
//       $('.civic-mega-menu-mobile').addClass('close-mobile-menu');
//       $('.civic-mega-menu-mobile').removeClass('open-mobile-menu');
//
//       setTimeout(function() {
//         $('.civic-mega-menu-mobile').removeClass('close-mobile-menu');
//         $('#mobile-menu-mask').removeClass('active');
//       }, 350);
//     }
//
//     /**
//      * @param {Object} event - DOM Event
//      *
//      * Toggles the 'active' class on menu items
//      * which the CSS uses to show and hide them.
//      * iScroll is then refreshed to account for the new menu height.
//      */
//     this.toggleMobileMenuSubmenu = function(event) {
//       var that = this;
//
//       $target = $(event.target);
//       $li = $target.parent().parent();
//       $target.toggleClass('active');
//       $li.toggleClass('active');
//
//       setTimeout(function() {
//         that.iScroll.refresh();
//       }, 10);
//     }
//
//     /**
//      * @param {Object} event - DOM Event
//      */
//     this.sizeLevel3Menu = function(event) {
//       $target = $(event.target);
//
//       if ($target.hasClass('menu-item--level-2')) {
//         $target = $target.parent();
//       }
//
//       if ($target.hasClass('menu-item-value--level-2')) {
//         $target = $target.parent().parent();
//       }
//
//       if ($target.hasClass('cmm-li--level-2')) {
//         $colLevel2 = $target.parent();
//         $ulLevel3 = $target.find('.cmm-ul--level-3');
//
//         if ($ulLevel3.length > 0) {
//           $colLevel2.css('height', $ulLevel3.height() + 'px');
//         } else {
//           $colLevel2.css('height', '');
//         }
//       }
//     }
//
//     /**
//      * @param {Object} event - DOM Event
//      *
//      * Adds an "active" class to focused menu items
//      * to then be targeted in CSS
//      */
//     this.onItemFocus = function(event) {
//       const $target = $(event.target);
//
//       this.setActiveOnFocused($target, 1);
//       this.setActiveOnFocused($target, 2);
//     }
//
//     /**
//      * @param {Object} $target - The jQuery target
//      * @param {number} level - The menu level to check and set
//      */
//     this.setActiveOnFocused = function($target, level) {
//       if ($target.hasClass('menu-item--level-' + level) && !$target.parent().hasClass('active')) {
//         var activeLi = this.wrapper.querySelector('.cmm-li--level-' + level + '.active');
//
//         if (activeLi) {
//           activeLi.classList.remove('active');
//         }
//
//         $target.parent().addClass('active');
//       }
//     }
//
//     this.onItemKeyDown = function() {
//       var that = this;
//
//       setTimeout(function() {
//         if (!that.wrapper.contains(document.activeElement)) {
//           $('.cmm-li').removeClass('active');
//         }
//       }, 50);
//     }
//
//     /**
//      * @param {Object} downEvent - DOM mousedown event
//      */
//     this.liMouseDownHandler = function(downEvent) {
//       $li = $(downEvent.currentTarget);
//
//       if ($li.hasClass('active')) {
//         $('body').one('mouseup', function(upEvent) {
//           const $upTarget = $(upEvent.target);
//
//           let $upAnchor;
//           if ($upTarget.hasClass('menu-item')) {
//             $upAnchor = $upTarget;
//           } else if ($upTarget.hasClass('menu-item-value')) {
//             $upAnchor = $upTarget.parent();
//           }
//
//           if ($upAnchor && upEvent.target == downEvent.target) {
//             $upAnchor.blur();
//             $upAnchor.parent().removeClass('active');
//           }
//         });
//       }
//     }
//
//     /**
//      * When the menu is in open "On Click" mode,
//      * this function closes the menu whenever a click
//      * is registered outside of the menu.
//      */
//     this.wrapperClickHandler = function() {
//       var that = this;
//
//       setTimeout(function() {
//         document.querySelector('body').addEventListener('click', function(e) {
//           if (!that.wrapper.contains(e.target)) {
//             $('.cmm-li').removeClass('active');
//           }
//         }, { once: true });
//
//         that.downEvent = null;
//       }, 1);
//     }
//   }
//
//   Drupal.behaviors.civicMegaMenu = {
//     attach: function(context) {
//       if (context.id === 'block-civic-main-menu') {
//         var megamenuEl = document.getElementById('civic-mega-menu');
//
//         if (megamenuEl) {
//           var megamenu = new CivicMegaMenu(megamenuEl);
//           megamenu.init();
//         }
//       }
//     }
//   }
// })(Drupal, jQuery);



(function(Drupal, $) {
  function CivicMegaMenu(wrapper) {
    this.wrapper = wrapper;
    this.mouseDownElement = null;
    this.myScroll = new IScroll('#civic-mega-menu-mobile');

    this.init = function() {
      $('a, button, span[tabindex]').on('focus', this.onItemFocus.bind(this));
      $('.civic-mega-menu a, .civic-mega-menu button, .civic-mega-menu span[tabindex]').on('keydown', this.onItemKeyDown.bind(this));
      $('.civic-mega-menu .cmm-li--level-1').on('mouseenter', this.positionMenuOnOpen.bind(this));
      $('.civic-mega-menu .cmm-li--level-3').on('mouseenter', this.sizeLevel4Menu.bind(this));
      $('.mobile-menu-open').on('click', this.openMobileMenu.bind(this));
      $('#mobile-menu-close').on('click', this.closesMobileMenu.bind(this));
      $('button.toggle-submenu').on('click', this.toggleMobileMenuSubmenu.bind(this));

      if (drupalSettings.cmm.expandOnClick) {
        $('.cmm-li--level-1').on('mousedown', this.liMouseDownHandler.bind(this));
        $(this.wrapper).on('click', this.wrapperClickHandler.bind(this));
      } else {
        $(window).on('mousemove', this.handleMouseMove.bind(this));
      }
    }

    // The menu is keyboard accessible,
    // Though once the mouse starts moving, the active classes are removed
    // This prevents the menu from getting stuck in its open state.
    this.handleMouseMove = function() {
      $activeLi = $('.cmm-li--level-1.active');

      if ($activeLi.length) {
        $activeLi.removeClass('active');
      }
    }

    this.positionMenuOnOpen = function() {
      $('#block-logo').addClass('mega-menu-open');

      if (window.innerWidth < 992) {
        const left = $(wrapper).offset().left;
        $('.cmm-ul--level-2').css('left', left * -1);
      }
    }

    this.openMobileMenu = function(e) {
      var that = this;
      $('#mobile-menu-mask').addClass('active');
      $(".civic-mega-menu-mobile").css({ 'display' : 'flex' });
      setTimeout(function() {
        $('.cmm-mobile-menu-wrapper').addClass('open-mobile-menu');
        $('html').css('overflow', 'hidden');
        $('body').css('overflow', 'hidden');
        that.myScroll.refresh();
      }, 200);
    }

    this.closesMobileMenu = function(e) {

      $('html').css('overflow', '');
      $('body').css('overflow', '');
      $('.cmm-mobile-menu-wrapper').addClass('close-mobile-menu');
      $('.cmm-mobile-menu-wrapper').removeClass('open-mobile-menu');
      $(".civic-mega-menu-mobile").css({ 'display' : 'none' });
      setTimeout(function() {
        $('.cmm-mobile-menu-wrapper').removeClass('close-mobile-menu');
        $('#mobile-menu-mask').removeClass('active');
      }, 350);
    }

    this.toggleMobileMenuSubmenu = function(e) {
      var that = this;

      $target = $(e.target);
      $li = $target.parent().parent();
      $target.toggleClass('active');
      $li.toggleClass('active');

      setTimeout(function() {
        that.myScroll.refresh();
      }, 10);
    }

    this.sizeLevel4Menu = function(e) {
      $target = $(e.target);

      if ($target.hasClass('menu-item--level-3')) {
        $target = $target.parent();
      }

      if ($target.hasClass('menu-item-value--level-3')) {
        $target = $target.parent().parent();
      }

      if ($target.hasClass('cmm-li--level-3')) {
        $ulLevel3 = $target.parent();
        $ulLevel2 = $ulLevel3.parent().parent().parent().parent();

        const widthOfFirst3Levels =  $ulLevel3.offset().left + $ulLevel3.width() - $ulLevel2.offset().left;
        const remainingWidth = $ulLevel2.width() - widthOfFirst3Levels - 40;

        $('.cmm-ul--level-4').css('width', remainingWidth + 'px');
      }

    }

    this.onItemFocus = function(e) {
      $target = $(e.target);

      var activeLi1 = this.wrapper.querySelector('.cmm-li--level-1.active');

      if ($target.hasClass('menu-item--level-1') && !$target.parent().hasClass('active')) {
        if (activeLi1) {
          activeLi1.classList.remove('active');
        }

        $target.parent().addClass('active');
      }
    }

    this.onItemKeyDown = function() {
      var that = this;

      setTimeout(function() {
        if (!that.wrapper.contains(document.activeElement)) {
          $('.cmm-li').removeClass('active');
        }
      }, 50);
    }

    // This function is currently not being used.
    this.onItemBlur = function() {
      var that = this;

      setTimeout(function() {

        if (!that.wrapper.contains(document.activeElement)) {
          $('.cmm-li').removeClass('active');
        }
      }, 250);
    }

    this.liMouseDownHandler = function(downEvent) {
      this.mouseDownElement = downEvent.target;
      $target = $(downEvent.target);

      if ($target.parent().hasClass('active')) {
        $('body').one('mouseup', function(upEvent) {
          if (upEvent.target === downEvent.target) {
            $target.blur();
            $target.parent().removeClass('active');
          }
        });
      }
    }

    this.wrapperClickHandler = function() {
      var that = this;

      setTimeout(function() {
        document.querySelector('body').addEventListener('click', function(e) {
          if (!that.wrapper.contains(e.target)) {
            $('.cmm-li').removeClass('active');
          }
        }, { once: true });

        that.downEvent = null;
      }, 1);
    }
  }

  Drupal.behaviors.civicMegaMenu = {
    attach: function(context) {
      if (context === document) {
        var megamenuEl = document.getElementById('civic-mega-menu');

        if (megamenuEl) {
          var megamenu = new CivicMegaMenu(megamenuEl);
          megamenu.init();
        }
      }
    }
  }
})(Drupal, jQuery);
