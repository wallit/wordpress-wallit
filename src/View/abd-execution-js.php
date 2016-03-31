<script>
    jQuery(function($) {
        ABD.detect({
            onDetected: function() {
                var $alert = $('<div id="imoneza-adblock-notification" />').text("<?= esc_html($message) ?>");
                $('body').prepend($alert);
            },
            strategyOptions: {
                strategyExternalJS: {
                    rootPath: "<?= $jsDir ?>/abd-strategy"
                }
            }
        });
    });
</script>
