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
                        window.location = window.location.href + '&first-time=done';
                    }
                    else {
                        $('#error-item').text(response.data).slideDown();
                    }
                }
            });
        });

        var $submitMessage = $('#submit-message');
        $('#imoneza-options-form').on('submit', function(e) {
            e.preventDefault();

            $(this).ajaxSubmit({
                url: ajaxurl,
                beforeSubmit: function(arr, $form) {
                    $('#first-time-success-message').slideUp();
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
                        $submitMessage.html('<span class="dashicons dashicons-yes"></span> Your settings have been saved!').removeClass('success').addClass('success');
                        setTimeout(function() {
                            $submitMessage.fadeOut('slow', function() {
                                $submitMessage.html('').show();
                            });
                        }, 5000);
                    }
                    else {
                        $submitMessage.text(response.data).removeClass('success').addClass('error');
                    }
                }
            });
        });
    });
})(jQuery);
