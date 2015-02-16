<?php
    
class iMoneza {
	private $options;

    public function __construct()
    {
        $this->options = get_option('imoneza_options');

        // If there's a public Access API key, and 'no_snippet' isn't set, then make sure we add the JS snippet to every page
        if (isset($this->options['ra_api_key_public']) && $this->options['ra_api_key_public'] != '' && (!isset($this->options['no_snippet']) || $this->options['no_snippet'] == '0')) {
            add_action('wp_head', array($this, 'create_snippet'));
        }

        // Access API 2.0 feature: server-managed redirection, which requires an Access API private key
        if (FALSE && isset($this->options['ra_api_key_private']) && $this->options['ra_api_key_private'] != '') {
            add_action('template_redirect', array($this, 'imoneza_template_redirect'));
        }
    }

    // Access API 2.0 feature
    public function imoneza_template_redirect()
    {
        $this_post = get_post();
        $resourceAccess = new iMoneza_ResourceAccess();
        $response = $resourceAccess->getResourceAccess($this_post->ID, get_permalink($this_post->ID));
    }

    // Adds the iMoneza JavaScript snippet to the HTML head of a page
    public function create_snippet()
    {
        $this_post = get_post();
        $public_api_key = $this->options['ra_api_key_public'];
        $id = $this_post->ID;

        echo '
            <script src="' . IMONEZA__RA_UI_URL . '/assets/imoneza.js"></script>
            <script type="text/javascript">
                iMoneza.ResourceAccess.init({
                    ApiKey: "' . $public_api_key . '",
                    ResourceKey: ' . $id . '
                });
            </script>
        ';

        if (!isset($this->options['no_dynamic']) || $this->options['no_dynamic'] == '0') {
            echo '
                <script type="application/imoneza">
                    <Resource>
                        <Name>' . $this_post->post_title . '</Name>
                        <Title>' . $this_post->post_title . '</Title>
                        <Description>' . $this_post->post_excerpt . '</Description>
                        <PublicationDate>' . $this_post->post_date . '</PublicationDate>
                    </Resource>
                </script>
            ';
        }
    }
}

?>