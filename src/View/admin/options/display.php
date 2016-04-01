<?php
/** @var $options \iMoneza\WordPress\Model\Options */
$options;

// this feeels really sloppy right now
if (!empty($isPro)) {
    echo '<style>.radio-container{color:' . $options->getPremiumIndicatorCustomColor() . '}.imoneza-custom-indicator{background-color:' . $options->getPremiumIndicatorCustomColor() . '}</style>';
}
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
                    <div class="form-row collapsible-on-off" data-on-off-handler="input[name='imoneza-options[indicate-premium-content]']" id="choose-premium-indicator" <?= $ipcStyle ?>>
                        <div class="form-row">
                            <label class="label-for-radio-container">Choose an icon:</label>
                            <ul class="radio-container">
                                <?php
                                foreach ($indicatorClasses as $class) {
                                    echo "<li><label><input type='radio' name='imoneza-options[indicator-class]' value='{$class}'";
                                    if ($options->getPremiumIndicatorIconClass() == $class) echo " checked";
                                    echo "><span class='{$class}'></span></label></li>";
                                }
                                if (!empty($isPro)) {
                                    echo '<li><label><input type="radio" name="imoneza-options[indicator-class]" value="imoneza-custom-indicator"';
                                    if ($options->getPremiumIndicatorIconClass() == 'imoneza-custom-indicator') echo " checked";
                                    $customLabel = $options->getPremiumIndicatorCustomText();
                                    echo "><span class='imoneza-custom-indicator'><span>{$customLabel}</span>";
                                    echo "<input name='imoneza-options[indicator-text]' id='imoneza-custom-indicator-text' value='" . esc_attr($customLabel) . "' />";
                                    echo "</span></label><a id='edit-custom-indicator' href='#'>edit</a></li>";
                                }
                                ?>
                            </ul>
                        </div>
                        <?php if (!empty($isPro)) : ?>
                        <div class="form-row color-picker-container">
                            <label>Customize Indicator Color:</label>
                            <input name="imoneza-options[indicator-color]" type="text" class="color-picker" value="<?= esc_attr($options->getPremiumIndicatorCustomColor()) ?>">
                        </div>
                        <?php endif; ?>
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
                    <h3><span class="dashicons dashicons-megaphone"></span> Notify Ad Blockers</h3>
                    <div class="form-row">
                        <label class="toggle-label">Add a notification for adblockers:</label>
                        <span class="toggle">
                            <input type="radio" id="notify-adblocker-no" name="imoneza-options[notify-adblocker]" value="0"<?php if (!$options->isNotifyAdblocker()) echo " checked"; ?>>
                            <label for="notify-adblocker-no" class="negative">No</label>
                            <input type="radio" id="notify-adblocker-yes" name="imoneza-options[notify-adblocker]" value="1"<?php if ($options->isNotifyAdblocker()) echo " checked"; ?>>
                            <label for="notify-adblocker-yes">Yes</label>
                        </span>
                    </div>
                    <?php
                    $naStyle = sprintf('style="display:%s"', $options->isNotifyAdblocker() ? 'block' : 'none');
                    ?>
                    <div class="form-row collapsible-on-off" data-on-off-handler="input[name='imoneza-options[notify-adblocker]']" id="specify-adblock-notification" <?= $naStyle ?>>
                        <label class="label-for-textarea">Message for users of ad blockers:</label>
                        <textarea name="imoneza-options[adblock-notification]"><?= esc_html($options->getAdblockNotification()) ?></textarea>
                    </div>
                </div>
            </div>
            <aside>
                <div class="i-card">
                    <h4>Adblock Notifier</h4>
                    <p>Enable this setting to alert adblock users that you use ads to support your website.</p>
                    <p>Remember, your visitors like your content.  We find it best to explain honestly your need and to be polite - otherwise you may have a lot less success with visitors disabling their adblock solution.</p>
                    <?php if (!empty($isPro)) : ?>
                    <p>
                        We will place the adblock notification at the very top of your site.  If you'd like it some where else, use this shortcode: <code>[imoneza_adblock_notification]</code>.  For theme-wide usage, see
                        <a href="https://developer.wordpress.org/reference/functions/do_shortcode/" target="_blank">do_shortcode()</a>.
                    </p>
                    <?php endif; ?>
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
            <?php if (empty($isPro)) : ?>
                <aside>
                    <div class="i-card">
                        <h4>Looking for More Features?</h4>
                        <p>
                            You're just scratching the surface with the features iMoneza provides.  Not only can you get more
                            customization options for these features, you can add micropayments for content and subscription
                            services to your content.  Ready to get started?  Visit us at <a target="_blank" href="http://imoneza.com">iMoneza.com</a>
                            to learn more.
                        </p>
                    </div>
                </aside>
            <?php endif; ?>
        </section>

    </form>
</div>