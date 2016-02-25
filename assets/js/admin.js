/**
 * iMoneza Wordpress Admin Javascript
 * @author iMoneza
 * @author Aaron Saray
 */
;(function($, history) {
    "use strict";

    /**
     * The iMoneza Admin ajax handler
     *
     * @param element
     * @param options
     */
    var imonezaAdminAjax = function(element, options) {
        var $element = $(element),
            $spinner = $('<div class="spinner imoneza-admin-spinner"></div>'),
            $resultMessage = $('<span class="imoneza-result-message" />'),
            isForm = $element.is('form'),
            $anchor = isForm ? $('input[type=submit]:first-child', $element) : $element;

        $anchor.after($resultMessage);

        this.settings = $.extend({
            url: ajaxurl,
            beforeSend: function() {
                this.settings.beforeCustomCallback();
                $anchor.after($spinner);
            }.bind(this),
            complete: function() {
                $spinner.remove();
            },
            dataType: 'json',
            error: function() {
                alert('There was an error with this request.');
                $spinner.remove();
            },
            success: function(response) {
                if (response.success) {
                    this.settings.successCustomCallback(response);
                    $resultMessage.html('<span class="dashicons dashicons-yes"></span> ' + response.data.message).removeClass('error').addClass('success');
                    setTimeout(function() {
                        $resultMessage.fadeOut('slow', function() {
                            $resultMessage.html('').show();
                        });
                    }, 5000);
                }
                else {
                    $resultMessage.text(response.data.message).removeClass('success').addClass('error');
                }
            }.bind(this),
            successCustomCallback: function(response) { // custom for if we have additional non-ui things to do
            },
            beforeCustomCallback: function() { // custom for additional non-ui things
            }
        }, options);

        $element.on(isForm ? 'submit' : 'click', function(e) {
            e.preventDefault();
            this.handleRequest(isForm, $element);
        }.bind(this));
    };

    /**
     * Kick off the ajax
     * @param isForm
     * @param $element
     */
    imonezaAdminAjax.prototype.handleRequest = function(isForm, $element) {
        isForm ? $element.ajaxSubmit(this.settings) : $.ajax(this.settings);
    };

    /**
     * Plugin Proxy for jQuery
     * @param options
     * @returns {*}
     * @constructor
     */
    function Plugin(options) {
        return this.each(function() {
            new imonezaAdminAjax(this, options);
        });
    }

    $.fn.imonezaAdminAjax = Plugin;


    $(function() {
        $('#imoneza-refresh-settings').imonezaAdminAjax({
            data: {
                action: "refresh_settings"
            },
            successCustomCallback: function(response) {
                $('#imoneza-property-title').text(response.data.title);
            }
        });
        $('#imoneza-options-form').imonezaAdminAjax({
            beforeCustomCallback: function() {
                history.replaceState(null, null, window.location.href.replace('&first-time=done', ''));
                $('#first-time-success-message').slideUp();
            }
        });
        $('#imoneza-first-form').imonezaAdminAjax({
            successCustomCallback: function() {
                window.location = window.location.href + '&first-time=done';
            }
        });

        var $overridePricing = $('#override-pricing');
        $('#show-override-pricing').on('click', function(e) {
            if ($(e.target).is(':checked')){
                $overridePricing.slideDown();
            }
            else {
                $overridePricing.slideUp();
            }
        });
    });

})(jQuery, window.history);
