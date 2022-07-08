(function (Drupal, $) {
  const setVar = (ul, vari, value) => {
    $(ul).length && $(ul).get(0).style.setProperty(`--${vari}`, `${value}`);
  }

  // const refreshWidth = ($li) => {
  //   // margin left ul = right-li - right child ul
  //   const $ul = $li.children('ul');
  //   if (!$ul.length) {
  //     return ;
  //   }
  //   setTimeout(() => {
  //     if ($ul.offset() && ($ul.offset().left >= 0)) {
  //       const marginleft = $li.offset().left + $li.width() - ($ul.offset().left + $ul.width());
  //       if (marginleft >= 1) {
  //         setVar($ul, 'margin-left', marginleft + 'px');
  //       }
  //     }      
  //   }, 300);
  // }

  const propSize = (obj, prop) => {
    const pr = obj.css(prop) && ~obj.css(prop).indexOf('px') ? parseInt(obj.css(prop), 10) : 0;
    return pr;
  }

  const setLi = (parentli) => {
    const $lis = $(parentli).children('ul').find('.cmm-li--level-2');
    for (const elm of $lis.toArray()) {
      const $ul = $(elm).children('ul');
      // Set variables for child ul
      if (!$ul.attr('dt-li-top')) {
        const litop = $(elm).offset().top - $(elm).parent().offset().top;
        if (litop > 0) {
          $ul.attr('dt-li-top', litop);
          $ul.length && $ul.get(0).style.setProperty("--li-top", `${litop}px`);
        }
      }
    }
  }

  const setHeight = (obj, mh) => {
          let pr = obj.css('min-height') && ~obj.css('min-height').indexOf('px') ? parseInt(obj.css('min-height'), 10) : 0;

          pr = pr <= mh ? mh : pr;
          if (pr >= 0) {
            obj.css('min-height', `${pr}px`);
          }
  }

  const refreshHeight = (elm) => {
    // In this element, we check the child ul's height and make sure parent div has the max height
    const elements = jQuery(elm).toArray();
    // Store this original height of elm
    if (!$(elm).parent().attr('dt-height')) {
      $(elm).parent().attr('dt-height', $(elm).parent().outerHeight());
    }

    for (const elm of elements) {
      const $ul = $(elm).children('ul');
      const uls = $ul.toArray();

      const tofind = uls.map((ul) => {
        if ($(ul).attr('dt-mheight')) {
          return $(ul).attr('dt-mheight');
        }
        // Store ul height also
        jQuery(ul).css('height', 'auto');
        const mheight = $(ul).outerHeight();
        $(ul).css('height', '');
        if (mheight > 0) {
          $(ul).attr('dt-mheight', mheight);
          if (!$(ul).attr('dt-height')) {
            const {max,min} = $(ul).children().filter(':visible').toArray().reduce((root, obj) => {
  const min = root.min === -1 ? jQuery(obj).offset().top : (jQuery(obj).offset().top < root.min ? jQuery(obj).offset().top : root.min);
  const max = root.max === -1 ? (jQuery(obj).outerHeight() + jQuery(obj).offset().top) : ((jQuery(obj).outerHeight() + jQuery(obj).offset().top) > root.max ? (jQuery(obj).outerHeight() + jQuery(obj).offset().top) : root.max);  
  return {min, max};
}, {min: -1, max: -1});
            const padding = propSize($(ul), 'padding-top') + propSize($(ul), 'padding-bottom');
            const height = padding + max - min;
            $(ul).attr('dt-height', height);
            // Now set --my-top

            const pheight = $(ul).parent().parent().attr('dt-height') || 0;
            const mytop = (pheight - height)/2;
            if (mytop >= 0) {
              $(ul).length && $(ul).get(0).style.setProperty("--my-top", `${mytop}px`);
            }
          }
        }
        return mheight;
      });
      const mh = Math.max(...[...tofind, jQuery(elm).parent().outerHeight()]);
      if (mh > 10) {
        setHeight($(elm).parent(), mh);
        // if($(elm).data('items') > 0){
          // const obj2 =$(elm).closest('.cmm-li.cmm-li--level-1');
          // setHeight(obj2, mh+25);
        // }
        // Now I'm affecting my parent also
        const obj = $(elm).parent().closest('li').parent();
        if (obj.hasClass("cmm-column--level-2")) {
          setHeight(obj, mh);
        }
      }
    }
  };

  const refreshAll = () => {
      const $myul = $('.cmm-ul.cmm-ul--level-1');
      const lioffset = $myul.offset().top + $myul.outerHeight() - $(document).scrollTop() - propSize($('header#header.header'), 'top');// - propSize($('body'), 'padding-top');
      document.documentElement.style.setProperty('--li-offset', `${lioffset}px`);
  }

  function CivicMegaMenu(wrapper) {
    this.wrapper = wrapper;
    this.mouseDownElement = null;
    this.myScroll = new IScroll('#civic-mega-menu-mobile');

    this.init = function () {
      $(document).on('drupalViewportOffsetChange', refreshAll);
      $( window ).resize(refreshAll).trigger('resize');
      setTimeout(refreshAll, 1000);
      setTimeout(refreshAll, 2000);
      setTimeout(refreshAll, 3000);
      // video loading dirty fix
      setTimeout(refreshAll, 10000);
      $('a, button, span[tabindex]').on('focus', this.onItemFocus.bind(this));
      $(
        '.civic-mega-menu a, .civic-mega-menu button, .civic-mega-menu span[tabindex]'
      ).on('keydown', this.onItemKeyDown.bind(this));
      $('.civic-mega-menu .cmm-li--level-1').on(
        'mouseenter',
        this.positionMenuOnOpen.bind(this)
      );

        $('.civic-mega-menu .cmm-li--level-1').hover(function() {
          let wrap =$(this);
          let back_wrap =$(this).find('ul.cmm-ul.menu.cmm-ul--level-2').height();
        //   let headerWdith = $('#header').width();
        //   if(headerWdith){
        //     wrap.css('width', headerWdith);
        //   }
        //   wrap.css('display', 'flex');
        // wrap.css('height', back_wrap);
        //   wrap.css('box-shadow', '0px 0px 3px 2px #aaa');
        // }, function() {
        //   let wrap =$(this).find('.menu_wrap');
        //   wrap.css('display', 'none');
        //   wrap.css('box-shadow', '0');

        });

      $('.civic-mega-menu .cmm-li--level-1').on('mouseenter', function () {
        setTimeout(setLi.bind({}, $(this)), 300);
        
      });

      $('.civic-mega-menu .cmm-li--level-2').on('mouseenter', function () {
        setTimeout(refreshHeight.bind({}, $(this)), 300);
      });

      $('.civic-mega-menu .cmm-li--level-3').on('mouseenter', function () {
        setTimeout(refreshHeight.bind({}, $(this)), 300);
      });

      // $('.civic-mega-menu .cmm-li--level-1').on('mouseenter', function() {
      //   refreshWidth($(this));
      // });
      $('.civic-mega-menu .cmm-li--level-3').on(
        'mouseenter',
        this.sizeLevel4Menu.bind(this)
      );
      $('.mobile-menu-open').on('click', this.openMobileMenu.bind(this));
      $('#mobile-menu-close').on('click', this.closesMobileMenu.bind(this));
      $('#mobile-menu-mask').on('click', this.closesMobileMenu.bind(this));

      $('button.toggle-submenu').on(
        'click',
        this.toggleMobileMenuSubmenu.bind(this)
      );

 
    };

    // The menu is keyboard accessible,
    // Though once the mouse starts moving, the active classes are removed
    // This prevents the menu from getting stuck in its open state.
    this.handleMouseMove = function () {
      $activeLi = $('.cmm-li--level-1.active');

      if ($activeLi.length) {
        $activeLi.removeClass('active');
      }
    };

    this.positionMenuOnOpen = function () {
      $('#block-logo').addClass('mega-menu-open');

      if (window.innerWidth < 992) {
        const left = $(wrapper).offset().left;
        $('.cmm-ul--level-2').css('left', left * -1);
      }
    };

    this.openMobileMenu = function (e) {
      var that = this;
      $('#mobile-menu-mask').addClass('active');
      $('#civic-mega-menu-mobile').css('display','block');
      $('#dynamic_select').css('margin-top','20px');

      setTimeout(function () {
        $('.cmm-mobile-menu-wrapper').addClass('open-mobile-menu');
        $('html').css('overflow', 'hidden');
        $('body').css('overflow', 'hidden');
        that.myScroll.refresh();
      }, 200);
    };

    this.closesMobileMenu = function (e) {
      $('html').css('overflow', '');
      $('body').css('overflow', '');
      $('.cmm-mobile-menu-wrapper').addClass('close-mobile-menu');
      $('.cmm-mobile-menu-wrapper').removeClass('open-mobile-menu');
  

      setTimeout(function () {
        $('.cmm-mobile-menu-wrapper').removeClass('close-mobile-menu');
        $('#mobile-menu-mask').removeClass('active');
        $('#civic-mega-menu-mobile').css('display','none');
      }, 350);
    };

    this.toggleMobileMenuSubmenu = function (e) {
      var that = this;

      $target = $(e.target);
      $li = $target.parent().parent();
      $target.toggleClass('active');
      $li.toggleClass('active');

      // .with-active-child
      $li.parent().toggleClass('with-active-child');
      // .latest-active
      $('.cmm-mobile-menu-wrapper .last-active').removeClass('last-active');
      let $last = $li;
      if (!$li.hasClass('active')) {
        $last = $li.closest('li.active');
      }
      $last.addClass('last-active');

      setTimeout(function () {
        that.myScroll.refresh();
      }, 10);
    };

    this.sizeLevel4Menu = function (e) {
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

        const widthOfFirst3Levels =
          $ulLevel3.offset().left + $ulLevel3.width() - $ulLevel2.offset().left;
        const remainingWidth = $ulLevel2.width() - widthOfFirst3Levels - 40;

        $('.cmm-ul--level-4').css('width', remainingWidth + 'px');
      }
    };

    this.onItemFocus = function (e) {
      $target = $(e.target);

      var activeLi1 = this.wrapper.querySelector('.cmm-li--level-1.active');

      if (
        $target.hasClass('menu-item--level-1') &&
        !$target.parent().hasClass('active')
      ) {
        if (activeLi1) {
          activeLi1.classList.remove('active');
        }

        $target.parent().addClass('active');
      }
    };

    this.onItemKeyDown = function () {
      var that = this;

      setTimeout(function () {
        if (!that.wrapper.contains(document.activeElement)) {
          $('.cmm-li').removeClass('active');
        }
      }, 50);
    };

    // This function is currently not being used.
    this.onItemBlur = function () {
      var that = this;

      setTimeout(function () {
        if (!that.wrapper.contains(document.activeElement)) {
          $('.cmm-li').removeClass('active');
        }
      }, 250);
    };

    this.liMouseDownHandler = function (downEvent) {
      this.mouseDownElement = downEvent.target;
      $target = $(downEvent.target);

      if ($target.parent().hasClass('active')) {
        $('body').one('mouseup', function (upEvent) {
          if (upEvent.target === downEvent.target) {
            $target.blur();
            $target.parent().removeClass('active');
          }
        });
      }
    };

    this.wrapperClickHandler = function () {
      var that = this;

      setTimeout(function () {
        document.querySelector('body').addEventListener(
          'click',
          function (e) {
            if (!that.wrapper.contains(e.target)) {
              $('.cmm-li').removeClass('active');
            }
          },
          { once: true }
        );

        that.downEvent = null;
      }, 1);
    };
  }

  Drupal.behaviors.civicMegaMenu = {
    attach: function (context) {
      if (context === document) {
        var megamenuEl = document.getElementById('civic-mega-menu');

        if (megamenuEl) {
          var megamenu = new CivicMegaMenu(megamenuEl);
          megamenu.init();
        }
      }
    }
  };
})(Drupal, jQuery);
