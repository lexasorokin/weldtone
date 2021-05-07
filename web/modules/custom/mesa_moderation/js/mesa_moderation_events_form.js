(function ($, Drupal) {
  // Use strict mode to reduce development errors.
  // @link http://www.nczonline.net/blog/2012/03/13/its-time-to-start-using-javascript-strict-mode/
  "use strict";

  function date_format(date) {

    return date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2) + ' ' + ('0' + date.getHours()).slice(-2) + ':' + ('0' + date.getMinutes()).slice(-2) + ':00';
  }

  var current_val = 'all';

  Drupal.behaviors.mesa_moderation_events_form = {
    attach: function (context, settings) {

      var select = $('<select class="moderation-date-presets">');

      $('<option value="all">All</option>').appendTo(select);
      $('<option value="outstanding">Outstanding</option>').appendTo(select);
      $('<option value="current">Current</option>').appendTo(select);
      $('<option value="upcoming">Upcoming</option>').appendTo(select);

      select.val(current_val);

      $('form[data-drupal-selector*="views-exposed-form-moderation-notifications"]').not('.select-added').prepend(select).addClass('select-added');

      select.on('change', function () {
        var val = $(this).val();

        current_val = val;

        var curDate = new Date();

        var todayStart = new Date(new Date().setHours(0, 0, 0, 0));

        var todayEnd = new Date(new Date().setHours(23, 59, 59, 999));

        var epochStart = new Date(0);
        var tomorrowStart = new Date(todayStart.getTime() + 1 * 24 * 60 * 60 * 1000);
        var yesterdayStart = new Date(todayStart.getTime() - 1 * 24 * 60 * 60 * 1000);

        var upcomingStart = new Date(todayStart.getTime() - 6 * 24 * 60 * 60 * 1000);

        var outstandingEnd = new Date(todayStart.getTime() - 7 * 24 * 60 * 60 * 1000);


        if (val == 'all') {

          $('[data-drupal-selector="edit-revision-timestamp-min"]').val(date_format(epochStart));
          $('[data-drupal-selector="edit-revision-timestamp-max"]').val(date_format(tomorrowStart));

        }

        if (val == 'current') {

          $('[data-drupal-selector="edit-revision-timestamp-min"]').val(date_format(todayStart));
          $('[data-drupal-selector="edit-revision-timestamp-max"]').val(date_format(todayEnd));

        }

        if (val == 'outstanding') {


          $('[data-drupal-selector="edit-revision-timestamp-min"]').val(date_format(epochStart));
          $('[data-drupal-selector="edit-revision-timestamp-max"]').val(date_format(outstandingEnd));
        }

        if (val == 'upcoming') {

          $('[data-drupal-selector="edit-revision-timestamp-min"]').val(date_format(upcomingStart));
          $('[data-drupal-selector="edit-revision-timestamp-max"]').val(date_format(yesterdayStart));


        }


      });
    }
  };
})(jQuery, Drupal);