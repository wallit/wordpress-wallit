<?php
/** @var $options \iMoneza\WordPress\Model\Options */
$options = $this->options;
?><div class="wrap">
    <h2 class="branded-header"><img src="<?= $this->assetUrl('images/logo-square.jpg') ?>" alt="logo"> <?= __("iMoneza Internal Configuration", 'iMoneza') ?></h2>
    <form method="post" id="imoneza-options-form" class="imoneza-form">
        <?php wp_nonce_field('imoneza-options'); ?>
        <input type="hidden" name="action" value="options_internal" />
        <section class="row">
            <div>
                <div class="i-card">
                    <h3><span class="dashicons dashicons-admin-links"></span> <?= __("URLs", 'iMoneza') ?></h3>
                    <p>Here you can override the URLs built into the plugin.  Do not use this section unless you know what you're doing.  <strong>Leave them blank</strong> to use the default production URLs.</p>
                    <div class="form-row">
                        <label for="management_api_url"><?= __("Resource Management API URL:", 'iMoneza') ?></label>
                        <input id="management_api_url" type="text" name="imoneza-options[manage-api-url]" placeholder="<?= \iMoneza\WordPress\Model\Options::DEFAULT_MANAGE_API_URL ?>" value="<?= esc_attr($options->getManageApiUrl()) ?>" class="large-text" />
                    </div>
                    <div class="form-row">
                        <label for="access_api_url"><?= __("Resource Access API URL:", 'iMoneza') ?></label>
                        <input id="access_api_url" type="text" name="imoneza-options[access-api-url]" placeholder="<?= \iMoneza\WordPress\Model\Options::DEFAULT_ACCESS_API_URL ?>" value="<?= esc_attr($options->getAccessApiUrl()) ?>" class="large-text" />
                    </div>
                    <div class="form-row">
                        <label for="js_cdn_url"><?= __("Javascript CDN URL:", 'iMoneza') ?></label>
                        <input id="js_cdn_url" type="text" name="imoneza-options[javascript-cdn-url]" placeholder="<?= \iMoneza\WordPress\Model\Options::DEFAULT_JAVASCRIPT_CDN_URL ?>" value="<?= esc_attr($options->getJavascriptCdnUrl()) ?>" class="large-text" />
                    </div>
                    <div class="form-row">
                        <label for="manage_ui_url"><?= __("Manage UI URL:", 'iMoneza') ?></label>
                        <input id="manage_ui_url" type="text" name="imoneza-options[manage-ui-url]" placeholder="<?= \iMoneza\WordPress\Model\Options::DEFAULT_MANAGE_UI_URL ?>" value="<?= esc_attr($options->getJavascriptCdnUrl()) ?>" class="large-text" />
                    </div>
                </div>
            </div>
        </section>
        <section class="row">
            <div>
                <div class="i-card">
                    <div class="form-row form-row-spacing-top clearfix">
                        <span class="alignleft">
                        <?php submit_button(__('Save Settings', 'iMoneza'), 'primary', 'submit', false); ?>
                        </span>
                    </div>
                </div>
            </div>
        </section>
    </form>
</div>