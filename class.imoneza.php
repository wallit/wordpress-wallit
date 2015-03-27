<?php
    
class iMoneza {
	private $options;

    public function __construct()
    {
        $this->options = get_option('imoneza_options');

        // If there's an Access API access key, and we're using client-side access control, create the JavaScript snippet
        if (isset($this->options['ra_api_key_access']) && $this->options['ra_api_key_access'] != '' && (!isset($this->options['access_control']) || $this->options['access_control'] == 'JS')) {
            add_action('wp_head', array($this, 'create_snippet'));
        }

        // If 'no_dynamic' isn't set, then make sure we add the dynamic resource creation block to every page
        if (!isset($this->options['no_dynamic']) || $this->options['no_dynamic'] != '1') {
            add_action('wp_head', array($this, 'create_dynamic'));
        }

        // Perform server-side access control
        if (isset($this->options['ra_api_key_secret']) && $this->options['ra_api_key_secret'] != '' && isset($this->options['access_control']) && $this->options['access_control'] == 'SS') {
            add_action('template_redirect', array($this, 'imoneza_template_redirect'));
            add_action('wp_head', array($this, 'create_reference'));
        }
    }

    public function imoneza_template_redirect()
    {
        $resourceValues = $this->get_resource_values();
        if ($resourceValues['key'] == '')
            return;

        $resourceAccess = new iMoneza_ResourceAccess();
        $response = $resourceAccess->getResourceAccess($resourceValues['key'], $resourceValues['url']);
    }

    private function get_resource_values()
    {
        $values = array();
        $values['key'] = '';
        $values['name'] = '';
        $values['title'] = '';
        $values['description'] = '';
        $values['publicationDate'] = '';
        $values['url'] = '';
        
        if (is_page() || is_single()) {
            $this_post = get_post();

            $values['key'] = $this_post->ID;
            $values['name'] = $this_post->post_title;
            $values['title'] = $this_post->post_title;
            $values['description'] = $this_post->post_excerpt;
            $values['publicationDate'] = $this_post->post_date;
            $values['url'] = get_permalink($this_post->ID);
        } else if (is_category()) {
            $cat = get_query_var('cat');
            $this_category = get_category($cat);

            $values['key'] = 'Category-' . $this_category->cat_ID;
            $values['name'] = $this_category->cat_name;
            $values['title'] = $this_category->cat_name;
            $values['url'] = get_category_link($this_category->cat_ID);
        } else if (is_front_page()) {
            $values['key'] = 'FrontPage';
            $values['name'] = 'Front Page';
            $values['title'] = 'Front Page';
            $values['url'] = get_home_url();
        } else if (is_tag()) {
            $tag = get_query_var('tag');
            $this_tag = get_term_by('name', $tag, 'post_tag');

            $values['key'] = 'Tag-' . $this_tag->term_id;
            $values['name'] = $this_tag->name;
            $values['title'] = $this_tag->name;
            $values['description'] = $this_tag->description;
            $values['url'] = get_term_link($this_tag->term_id);
        }

        // Ignore archive pages
        // Ignore feeds

        return $values;
    }

    // Adds the iMoneza JavaScript snippet to the HTML head of a page
    public function create_snippet()
    {
        $public_api_key = $this->options['ra_api_key_access'];
        $resourceValues = $this->get_resource_values();

        if ($resourceValues['key'] != '') {
            echo '
                <script src="' . IMONEZA__RA_UI_URL . '/assets/imoneza.js"></script>
                <script type="text/javascript">
                    iMoneza.ResourceAccess.init({
                        ApiKey: "' . $public_api_key . '",
                        ResourceKey: "' . $resourceValues['key'] . '"
                    });
                </script>
            ';
        }
    }

    // Adds the iMoneza JavaScript reference to the HTML head of a page
    public function create_reference()
    {
        echo '<script src="' . IMONEZA__RA_UI_URL . '/assets/imoneza.js"></script>';
    }

    // Adds the dynamic resource creation block to the HTML head of a page
    public function create_dynamic()
    {
        $resourceValues = $this->get_resource_values();

        if ($resourceValues['key'] != '') {
            echo '
                <script type="application/imoneza">
                    <Resource>
                        <Name>' . $resourceValues['name'] . '</Name>
                        <Title>' . $resourceValues['title'] . '</Title>' .
                        ($resourceValues['description'] == '' ? '' :  '<Description>' . $resourceValues['description'] . '</Description>') . 
                        ($resourceValues['publicationDate'] == '' ? '' : '<PublicationDate>' . $resourceValues['publicationDate'] . '</PublicationDate>') .
                    '</Resource>
                </script>
            ';
        }
    }
}

?>