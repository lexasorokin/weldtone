(function($, Drupal) {
  function Popup() {
    this.$mask;
    this.$popup;
    this.$closeButton;
    this.dateString;
    this.frequency = drupalSettings.startupPopup.frequency;
    this.popupBody = drupalSettings.startupPopup.popupBody;
    this.dismissable = drupalSettings.startupPopup.dismissable;

    this.init = function() {
      var popupShown = true;

      console.log(this.dismissable);

      if (this.frequency === 'daily') {
        var date = new Date();
        this.dateString = '' + date.getUTCFullYear() + (date.getMonth() + 1) + date.getDate();
        popupShown = localStorage.getItem('civicPopupShownOn' + this.dateString); 
      } else if (this.frequency === 'once') {
        var popupShown = localStorage.getItem('civicPopupShown');
      }

      if (!popupShown || this.frequency === 'always') {
        this.createDomElements();
        this.appendDomElements();
      }
    }

    this.createDomElements = function() {
      this.$mask = $('<div id="startup-popup-mask" class="startup-popup-mask">');
      this.$popup = $('<div id="startup-popup" class="startup-popup">' + this.popupBody.value + '</div>');
      this.$closeButton = $('<button id="startup-popup-close-button btn" class="startup-popup-close-button">'
        + '<span class="x-line x-line-1"></span>'
        + '<span class="x-line x-line-2"></span>'
        + '</button>');
      this.$closeButton.on('click', this.closePopup.bind(this));

      if (this.dismissable) {
        this.$mask.on('click', this.closePopup.bind(this));
        this.$popup.on('click', function(e) { e.stopPropagation() });
      }
    }

    this.appendDomElements = function() {
      if (this.dismissable) {
        this.$popup.append(this.$closeButton);
      }
      
      this.$mask.append(this.$popup);
      $('body').append(this.$mask);
      $('#startup-popup a').on('click', this.setLocalStorage.bind(this));
    }

    this.closePopup = function(e) {
      document.querySelector('body').removeChild(this.$mask[0]);
      this.setLocalStorage(e);
    }

    this.setLocalStorage = function(e) {
      e.stopPropagation();

      if (this.frequency === 'daily') {
        localStorage.setItem('civicPopupShownOn' + this.dateString, true);
      } else {
        localStorage.setItem('civicPopupShown', true);
      }
    }
  }


  Drupal.behaviors.startupPopup = {
    attach: function(context) {
      if (context === document) {
        var popup = new Popup();
        popup.init();
      }
    }
  }
})(jQuery, Drupal);