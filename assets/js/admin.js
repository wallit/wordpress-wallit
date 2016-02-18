(function($) {
    $(function() {
        var $spinner = $('<div class="spinner" style="visibility: visible; position:absolute"></div>');

        $('#first-form').on('submit', function(e) {
            e.preventDefault();

            $(this).ajaxSubmit({
                url: ajaxurl,
                beforeSubmit: function(arr, $form) {
                    $('input[type=submit]', $form).after($spinner);
                },
                dataType: 'json',
                error: function() {
                    alert('There was an error with this request.');
                    $spinner.remove();
                },
                success: function(response) {
                    $spinner.remove();
                    if (response.success) {
                        window.location.reload(true);
                    }
                }
            });
        });
    });
})(jQuery);
