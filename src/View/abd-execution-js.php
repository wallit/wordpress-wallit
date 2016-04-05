<script>
    jQuery(function($) {
        ABD.detect({
            onDetected: function() {
                var $alert;
                if (document.getElementById('imoneza-adblock-notification')) {
                    $alert = $('#imoneza-adblock-notification');
                }
                else {
                    $alert = $('<div id="imoneza-adblock-notification" />');
                    $('body').prepend($alert);
                }
                $alert.text("<?= esc_html($this->message) ?>");
            },
            strategyOptions: {
                strategyExternalJS: {
                    rootPath: "<?= $this->jsDir ?>/abd-strategy"
                }
            }
        });
    });
</script>
