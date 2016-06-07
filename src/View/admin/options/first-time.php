<div class="wrap">
    <h2 class="branded-header"><img src="<?= $this->assetUrl('images/logo-square.jpg') ?>" alt="logo"> <?= __("iMoneza Configuration", 'iMoneza') ?></h2>
    <section class="row">
        <div>
            <div class="i-card text-center">
                <p><?= __("Hey there!  It looks like it's your first time here.  Let's get started!", 'iMoneza') ?></p>
            </div>
            <div class="i-card">
                <h3><?= __("Time to do a little configuration", 'iMoneza') ?></h3>
                <p>
                    <?= __("Right now, all we need is your Resource Management API key and secret.  This will help
                    us custom tailor the rest of the plugin options for you.", 'iMoneza') ?>
                </p>
                <form method="post" id="imoneza-first-form" class="imoneza-form">
                    <?php wp_nonce_field('imoneza-options'); ?>
                    <input type="hidden" name="action" value="options_first_time" />
                    <div class="form-row">
                        <label for="api_key"><?= __("Resource Management API Key:", 'iMoneza') ?></label>
                        <input id="api_key" type="text" name="imoneza-options[management-api-key]" required class="large-text" />
                    </div>
                    <div class="form-row">
                        <label for="api_secret"><?= __("Resource Management API Secret:", 'iMoneza') ?></label>
                        <input id="api_secret" type="text" name="imoneza-options[management-api-secret]" required class="large-text" />
                    </div>
                    <?php submit_button(__('Verify Access', 'iMoneza')); ?>
                </form>
            </div>
        </div>
        <aside>
            <div class="i-card">
                <h2 class="logo-header"><img src="<?= $this->assetUrl('images/logo-rectangle.jpg') ?>" alt="logo"></h2>
                <h3><?= __("What is iMoneza?", 'iMoneza') ?></h3>
                <p><?= __("iMoneza is a digital micropayment paywall service. This plugin will add iMoneza's paywall to your site and allow you to manage your iMoneza resources from within WordPress.", 'iMoneza') ?></p>
                <p><strong><?= __("An iMoneza account is required.", 'iMoneza') ?></strong>  <?= sprintf(__("If you don't have one, it's simple and easy.  Just go to %s and sign up.", 'iMoneza'), '<a href="https://www.imoneza.com/sign-up">iMoneza.com</a>') ?></p>
            </div>
        </aside>
    </section>
</div>