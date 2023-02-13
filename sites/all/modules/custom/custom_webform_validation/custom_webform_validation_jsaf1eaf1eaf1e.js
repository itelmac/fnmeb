(function ($) {
  $(document).ready(function () {
    // Turn off browser tooltips
    $('.webform-client-form').attr('novalidate', '');

    //If form errors and error messages
    if ($('.form-item').children().hasClass('error') && $('.messages.error')) {

      //Add role of alert to the messages
      $('.messages.error').attr("role", "alert");
      
      //Loop through webform components to determine which ones have errors and rewrite labels appropriately
      $('.webform-component:has(.error)').each(function (index) {
        var errorMessage = ($('.messages.error li').length > 0) ? $('.messages.error li')[index].innerHTML : $('.messages.error').text();
        var errorLabel = $(this).find('label:first-child').addClass('error');
        errorLabel.text(errorMessage);
      });

      //Count number of error messages
      var numberOfErrors = ($('.messages.error li').length > 1) ? $('.messages.error li').length : 1;

      //Rewrite error message at top of form to indicate total error messages and full list
      $('.messages.error').prepend(`<p>There are ${numberOfErrors} error(s) present in form.</p>`).addClass('webform-errors');
    };
  });
})(jQuery);
