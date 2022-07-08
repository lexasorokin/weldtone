(function ($, Drupal) {
  // Use strict mode to reduce development errors.
  // @link http://www.nczonline.net/blog/2012/03/13/its-time-to-start-using-javascript-strict-mode/
  "use strict";

  Drupal.behaviors.board_meetings_notification_form = {
    attach: function (context, settings) {

      $('[data-drupal-selector="edit-notifications-template"]').not('.tmp-processed').on('change', function () {
        var templateid = $(this).val();

        if (typeof (drupalSettings.notifications_templates[templateid]) != 'undefined') {
          var body = drupalSettings.notifications_templates[templateid].body;
          var subject = drupalSettings.notifications_templates[templateid].subject;

          $('[data-drupal-selector="edit-notifications-subject"]').val(subject);
          $('[data-drupal-selector="edit-notifications-body"]').val(body);
        }
      }).addClass('tmp-processed');

    }
  };
})(jQuery, Drupal);