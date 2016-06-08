<?php
/** @var $options \iMoneza\WordPress\Model\Options */
$options = $this->options;
?>
<div class="wrap">
    <h2 class="branded-header"><img src="<?= $this->assetUrl('images/logo-square.jpg') ?>" alt="logo"> <?= __("iMoneza Access Configuration", 'iMoneza') ?></h2>

    <div class="i-card text-center">
        <h3 id="imoneza-property-title"><?= esc_html($options->getPropertyTitle()) ?></h3>
    </div>

    <?php if ($this->firstTimeSuccess) : ?>
        <div class="i-card" id="first-time-success-message">
            <p class="text-center success large">
                <span class="dashicons dashicons-thumbs-up"></span> <?= __("Way to go!  Now, let's finish this up!", 'iMoneza') ?>
            </p>
        </div>
    <?php endif; ?>

    <form method="post" id="imoneza-options-form" class="imoneza-form">
        <?php wp_nonce_field('imoneza-options'); ?>
        <input type="hidden" name="action" value="options_access" />

        <section class="row">
            <div>
                <div class="i-card">
                    <h3><span class="dashicons dashicons-shield"></span> <?= __("API Access Credentials", 'iMoneza') ?></h3>
                    <div class="form-row">
                        <label for="management_api_key"><?= __("Resource Management API Key:", 'iMoneza') ?></label>
                        <input id="management_api_key" type="text" name="imoneza-options[management-api-key]" value="<?= esc_attr($options->getManagementApiKey()) ?>" required class="large-text" />
                    </div>
                    <div class="form-row">
                        <label for="management_api_secret"><?= __("Resource Management API Secret:", 'iMoneza') ?></label>
                        <input id="management_api_secret" type="text" name="imoneza-options[management-api-secret]" value="<?= esc_attr($options->getManagementApiSecret()) ?>"required class="large-text" />
                    </div>
                    <hr>
                    <div class="form-row">
                        <label for="access_api_key"><?= __("Resource Access API Key:", 'iMoneza') ?></label>
                        <input id="access_api_key" type="text" name="imoneza-options[access-api-key]" value="<?= esc_attr($options->getAccessApiKey()) ?>" required class="large-text" />
                    </div>
                    <div class="form-row">
                        <label for="access_api_secret"><?= __("Resource Access API Secret:", 'iMoneza') ?></label>
                        <input id="access_api_secret" type="text" name="imoneza-options[access-api-secret]" value="<?= esc_attr($options->getAccessApiSecret()) ?>" required class="large-text" />
                    </div>
                </div>
            </div>
            <aside>
                <div class="i-card">
                    <h4><?= __("API Access", 'iMoneza') ?></h4>
                    <p>
                        <?= __("Your secure iMoneza data and configuration is hosted remotely.  To identify your website and account,
                        while keeping you fully secure, we need access to specific API Keys.  Protect these like you would protect
                        your username and password on any site.", 'iMoneza') ?>
                    </p>
                    <h5><?= __("Resource Management API", 'iMoneza') ?></h5>
                    <p><?= __("This API allows your website to modify your settings and account information with iMoneza.  It also allows us to identify you and provide you the proper level of customization.", 'iMoneza') ?></p>
                    <h5><?= __("Resource Access API", 'iMoneza') ?></h5>
                    <p><?= __("This API is used primarily to connect to your content and users.  This is the basis of your PayWall measurement and enforcement.", 'iMoneza') ?></p>
                </div>
            </aside>
        </section>
        <section class="row">
            <div>
                <div class="i-card">
                    <h3><span class="dashicons dashicons-admin-network"></span> <?= __("Access Control Method", 'iMoneza') ?></h3>
                    <div class="form-row">
                        <label class="toggle-label"><?= __("Select an Access Control:", 'iMoneza') ?></label>
                        <span class="toggle">
                            <input type="radio" id="access_control_client" name="imoneza-options[access-control]" value="C"<?php if ($options->isAccessControlClient()) echo " checked"; ?>>
                            <label for="access_control_client"><?= __("Client Side", 'iMoneza') ?></label>
                            <input type="radio" id="access_control_server" name="imoneza-options[access-control]" value="S"<?php if ($options->isAccessControlServer()) echo " checked"; ?>>
                            <label for="access_control_server"><?= __("Server Side", 'iMoneza') ?></label>
                        </span>
                    </div>
                </div>
            </div>
            <aside>
                <div class="i-card">
                    <h4><?= __("Which Method is For Me?", 'iMoneza') ?></h4>
                    <p>
                        <?= __("Client-side access is usually the best choice for most content.  Your content is protected quickly and easily.
                        For premium, ultra-high quality content, server-side access provides a slower, but more robust security model.", 'iMoneza') ?>
                    </p>
                </div>
            </aside>
        </section>
        <?php
        $dcrnStyle = sprintf('style="display:%s"', $options->isDynamicallyCreateResources() ? 'block' : 'none');
        ?>
        <section class="row" id="dynamically-create-resources-notification" <?= $dcrnStyle ?>>
            <div>
                <div class="i-card">
                    <h3><span class="dashicons dashicons-migrate"></span> <?= __("Dynamically Create Resources", 'iMoneza') ?></h3>
                    <?php
                    if ($this->postsQueuedForProcessing) {
                        echo '<p>';
                        echo __('Your resources are being added to iMoneza.', 'iMoneza');
                        echo ' ';
                        printf(_n('There is %s remaining.', 'There are %s remaining.', $this->postQueuedForProcessing, 'iMoneza'), $this->postQueuedForProcessing);
                        echo ' ';
                        echo $this->remainingTimeIndication;
                        echo '</p>';
                    }
                    else {
                        echo '<p>';
                        echo __('Congratulations!  All of your content is managed by iMoneza.', 'iMoneza');
                        echo '</p>';
                    }
                    ?>
                </div>
            </div>
        </section>
        <div class="i-card">
            <div class="form-row form-row-spacing-top clearfix">
                <span class="alignleft">
                <?php submit_button(__('Save Settings', 'iMoneza'), 'primary', 'submit', false); ?>
                </span>
                <span class="alignright">
                    <a href="#" class="button alignright" id="imoneza-refresh-settings"><?= __("Refresh Options from iMoneza.com", 'iMoneza') ?></a>
                </span>
            </div>
        </div>
    </form>
</div>