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
            showIndications: true,
            url: ajaxurl,
            beforeSend: function() {
                this.settings.beforeCustomCallback();
                if (this.settings.showIndications) {
                    $anchor.after($spinner);
                }
            }.bind(this),
            complete: function() {
                $spinner.remove();
            },
            dataType: 'json',
            error: function() {
                alert('There was an error with this request.');
                if (this.settings.showIndications) {
                    $spinner.remove();
                }
            },
            success: function(response) {
                if (response.success) {
                    this.settings.successCustomCallback(response);
                    if (this.settings.showIndications) {
                        $resultMessage.html('<span class="dashicons dashicons-yes"></span> ' + response.data.message).removeClass('error').addClass('success');
                        setTimeout(function () {
                            $resultMessage.fadeOut('slow', function () {
                                $resultMessage.html('').show();
                            });
                        }, 5000);
                    }

                }
                else {
                    if (this.showIndications) {
                        $resultMessage.text(response.data.message).removeClass('success').addClass('error');
                    }
                }
            }.bind(this),
            successCustomCallback: function(response) { // custom for if we have additional non-ui things to do
            },
            beforeCustomCallback: function() { // custom for additional non-ui things
            }
        }, options);

        $element.on(isForm ? 'submit' : 'click ajax', function(e) {
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


    /*******************************************************************************************************************/

    /**
     * Handles the pricing overrides on the admin post page
     */
    var overridePricing = function() {
        var $pricingToggle = $('#show-override-pricing');
        var $pricingPanel = $('#override-pricing');

        /**
         * The initializer basically
         */
        this.handle = function() {
            if ($pricingToggle.length) {
                addPanelHandler();
                refreshAndPopulateData();
            }
        };

        /**
         * add the toggle for the panel box
         */
        function addPanelHandler() {
            $pricingToggle.on('click', function (e) {
                if ($(e.target).is(':checked')) {
                    $pricingPanel.slideDown();
                }
                else {
                    $pricingPanel.slideUp();
                }
            });
        }

        /**
         * This is used to refresh the data for the property and then change any field values if necessary
         */
        function refreshAndPopulateData()
        {
            $pricingPanel.imonezaAdminAjax({
                showIndications: false,
                data: {
                    action: "refresh_settings"
                },
                successCustomCallback: function(response) {
                    var $autoDisplay = $('#message-automatically-manage'),
                        $manualDisplay = $('#message-manually-manage'),
                        $overrideSelectLabel = $('#show-override-pricing + span');

                    if (response.data.options.dynamicallyCreateResources) {
                        $autoDisplay.show();
                        $manualDisplay.hide();
                        $overrideSelectLabel.html($overrideSelectLabel.data('automatically-manage'));
                    }
                    else {
                        $autoDisplay.hide();
                        $manualDisplay.show();
                        $overrideSelectLabel.html($overrideSelectLabel.data('manually-manage'));
                    }
                }
            });
            $pricingPanel.trigger('ajax');
        }
    };


    /*******************************************************************************************************************/

    $(function() {
        $('#imoneza-refresh-settings').imonezaAdminAjax({
            data: {
                action: "refresh_settings"
            },
            successCustomCallback: function(response) {
                $('#imoneza-property-title').text(response.data.options.propertyTitle);
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

        (new overridePricing).handle();

    });

})(jQuery, window.history);
