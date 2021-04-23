
(function($, Drupal) {
  function InstagramFeed() {
    this.form = document.querySelector('form.instagram-feed-config-form');
    this.appIdField = document.querySelector('input[name="client_id"]');
    this.clientSecretField = document.querySelector('input[name="client_secret"]');
    this.redirectUriField = document.querySelector('input[name="auth_redirect_uri"]');
    this.codeField = document.querySelector('input[name="code"]');
    this.accessTokenField = document.querySelector('input[name="access_token"]');
  
    this.getAccessTokenBtn = document.querySelector('#get-access-token');
    this.refreshAccessTokenBtn = document.querySelector('#refresh-access-token');
  
    this.init = function() {
      if (this.getAccessTokenBtn) {
        this.getAccessTokenBtn.addEventListener('click', this.getAccessToken.bind(this));
      }

      if (this.refreshAccessTokenBtn) {
        this.refreshAccessTokenBtn.addEventListener('click', this.refreshAccessToken.bind(this));
      }
    }
  
    this.getAccessToken = function() {
      if (this.appIdField && this.clientSecretField && this.redirectUriField && this.codeField) {
        $.post('https://api.instagram.com/oauth/access_token', {
          client_id: this.appIdField.value,
          client_secret: this.clientSecretField.value,
          code: this.codeField.value,
          grant_type: 'authorization_code',
          redirect_uri: this.redirectUriField.value
        }, function(res) {
          console.log(res);
        }).fail(function(err) {
          alert('[ERROR TYPE] ' + err.responseJSON.error_type + '.\n[ERROR MESSAGE] ' + err.responseJSON.error_message);
          console.log(err);
        });
      }
    }

    this.refreshAccessToken = function() {
      var that = this;
      if (this.accessTokenField) {
        $.get('https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' + this.accessTokenField.value, function(res) {
          console.log(res);

          if (res.access_token) {
            that.accessTokenField.value = res.access_token;
            that.form.submit();
          }
        });
      }
    }
  }

  Drupal.behaviors.autocompleteInputs = {
    attach: function(context) {
      $(window).once('instagramFeedLoad').on('load', function() {
        var instagramFeed = new InstagramFeed();
        instagramFeed.init();       
      });
    }
  }
})(jQuery, Drupal);