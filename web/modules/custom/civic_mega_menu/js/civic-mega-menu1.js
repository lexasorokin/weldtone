(function(Drupal, $) {
  /**
   * @constructor
   * @param {Object} wrapper - The wrapping DOM element
   */
  function CivicMegaMenu(wrapper) {
    this.wrapper = wrapper;
    this.iScroll = new IScroll('#civic-mega-menu-mobile');
    
    /**
     * Initalize function called once the constructor is instatiated.
     */
    this.init = function() {
      $('.civic-mega-menu a, .civic-mega-menu button, .civic-mega-menu span[tabindex]').on('focus', this.onItemFocus.bind(this));
      $('.civic-mega-menu a, .civic-mega-menu button, .civic-mega-menu span[tabindex]').on('keydown', this.onItemKeyDown.bind(this));
      $('.civic-mega-menu.layout-3 .cmm-li--level-2').on('mouseenter', this.sizeLevel3Menu.bind(this));
      $('.civic-mega-menu.layout-3 .menu-item--level-2').on('focus', this.sizeLevel3Menu.bind(this));
      $('#mobile-menu-open').on('click', this.openMobileMenu.bind(this));
      $('#mobile-menu-close').on('click', this.closesMobileMenu.bind(this));
      $('button.toggle-submenu').on('click', this.toggleMobileMenuSubmenu.bind(this));

      if (drupalSettings.cmm.expandOnClick) {
        $('.cmm-li--level-1').on('mousedown', this.liMouseDownHandler.bind(this));
        $(this.wrapper).on('click', this.wrapperClickHandler.bind(this));
      } else {
        $(window).on('mousemove', this.handleMouseMove.bind(this));
      }
    }

    /**
     * The menu is keyboard accessible,
     * Though once the mouse starts moving, the active classes are removed
     * This prevents the menu from getting stuck in its open state.
     */
    this.handleMouseMove = function() {
      $activeLi = $('.cmm-li--level-1.active');

      if ($activeLi.length) {
        $activeLi.removeClass('active');
      }
    }

    /**
     * Handles opening the mobile menu
     * 
     * The scroll is removed from the page html and body
     * Scrolling for the menu is then controlled by iScroll
     */
    this.openMobileMenu = function() {
      var that = this;
      $('#mobile-menu-mask').addClass('active');
      
      setTimeout(function() {
        $('.civic-mega-menu-mobile').addClass('open-mobile-menu');
        $('html').css('overflow', 'hidden');
        $('body').css('overflow', 'hidden');
        that.iScroll.refresh();
      }, 200);
    }
    
    /**
     * Handles closing the mobile menu
     */
    this.closesMobileMenu = function() {
      $('html').css('overflow', '');
      $('body').css('overflow', '');
      $('.civic-mega-menu-mobile').addClass('close-mobile-menu');
      $('.civic-mega-menu-mobile').removeClass('open-mobile-menu');
      
      setTimeout(function() {
        $('.civic-mega-menu-mobile').removeClass('close-mobile-menu');
        $('#mobile-menu-mask').removeClass('active');
      }, 350);
    }

    /**
     * @param {Object} event - DOM Event
     * 
     * Toggles the 'active' class on menu items
     * which the CSS uses to show and hide them.
     * iScroll is then refreshed to account for the new menu height.
     */
    this.toggleMobileMenuSubmenu = function(event) {
      var that = this;
      
      $target = $(event.target);
      $li = $target.parent().parent();
      $target.toggleClass('active');
      $li.toggleClass('active');

      setTimeout(function() {
        that.iScroll.refresh();
      }, 10);
    }

    /**
     * @param {Object} event - DOM Event
     */
    this.sizeLevel3Menu = function(event) {
      $target = $(event.target);

      if ($target.hasClass('menu-item--level-2')) {
        $target = $target.parent();
      }

      if ($target.hasClass('menu-item-value--level-2')) {
        $target = $target.parent().parent();
      }

      if ($target.hasClass('cmm-li--level-2')) {
        $colLevel2 = $target.parent();
        $ulLevel3 = $target.find('.cmm-ul--level-3');
        
        if ($ulLevel3.length > 0) {
          $colLevel2.css('height', $ulLevel3.height() + 'px');
        } else {
          $colLevel2.css('height', '');
        }
      }
    }

    /**
     * @param {Object} event - DOM Event
     * 
     * Adds an "active" class to focused menu items
     * to then be targeted in CSS
     */
    this.onItemFocus = function(event) {
      const $target = $(event.target);

      this.setActiveOnFocused($target, 1);
      this.setActiveOnFocused($target, 2);
    }

    /**
     * @param {Object} $target - The jQuery target
     * @param {number} level - The menu level to check and set 
     */
    this.setActiveOnFocused = function($target, level) {
      if ($target.hasClass('menu-item--level-' + level) && !$target.parent().hasClass('active')) {
        var activeLi = this.wrapper.querySelector('.cmm-li--level-' + level + '.active');

        if (activeLi) {
          activeLi.classList.remove('active');
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

    /**
     * @param {Object} downEvent - DOM mousedown event 
     */
    this.liMouseDownHandler = function(downEvent) {
      $li = $(downEvent.currentTarget);

      if ($li.hasClass('active')) {
        $('body').one('mouseup', function(upEvent) {
          const $upTarget = $(upEvent.target);
          
          let $upAnchor;
          if ($upTarget.hasClass('menu-item')) {
            $upAnchor = $upTarget;
          } else if ($upTarget.hasClass('menu-item-value')) {
            $upAnchor = $upTarget.parent();
          }

          if ($upAnchor && upEvent.target == downEvent.target) {
            $upAnchor.blur();
            $upAnchor.parent().removeClass('active');
          }
        });
      }
    }

    /**
     * When the menu is in open "On Click" mode,
     * this function closes the menu whenever a click
     * is registered outside of the menu.
     */
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
      if (context.id === 'block-civic-main-menu') {
        var megamenuEl = document.getElementById('civic-mega-menu');

        if (megamenuEl) {
          var megamenu = new CivicMegaMenu(megamenuEl);
          megamenu.init();
        }
      }
    }   
  }
})(Drupal, jQuery);