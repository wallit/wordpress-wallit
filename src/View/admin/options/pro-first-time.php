<div class="wrap">
    <h2 class="branded-header"><img src="<?= $assetUrl('/images/logo-square.jpg') ?>" alt="logo"> iMoneza Configuration</h2>
    <section class="row">
        <div>
            <div class="i-card text-center">
                <p>Hey there!  It looks like it's your first time here.  Let's get started!</p>
            </div>
            <div class="i-card">
                <h3>Time to do a little configuration</h3>
                <p>
                    Right now, all we need is your Resource Management API key and secret.  This will help
                    us custom tailor the rest of the plugin options for you.
                </p>
                <form method="post" id="imoneza-first-form" class="imoneza-form">
                    <?php wp_nonce_field('imoneza-options'); ?>
                    <input type="hidden" name="action" value="options_pro_first_time" />
                    <div class="form-row">
                        <label for="api_key">Resource Management API Key:</label>
                        <input id="api_key" type="text" name="imoneza-options[management-api-key]" required class="large-text" />
                    </div>
                    <div class="form-row">
                        <label for="api_secret">Resource Management API Secret:</label>
                        <input id="api_secret" type="text" name="imoneza-options[management-api-secret]" required class="large-text" />
                    </div>
                    <?php submit_button('Verify Access'); ?>
                </form>
            </div>
        </div>
        <aside>
            <div class="i-card">
                <h2 class="logo-header"><img src="<?= $assetUrl('/images/logo-rectangle.jpg') ?>" alt="logo"></h2>
                <h3>What is iMoneza?</h3>
                <p>iMoneza is a digital micro-transaction paywall service. This plugin will add iMoneza's paywall to your site and allow you to manage your iMoneza resources from within WordPress.</p>
                <p><strong>An iMoneza account is required.</strong>  If you don't have one, it's simple and easy.  Just go to <a href="http://imoneza.com">iMoneza.com</a> and sign up.</p>
            </div>
        </aside>
    </section>
</div>