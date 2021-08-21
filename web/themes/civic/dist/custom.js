(function ($, Drupal) {
  // Use strict mode to reduce development errors.
  // @link http://www.nczonline.net/blog/2012/03/13/its-time-to-start-using-javascript-strict-mode/
  "use strict";

  Drupal.behaviors.eventsequal = {
    attach: function (context, settings) {
      setTimeout(function () {
        var row_num = 0;

        var prevtop = 0;

        var row_max_title = 0;
        var row_max_bottom = 0;

        $('.view-id-events .card').each(function () {

          var thistop = $(this).position().top;

          if (thistop > prevtop) {
            row_num++;
            row_max_title = 0;
            row_max_bottom = 0;
          }

          prevtop = thistop;


          $(this).attr('data-rownum', row_num);

          var title_height = $(this).find('.card-title').height();
          var bottom_height = $(this).find('.card-bottom').height();

          row_max_title = Math.max(title_height, row_max_title);
          row_max_bottom = Math.max(bottom_height, row_max_bottom);

          $('[data-rownum="' + row_num + '"] .card-title').height(row_max_title);
          $('[data-rownum="' + row_num + '"] .card-bottom').height(row_max_bottom);

        });
      }, 300);

    }
  };


  $(document).ready(function () {
    $('.paragraph--type--image-with-icon-and-link div .elements-wrapper .link').on('click', function () {
      window.location.href = $(this).find('a').attr('href');
    });


    $('#civic-mega-menu .cmm-li--level-2').on('mouseover', function () {
      var thisul = $(this).find('.cmm-ul--level-3');

      var l2ul = $(this).closest('.cmm-ul--level-2');
      var base_padding = 25;

      if (thisul && thisul.position()) {
        var thisbottom = l2ul.offset().top + l2ul.outerHeight(true);
        var bottom = thisul.offset().top + thisul.outerHeight(true);
        //console.log(thisul.offset().top);
        //console.log(thisul.position().top);
        var bottom_diff = bottom - thisbottom;


        if (bottom_diff > 0) {
          l2ul.css('padding-bottom', (base_padding + bottom_diff) + 'px')
        }
      }


    });
  });


})(jQuery, Drupal);