var $ = jQuery;

$(document).ready(function () {
  ({
    $openBtn: $('.js-contact-open'),
    $closeBtn: $('.js-contact-close'),
    open: function () {
      $('body').css('overflow', 'hidden');
      $('.js-contact-popup').fadeIn();
    },
    close: function () {
      $('body').css('overflow', 'visible');
      $('.js-contact-popup').fadeOut();
    },
    clickEvents: function () {
      var that = this;

      this.$openBtn.on('click', function (e) {
        e.preventDefault();

        that.open();
      });

      this.$closeBtn.on('click', function (e) {
        e.preventDefault();

        that.close();
      });
    },
    init: function () {
      this.clickEvents();
    }
  }).init();
});
