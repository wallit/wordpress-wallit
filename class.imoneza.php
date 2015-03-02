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
        $public_api_key = $this->options['ra_api_key_public'];

        $key = '';
        $name = '';
        $title = '';
        $description = '';
        $publicationDate = '';
        
        if (is_page() || is_single()) {
            $this_post = get_post();

            $key = $this_post->ID;
            $name = $this_post->post_title;
            $title = $this_post->post_title;
            $description = $this_post->post_excerpt;
            $publicationDate = $this_post->post_date;
        } else if (is_category()) {
            $cat = get_query_var('cat');
            $this_category = get_category($cat);

            $key = 'Category-' . $this_category->cat_ID;
            $name = $this_category->cat_name;
            $title = $this_category->cat_name;
        } else if (is_front_page()) {
            $key = 'FrontPage';
            $name = 'Front Page';
            $title = 'Front Page';
        } else if (is_tag()) {
            $tag = get_query_var('tag');
            $this_tag = get_term_by('name', $tag, 'post_tag');

            $key = 'Tag-' . $this_tag->term_id;
            $name = $this_tag->name;
            $title = $this_tag->name;
            $description = $this_tag->description;
        }

        // Ignore archive pages
        // Ignore feeds

        if ($key != '') {
            echo '
                <script src="' . IMONEZA__RA_UI_URL . '/assets/imoneza.js"></script>
                <script type="text/javascript">
                    iMoneza.ResourceAccess.init({
                        ApiKey: "' . $public_api_key . '",
                        ResourceKey: "' . $key . '"
                    });
                </script>
            ';

            if (!isset($this->options['no_dynamic']) || $this->options['no_dynamic'] == '0') {
                echo '
                    <script type="application/imoneza">
                        <Resource>
                            <Name>' . $name . '</Name>
                            <Title>' . $title . '</Title>' .
                            ($description == '' ? '' :  '<Description>' . $description . '</Description>') . 
                            ($publicationDate == '' ? '' : '<PublicationDate>' . $publicationDate . '</PublicationDate>') .
                        '</Resource>
                    </script>
                ';
            }
        }
    }
}

?>