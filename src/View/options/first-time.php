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
                    Right now, all we need is your API access key.  This will help
                    us custom tailor the rest of the plugin options for you.
                </p>
                <form method="post" id="first-form">
                    <?php settings_fields( 'imoneza-pro-settings-group' ); ?>
                    <div class="form-row">
                        <label for="access_api_key">API Access Key:</label>
                        <input id="access_api_key" type="text" name="access_api_key" required />
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