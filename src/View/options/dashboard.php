<div class="wrap">
    <h2 class="branded-header"><img src="<?= $assetUrl('/images/logo-square.jpg') ?>" alt="logo"> iMoneza Configuration</h2>

    <div class="i-card text-center">
        <h3><?= esc_html($propertyTitle) ?></h3>
    </div>
    <?php if ($firstTimeSuccess) : ?>
        <div class="i-card">
            <p class="text-center success large">
                <span class="dashicons dashicons-thumbs-up"></span> Way to go!  Now, let's finish this up!
            </p>

        </div>
    <?php endif; ?>
    <form method="post" id="imoneza-options-form" class="imoneza-form">
        <?php wp_nonce_field('imoneza-settings'); ?>
        <input type="hidden" name="action" value="settings" />

        <section class="row">
            <div>
                <div class="i-card">
                    <h3><span class="dashicons dashicons-shield"></span> API Access Credentials</h3>
                    <div class="form-row">
                        <label for="management_api_key">Resource Management API Key:</label>
                        <input id="management_api_key" type="text" name="imoneza-management-api-key" value="<?= esc_attr($options['imoneza-management-api-key']) ?>" required />
                    </div>
                    <div class="form-row">
                        <label for="management_api_secret">Resource Management API Secret:</label>
                        <input id="management_api_secret" type="text" name="imoneza-management-api-secret" value="<?= esc_attr($options['imoneza-management-api-secret']) ?>"required />
                    </div>
                    <hr>
                    <div class="form-row">
                        <label for="access_api_key">Resource Access API Key:</label>
                        <input id="access_api_key" type="text" name="imoneza-access-api-key" required />
                    </div>
                    <div class="form-row">
                        <label for="access_api_secret">Resource Access API Secret:</label>
                        <input id="access_api_secret" type="text" name="imoneza-access-api-secret" required />
                    </div>
                </div>
            </div>
            <aside>
                <div class="i-card">
                    <h4>API Access</h4>
                    <p>
                        Your secure iMoneza data and configuration is hosted remotely.  To identify your website and account,
                        while keeping you fully secure, we need access to specific API Keys.  Protect these like you would protect
                        your username and password on any site.
                    </p>
                    <h5>Resource Management API</h5>
                    <p>This API allows your website to modify your settings and account information with iMoneza.  It also allows us to identify you and provide you the proper level of customization.</p>
                    <h5>Resource Access API</h5>
                    <p>This API is used primarily to connect to your content and users.  This is the basis of your paywall measurement and enforcement.</p>
                </div>
            </aside>
        </section>
        <div class="i-card">
            <?php submit_button('Save Settings'); ?>
        </div>
    </form>
</div>