<?php
    
class iMoneza_Admin {
	private $options;

    public function __construct()
    {
        session_start();

        $this->options = get_option('imoneza_options');

        add_action('admin_menu', array( $this, 'add_plugin_page'));
        add_action('admin_init', array( $this, 'page_init'));
        add_action('admin_notices', array($this, 'admin_notices'));

        // If Management API public and private keys are set, then add the iMoneza metabox
        if (isset($this->options['rm_api_key_access']) && $this->options['rm_api_key_access'] != '' && isset($this->options['rm_api_key_secret']) && $this->options['rm_api_key_secret'] != '') {
            add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
            add_action('save_post', array($this, 'imoneza_save_meta_box_data'));
        }
    }

    public function add_meta_boxes($post)
    {
        $screens = array('post', 'page');
        foreach ($screens as $screens) {
            add_meta_box(
                'imoneza-meta-box',
                'iMoneza',
                array($this, 'render_imoneza_meta_box'),
                $screen,
                'normal',
                'default'
            );
        }
    }

    public function render_imoneza_meta_box($post)
    {
        // Add an nonce field so we can check for it later.
	    wp_nonce_field( 'imoneza_meta_box', 'imoneza_meta_box_nonce' );

        try {
            $resourceManagement = new iMoneza_ResourceManagement();
            //$response = $resourceManagement->getProperty();
            $resource = $resourceManagement->getResource($post->ID, true);

            if (IMONEZA__DEBUG) {
                echo '<p><a onclick="document.getElementById(\'imonezaDebugResource\').style.display = document.getElementById(\'imonezaDebugResource\').style.display == \'none\' ? \'block\' : \'none\';">Resource</a></p>';
                echo '<pre id="imonezaDebugResource" style="display:none;">';
                print_r($resource);
                echo '</pre>';
            }

            $isManaged = $resource['IsManaged'] == 1 && $resource['Active'] == 1;

            if (!$isManaged) {
                $property = $resourceManagement->getProperty();
            } else {
                $property = $resource['Property'];
            }

            if (IMONEZA__DEBUG) {
                echo '<p><a onclick="document.getElementById(\'imonezaDebugProperty\').style.display = document.getElementById(\'imonezaDebugProperty\').style.display == \'none\' ? \'block\' : \'none\';">Property</a></p>';
                echo '<pre id="imonezaDebugProperty" style="display:none;">';
                print_r($resource);
                echo '</pre>';
            }

            $pricingGroups = '';
            $pricingGroupsList = $isManaged ? $resource['Property']['PricingGroups'] : $property['PricingGroups'];
            foreach ($pricingGroupsList as $pricingGroup) {
                $isSelected = $isManaged ? $resource['PricingGroup']['PricingGroupID'] == $pricingGroup['PricingGroupID'] : $pricingGroup['IsDefault'] == 1;
                $pricingGroups .= '<option value="' . $pricingGroup['PricingGroupID'] . '"' . ($isSelected ? ' selected="selected"' : '' ) . '>' . $pricingGroup['Name'] . '</option>';
            }

            // If there are no pricing tiers, set a default zero tier in case we need it
            if (!isset($resource['ResourcePricingTiers']) || count($resource['ResourcePricingTiers']) == 0) {
                $resource['ResourcePricingTiers'] = array(
                    array('Tier' => 0, 'Price' => '0.00')
                );
            }

            echo '
            <script type="text/javascript">
            function imoneza_update_display() {
                if (document.getElementById("imoneza_isManaged").checked) {
                    if (document.getElementById("imoneza_name").value == "" && document.getElementById("title"))
                        document.getElementById("imoneza_name").value = document.getElementById("title").value;
                    if (document.getElementById("imoneza_title").value == "" && document.getElementById("title"))
                        document.getElementById("imoneza_title").value = document.getElementById("title").value;
                    if (document.getElementById("imoneza_description").value == "" && document.getElementById("excerpt"))
                        document.getElementById("imoneza_description").value = document.getElementById("excerpt").value;
                }
                imoneza_toggle_class(".imoneza_row", document.getElementById("imoneza_isManaged").checked);

                if (document.getElementById("imoneza_isManaged").checked) {
                    var pm = document.getElementById("imoneza_pricingModel");
                    var selectedPricingModel = pm.options[pm.selectedIndex].value;
                    imoneza_toggle_class(".imoneza_row_price", (selectedPricingModel == "FixedPrice" || selectedPricingModel == "VariablePrice"));
                    imoneza_toggle_class(".imoneza_row_price_tier", (selectedPricingModel == "TimeTiered" || selectedPricingModel == "ViewTiered"));

                    var epu = document.getElementById("imoneza_expirationPeriodUnit");
                    var expirationPeriodUnit = epu.options[epu.selectedIndex].value;
                    imoneza_toggle_class(".imoneza_row_price_expiration", (selectedPricingModel == "FixedPrice" || selectedPricingModel == "VariablePrice") && (expirationPeriodUnit != "Never"));
                }
            }

            function imoneza_toggle_class(className, isVisible) {
                var els = document.querySelectorAll(className);
                for (var i = 0; i < els.length; ++i) {
                    els[i].style.display = (isVisible ? "table-row" : "none");
                }
            }

            function imoneza_add_tier(label) {
                var t = document.getElementById("imoneza_tiers");
                var r = t.insertRow(t.rows.length - 1);
                var c0 = r.insertCell(0);
                var c1 = r.insertCell(1);
                var c2 = r.insertCell(2);
                if (label == \'minutes\')
                    label = \'<select name="imoneza_tier_price_multiplier[]"><option value="1">minutes</option><option value="60">hours</option><option value="1440">days</option></select>\';
                c0.innerHTML = "<input type=\"text\" size=\"5\" name=\"imoneza_tier[]\" /> " + label;
                c1.innerHTML = "<input type=\"text\" name=\"imoneza_tier_price[]\" />";
                c2.innerHTML = "<a href=\"#\" onclick=\"return imoneza_remove_tier(this);\">Remove</a>";
                
                return false;
            }

            function imoneza_remove_tier(t) {
                var r = t.parentNode.parentNode;
                r.parentNode.removeChild(r);

                return false;
            }
            </script>';

            $rowClass = 'imoneza_row';
            $priceRowClass = ' imoneza_row_price';
            $expirationValueClass = ' imoneza_row_price_expiration';
            $priceTierClass = 'imoneza_row_price_tier';
            $isHidden = FALSE;

            $styleAttr = '';
            $priceStyleAttr = '';
            $expirationStyleAttr = '';
            $priceTierStyleAttr = '';

            if (!$isManaged)
                $styleAttr .= ' style="display:none;"';

            if (!$isManaged || ($resource['PricingModel'] != 'FixedPrice' && $resource['PricingModel'] != 'VariablePrice'))
                $priceStyleAttr = ($styleAttr == '' ? ' style="display:none;"' : $styleAttr);

            if (!$isManaged || ($resource['PricingModel'] != 'FixedPrice' && $resource['PricingModel'] != 'VariablePrice') || $resource['ExpirationPeriodUnit'] == 'Never')
                $expirationStyleAttr = ($priceStyleAttr == '' ? ' style="display:none;"' : $priceStyleAttr);

            if (!$isManaged || ($resource['PricingModel'] != 'TimeTiered' && $resource['PricingModel'] != 'ViewTiered'))
                $priceTierStyleAttr = ($priceStyleAttr == '' ? ' style="display:none;"' : $priceStyleAttr);

            echo '<table><tbody>';
	        echo '<tr><td width="120"><input type="hidden" id="imoneza_isManaged_original" name="imoneza_isManaged_original" value="' . ($isManaged ? '1' : '0') . '" /><input onclick="imoneza_update_display()" type="checkbox" id="imoneza_isManaged" name="imoneza_isManaged" value="1"' . ($isManaged ? ' checked' : '') . ' /></td><td colspan="2"><label for="imoneza_isManaged">Use iMoneza to manage access to this resource</label></td></tr>';
            
            echo '<tr class="' . $rowClass . '"' . $styleAttr . '><td colspan="3"><strong>Metadata</strong></td></tr>';
	        echo '<tr class="' . $rowClass . '"' . $styleAttr . '><td><label for="imoneza_name">Internal Name</label></td><td><input type="text" id="imoneza_name" name="imoneza_name" value="' . esc_attr($resource['Name']) . '" size="25" /></td><td><small>A friendly name for the property to help you identify it. This name is never displayed publicly to consumers. Defaults to the article title.</small></td></tr>';
	        echo '<tr class="' . $rowClass . '"' . $styleAttr . '><td><label for="imoneza_title">Title</label></td><td><input type="text" id="imoneza_title" name="imoneza_title" value="' . esc_attr($resource['Title']) . '" size="25" /></td><td><small>The title of the property which gets displayed to consumers. Defaults to the article title.</small></td></tr>';
	        echo '<tr class="' . $rowClass . '"' . $styleAttr . '><td><label for="imoneza_byline">Byline</label></td><td><input type="text" id="imoneza_byline" name="imoneza_byline" value="' . esc_attr($resource['Byline']) . '" size="25" /></td><td><small>For instance, the author of the post.</small></td></tr>';
	        echo '<tr class="' . $rowClass . '"' . $styleAttr . '><td><label for="imoneza_description">Description</label></td><td><textarea id="imoneza_description" name="imoneza_description">' . esc_attr($resource['Description']) . '</textarea></td><td><small>A short description of the post. Defaults to the post\'s excerpt.</small></td></tr>';

            echo '<tr class="' . $rowClass . '"' . $styleAttr . '><td colspan="3"><strong>Pricing</strong></td></tr>';
	        echo '<tr class="' . $rowClass . '"' . $styleAttr . '><td><label for="imoneza_pricingGroup">Pricing Group</label></td><td><select id="imoneza_pricingGroup" name="imoneza_pricingGroup">' . $pricingGroups . '</select></td></tr>';
	        echo '<tr class="' . $rowClass . '"' . $styleAttr . '><td><label for="imoneza_pricingModel">Pricing Model</label></td><td><select id="imoneza_pricingModel" name="imoneza_pricingModel" onchange="imoneza_update_display()">' .
                '<option value="Inherit"' . ($resource['PricingModel'] == 'Inherit' ? ' selected="selected"' : '') . '>Inherit</option>' .
                '<option value="Free"' . ($resource['PricingModel'] == 'Free' ? ' selected="selected"' : '') . '>Free</option>' .
                '<option value="FixedPrice"' . ($resource['PricingModel'] == 'FixedPrice' ? ' selected="selected"' : '') . '>Fixed Price</option>' .
                '<option value="VariablePrice"' . ($resource['PricingModel'] == 'VariablePrice' ? ' selected="selected"' : '') . '>Variable Price</option>' .
                '<option value="TimeTiered"' . ($resource['PricingModel'] == 'TimeTiered' ? ' selected="selected"' : '') . '>Time Tiered</option>' .
                '<option value="ViewTiered"' . ($resource['PricingModel'] == 'ViewTiered' ? ' selected="selected"' : '') . '>View Tiered</option>' .
                '<option value="SubscriptionOnly"' . ($resource['PricingModel'] == 'SubscriptionOnly' ? ' selected="selected"' : '') . '>Subscription Only</option>' .
                '</select></td></tr>';

            echo '<tr class="' . $rowClass . $priceRowClass . '"' . $priceStyleAttr . '><td colspan="3"><strong>Custom Pricing</strong></td></tr>';
	        echo '<tr class="' . $rowClass . $priceRowClass . '"' . $priceStyleAttr . '><td><label for="imoneza_price">Price</label></td><td><input type="text" id="imoneza_price" name="imoneza_price" value="' . esc_attr($resource['Price']) . '" size="25" /></td></tr>';
	        echo '<tr class="' . $rowClass . $priceRowClass . '"' . $priceStyleAttr . '><td><label for="imoneza_expirationPeriodUnit">Expiration Period</label></td><td><select id="imoneza_expirationPeriodUnit" name="imoneza_expirationPeriodUnit" onchange="imoneza_update_display()">' .
                '<option value="Never"' . ($resource['ExpirationPeriodUnit'] == 'Never' ? ' selected="selected"' : '') . '>Never</option>' .
                '<option value="Years"' . ($resource['ExpirationPeriodUnit'] == 'Years' ? ' selected="selected"' : '') . '>Years</option>' .
                '<option value="Months"' . ($resource['ExpirationPeriodUnit'] == 'Months' ? ' selected="selected"' : '') . '>Months</option>' .
                '<option value="Weeks"' . ($resource['ExpirationPeriodUnit'] == 'Weeks' ? ' selected="selected"' : '') . '>Weeks</option>' .
                '<option value="Days"' . ($resource['ExpirationPeriodUnit'] == 'Days' ? ' selected="selected"' : '') . '>Days</option>' .
                '</select></td></tr>';
	        echo '<tr class="' . $rowClass . $priceRowClass . $expirationValueClass . '"' . $expirationStyleAttr . '><td><label for="imoneza_expirationPeriodValue">Expiration Duration</label></td><td><input type="text" id="imoneza_expirationPeriodValue" name="imoneza_expirationPeriodValue" value="' . esc_attr($resource['ExpirationPeriodValue']) . '" size="25" /></td></tr>';

            echo '<tr class="' . $rowClass . ' ' . $priceTierClass . '"' . $priceTierStyleAttr . '><td colspan="2"><strong>Pricing Tiers</strong></td><td><small>You must have at least one tier, and there must be one tier of 0 minutes or 0 views.</small></td></tr>';
            echo '<tr class="' . $rowClass . ' ' . $priceTierClass . '"' . $priceTierStyleAttr . '><td colspan="3"><table id="imoneza_tiers"><tbody><tr><th>Tier</th><th>Price</th></tr>';
            foreach ($resource['ResourcePricingTiers'] as $tier) {
                $label = ' views';
                $value = $tier['Tier'];
                if ($resource['PricingModel'] == 'TimeTiered') {
                    $label = 'minutes';
                    if ($value > 0 && $value % 1440 == 0) {
                        $label = 'days';
                        $value = $value / 1440;
                    } else if ($value > 0 && $value % 60 == 0) {
                        $label = 'hours';
                        $value = $value / 60;
                    }
                    $label = '<select name="imoneza_tier_price_multiplier[]"><option value="1"' . ($label == 'minutes' ? ' selected' : '') . '>minutes</option><option value="60"' . ($label == 'hours' ? ' selected' : '') . '>hours</option><option value="1440"' . ($label == 'days' ? ' selected' : '') . '>days</option></select>';
                }
                echo '<tr><td><input type="text" value="' . $value . '" name="imoneza_tier[]" size="5" />' . $label . '</td><td><input type="text" value="' . number_format($tier['Price'], 2) . '" name="imoneza_tier_price[]" /></td><td>' . ($tier['Tier'] > 0 ? '<a href="#" onclick="return imoneza_remove_tier(this);">Remove Tier</a>' : '') . '</td></tr>';
            }
            echo '<tr><td width="220"></td><td width="160"></td><td><a href="#" onclick="return imoneza_add_tier(\'' . ($resource['PricingModel'] == 'ViewTiered' ? 'views' : 'minutes') . '\');">Add Tier</a></td></tr>';
            echo '</tbody></table></td></tr>';

            echo '</tbody></table>';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function imoneza_save_meta_box_data($post_id) {

	    /*
	     * We need to verify this came from our screen and with proper authorization,
	     * because the save_post action can be triggered at other times.
	     */

	    // Check if our nonce is set.
	    if (!isset($_POST['imoneza_meta_box_nonce']))
		    return;

	    // Verify that the nonce is valid.
	    if (!wp_verify_nonce($_POST['imoneza_meta_box_nonce'], 'imoneza_meta_box'))
		    return;

	    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		    return;

        // AJAX? Not used here
        if (defined('DOING_AJAX') && DOING_AJAX)
            return;

        // Return if it's a post revision
        if (false !== wp_is_post_revision($post_id))
            return;

	    // Check the user's permissions.
	    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
		    if (!current_user_can('edit_page', $post_id))
			    return;
	    } else {
		    if (!current_user_can('edit_post', $post_id))
			    return;
	    }

        if ($_POST['imoneza_isManaged'] != '1') {
            if ($_POST['imoneza_isManaged_original'] == '1') {
                // user unchecked the box for iMoneza to manage the resource
                $resourceManagement = new iMoneza_ResourceManagement();
                $data = array(
                    'ExternalKey' => $post_id, 
                    'Active' => 0
                );
                $resource = $resourceManagement->putResource($post_id, $data);

                $this->setUpdatedNotice('iMoneza settings for the resource were successfully updated.');
            }
            return;
        }

	    /* OK, it's safe for us to save the data now. */

        $data = array(
            'ExternalKey' => $post_id,
            'Active' => 1,
            'Name' => sanitize_text_field($_POST['imoneza_name']),
            'Title' => sanitize_text_field($_POST['imoneza_title']),
            'Byline' => sanitize_text_field($_POST['imoneza_byline']),
            'Description' => sanitize_text_field($_POST['imoneza_description']),
            'URL' => get_permalink($post_id),
            'PublicationDate' => get_the_time('c', $post_id),
            'PricingGroup' => array('PricingGroupID' => sanitize_text_field($_POST['imoneza_pricingGroup'])),
            'PricingModel' => sanitize_text_field($_POST['imoneza_pricingModel'])
        );

        if ($_POST['imoneza_pricingModel'] == 'FixedPrice' || $_POST['imoneza_pricingModel'] == 'VariablePrice') {
            $data['Price'] = sanitize_text_field($_POST['imoneza_price']);
            $data['ExpirationPeriodUnit'] = sanitize_text_field($_POST['imoneza_expirationPeriodUnit']);
            if ($_POST['imoneza_expirationPeriodUnit'] != 'Never')
                $data['ExpirationPeriodValue'] = sanitize_text_field($_POST['imoneza_expirationPeriodValue']);
        }

        if ($_POST['imoneza_pricingModel'] == 'ViewTiered' || $_POST['imoneza_pricingModel'] == 'TimeTiered') {
            $tiers = $_POST['imoneza_tier'];
            $prices = $_POST['imoneza_tier_price'];
            $multiplier = $_POST['imoneza_tier_price_multiplier'];
            $vals = array();
            for ($i = 0; $i < count($tiers); ++$i)
                $vals[] = array('Tier' => $tiers[$i] * (isset($multiplier) ? $multiplier[$i] : 1), 'Price' => $prices[$i]);

            $data['ResourcePricingTiers'] = $vals;
        }

        $resourceManagement = new iMoneza_ResourceManagement();
        try {
            $resource = $resourceManagement->putResource($post_id, $data);
            $this->setUpdatedNotice('iMoneza settings for the resource were successfully updated.');
        } catch (Exception $e) {
            $this->setErrorNotice($e->getMessage());
        }
    }

    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page('iMoneza', 'iMoneza', 'manage_options', 'imoneza-settings-admin', array( $this, 'create_admin_page' ));
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>iMoneza Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields('imoneza_settings');
                do_settings_sections('imoneza-settings-admin');
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    public function setUpdatedNotice($notice) {
        session_start();
        $_SESSION['iMoneza_UpdatedNotice'] = $notice;
    }

    public function setErrorNotice($notice) {
        session_start();
        $_SESSION['iMoneza_ErrorNotice'] = $notice;
    }

    public function admin_notices() {
        if (isset($_SESSION['iMoneza_UpdatedNotice']) && $_SESSION['iMoneza_UpdatedNotice'] != '') {
            ?>
                <div class="updated">
                    <p><?= $_SESSION['iMoneza_UpdatedNotice'] ?></p>
                </div>
            <?php
            unset($_SESSION['iMoneza_UpdatedNotice']);
        }
        if (isset($_SESSION['iMoneza_ErrorNotice']) && $_SESSION['iMoneza_ErrorNotice'] != '') {
            ?>
                <div class="error">
                    <p><?= $_SESSION['iMoneza_ErrorNotice'] ?></p>
                </div>
            <?php
            unset($_SESSION['iMoneza_ErrorNotice']);
        }
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting('imoneza_settings', 'imoneza_options', array( $this, 'sanitize' ));

        add_settings_section('imoneza_settings_ra_api_key', 'Resource Access API', array($this, 'print_section_info_ra_api'), 'imoneza-settings-admin');  
        add_settings_field('ra_api_key_access', 'Access Key', array( $this, 'ra_api_key_access_callback' ), 'imoneza-settings-admin', 'imoneza_settings_ra_api_key');
        add_settings_field('ra_api_key_secret', 'Secret Key', array( $this, 'ra_api_key_secret_callback' ), 'imoneza-settings-admin', 'imoneza_settings_ra_api_key');

        add_settings_section('imoneza_settings_rm_api_key', 'Resource Management API', array($this, 'print_section_info_rm_api'), 'imoneza-settings-admin');
        add_settings_field('rm_api_key_access', 'Access Key', array( $this, 'rm_api_key_access_callback' ), 'imoneza-settings-admin', 'imoneza_settings_rm_api_key');
        add_settings_field('rm_api_key_secret', 'Secret Key', array( $this, 'rm_api_key_secret_callback' ), 'imoneza-settings-admin', 'imoneza_settings_rm_api_key');

        add_settings_section('imoneza_settings_dynamic_resource_creation', 'Dynamic Resource Creation', array($this, 'print_section_info_dynamic_resource_creation'), 'imoneza-settings-admin');
        add_settings_field('no_dynamic', 'Do not include dynamic resource creation block on every page', array( $this, 'no_dynamic_callback' ), 'imoneza-settings-admin', 'imoneza_settings_dynamic_resource_creation');

        add_settings_section('imoneza_access_control', 'Access Control', array($this, 'print_section_info_access_control'), 'imoneza-settings-admin');
        add_settings_field('access_control', 'Access Control Method', array( $this, 'access_control_callback' ), 'imoneza-settings-admin', 'imoneza_access_control');
        add_settings_field('access_control_excluded_user_agents', 'Excluded User Agents', array( $this, 'access_control_excluded_user_agents_callback' ), 'imoneza-settings-admin', 'imoneza_access_control');

        add_settings_section('imoneza_help', 'Help', array($this, 'print_section_info_help'), 'imoneza-settings-admin');
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();

        if (isset($input['rm_api_key_access']))
            $new_input['rm_api_key_access'] = sanitize_text_field($input['rm_api_key_access']);
        if (isset($input['rm_api_key_secret']))
            $new_input['rm_api_key_secret'] = sanitize_text_field($input['rm_api_key_secret']);

        if (isset($input['ra_api_key_access']))
            $new_input['ra_api_key_access'] = sanitize_text_field($input['ra_api_key_access']);
        if (isset($input['ra_api_key_secret']))
            $new_input['ra_api_key_secret'] = sanitize_text_field($input['ra_api_key_secret']);

        if (isset($input['no_dynamic']) && $input['no_dynamic'] == '1')
            $new_input['no_dynamic'] = '1';
        else
            $new_input['no_dynamic'] = '0';

        if (isset($input['use_access_control']) && $input['use_access_control'] == '1')
            $new_input['use_access_control'] = '1';
        else
            $new_input['use_access_control'] = '0';

        if (isset($input['access_control']))
            $new_input['access_control'] = sanitize_text_field($input['access_control']);

        if (isset($input['access_control_excluded_user_agents']))
            $new_input['access_control_excluded_user_agents'] = implode("\n", array_map('sanitize_text_field', str_replace("\r", "", explode("\n", $input['access_control_excluded_user_agents']))));

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info_ra_api()
    {
        print 'You must provide a Resource Access API access key and secret key.';
    }

    public function print_section_info_rm_api()
    {
        print 'You must provide a Resource Management API access key and secret key. Note that these two keys should be different from the Resource Access API access key and secret key.';
    }

    public function print_section_info_dynamic_resource_creation()
    {
        print 'Dynamic resource creation allows pages on your site to be added to iMoneza automatically when users first hit them. To use this feature, make sure the checkbox below is unchecked and that you\'ve checked "Dynamically Create Resources" on the <a href="https://manageui.imoneza.com/Property/Edit#tab_advanced">Property Settings</a> page.';
    }

    public function print_section_info_access_control()
    {
        print 'The access control method specifies how your site will communicate with iMoneza, determine if a user has access to the resource they\'re trying to access, and display the paywall if needed.' .
               '<ul>' .
               '<li><strong>None:</strong> No access control is enforced. Visitors to your site will not interact with iMoneza and will never see a paywall. Essentially, iMoneza won\'t be used on your site.</li>' .
               '<li><strong>Client-side:</strong> The JavaScript Library is run in your users\'s web browsers. You can set whether the paywall appears in a modal window or not on the <a href="https://manageui.imoneza.com/Property/Edit#tab_paywall">Property Settings</a> page.</li>' .
               '<li><strong>Server-side:</strong> User access is verified on your web server. This is more secure than the client-side approach. If you choose this option, you can also exclude certain user agents (like search engine bots) from being redirected to the paywall.</li>' .
               '</ul>' .
               '<p>If you specify user agents to exclude, you can specify one user agent per line.</p>';
    }

    public function print_section_info_help()
    {
        print 'The <a href="https://www.imoneza.com/wordpress-plugin/" target="_blank">iMoneza website</a> has additional information about these settings.';
        print '<script>' .
               'function imoneza_update_controls() {' .
               '  document.getElementById("access_control_excluded_user_agents").disabled = !document.getElementById("access_control_ss").checked; ' .
               '} ' .
               'imoneza_update_controls(); ' .
               '</script>';

    }

    public function ra_api_key_access_callback()
    {
        printf(
            '<input type="text" id="ra_api_key_access" name="imoneza_options[ra_api_key_access]" value="%s" />',
            isset($this->options['ra_api_key_access']) ? esc_attr( $this->options['ra_api_key_access']) : ''
        );
    }

    public function ra_api_key_secret_callback()
    {
        printf(
            '<input type="text" id="ra_api_key_secret" name="imoneza_options[ra_api_key_secret]" value="%s" />',
            isset($this->options['ra_api_key_secret']) ? esc_attr( $this->options['ra_api_key_secret']) : ''
        );
    }

    public function rm_api_key_access_callback()
    {
        printf(
            '<input type="text" id="rm_api_key_access" name="imoneza_options[rm_api_key_access]" value="%s" />',
            isset($this->options['rm_api_key_access']) ? esc_attr( $this->options['rm_api_key_access']) : ''
        );
    }

    public function rm_api_key_secret_callback()
    {
        printf(
            '<input type="text" id="rm_api_key_secret" name="imoneza_options[rm_api_key_secret]" value="%s" />',
            isset($this->options['rm_api_key_secret']) ? esc_attr( $this->options['rm_api_key_secret']) : ''
        );
    }

    public function no_dynamic_callback()
    {
        printf(
            '<input type="checkbox" id="no_dynamic" name="imoneza_options[no_dynamic]" value="1" %s/>',
            isset($this->options['no_dynamic']) && $this->options['no_dynamic'] == '1' ? ' checked' : ''
        );
    }

    public function access_control_callback()
    {
        printf(
            '<input type="radio" id="access_control_none" name="imoneza_options[access_control]" value="None" onclick="imoneza_update_controls();" %s/><label for="access_control_none">None</label><br />',
            isset($this->options['access_control']) && $this->options['access_control'] == 'None' ? ' checked' : ''
        );
        printf(
            '<input type="radio" id="access_control_js" name="imoneza_options[access_control]" value="JS" onclick="imoneza_update_controls();" %s/><label for="access_control_js">Client-side (JavaScript Library)</label><br />',
            isset($this->options['access_control']) && $this->options['access_control'] == 'JS' ? ' checked' : ''
        );
        printf(
            '<input type="radio" id="access_control_ss" name="imoneza_options[access_control]" value="SS" onclick="imoneza_update_controls();" %s/><label for="access_control_ss">Server-side</label><br />',
            isset($this->options['access_control']) && $this->options['access_control'] == 'SS' ? ' checked' : ''
        );
    }

    public function access_control_excluded_user_agents_callback()
    {
        printf(
            '<textarea id="access_control_excluded_user_agents" name="imoneza_options[access_control_excluded_user_agents]" rows="5" cols="80">%s</textarea>',
            isset($this->options['access_control_excluded_user_agents']) ? esc_attr($this->options['access_control_excluded_user_agents']) : ''
        );
    }
}
?>