(function ($) {
  $(document).ready(function () {
    $('.color-field').wpColorPicker({
      change: function () {

        var $form = $(this).closest('form');
        $($form).change();
      }
    });
  });
})(jQuery);