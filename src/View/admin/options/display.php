<?php
/** @var $options \iMoneza\WordPress\Model\Options */
$options;
?><div class="wrap">
    <h2 class="branded-header"><img src="<?= $assetUrl('/images/logo-square.jpg') ?>" alt="logo"> iMoneza Display Configuration</h2>
    <form method="post" id="imoneza-options-form" class="imoneza-form">
        <?php wp_nonce_field('imoneza-options'); ?>
        <input type="hidden" name="action" value="options_display" />

        <section class="row">
            <div>
                <div class="i-card">
                    <h3><span class="dashicons dashicons-awards"></span> Premium Content</h3>
                    <div class="form-row">
                        <label class="toggle-label">Indicate Premium Content:</label>
                        <span class="toggle">
                            <input type="radio" id="indicate-premium-content-no" name="imoneza-options[indicate-premium-content]" value="0"<?php if (!$options->isIndicatePremiumContent()) echo " checked"; ?>>
                            <label for="indicate-premium-content-no" class="negative">No</label>
                            <input type="radio" id="indicate-premium-content-yes" name="imoneza-options[indicate-premium-content]" value="1"<?php if ($options->isIndicatePremiumContent()) echo " checked"; ?>>
                            <label for="indicate-premium-content-yes">Yes</label>
                        </span>
                    </div>
                    <?php
                    $ipcStyle = sprintf('style="display:%s"', $options->isIndicatePremiumContent() ? 'block' : 'none');
                    ?>
                    <div class="form-row" id="choose-premium-indicator" <?= $ipcStyle ?>>
                        <label class="label-for-radio-container">Choose an icon:</label>
                        <ul class="radio-container">
                            <?php
                            foreach ($indicatorClasses as $class) {
                                echo "<li><label><input type='radio' name='imoneza-options[indicator-class]' value='{$class}'";
                                if ($options->getPremiumIndicatorIconClass() == $class) echo " checked";
                                echo "><span class='{$class}'></label></li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <aside>
                <div class="i-card">
                    <h4>Premium Content</h4>
                    <p>
                        Premium content shouldn't go unnoticed.  Use these settings to indicate which parts of your website truly are premium.
                    </p>
                    <h5>Indicator Icon and Tag</h5>
                    <p>
                        If you enable this setting, pick one of the premium indicators for your content.  These icons will be placed before the title
                        of the content wherever it appears.  To mark a piece of content as premium, add the <strong>premium</strong> tag to it.
                    </p>
                </div>
            </aside>
        </section>
        <section class="row">
            <div>
                <div class="i-card">
                    <div class="form-row form-row-spacing-top clearfix">
                        <span class="alignleft">
                        <?php submit_button('Save Settings', 'primary', 'submit', false); ?>
                        </span>
                    </div>
                </div>
            </div>
        </section>
    </form>
</div>