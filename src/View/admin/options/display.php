<?php
/** @var $options \iMoneza\WordPress\Model\Options */
$options = $this->options;

// this feeels really sloppy right now
echo '<style>.radio-container{color:' . $options->getPremiumIndicatorCustomColor() . '}.imoneza-custom-indicator{background-color:' . $options->getPremiumIndicatorCustomColor() . '}</style>';
?><div class="wrap">
    <h2 class="branded-header"><img src="<?= $this->assetUrl('images/logo-square.jpg') ?>" alt="logo"> <?= __("iMoneza Display Configuration", 'iMoneza') ?></h2>
    <form method="post" id="imoneza-options-form" class="imoneza-form">
        <?php wp_nonce_field('imoneza-options'); ?>
        <input type="hidden" name="action" value="options_display" />
        <section class="row">
            <div>
                <div class="i-card">
                    <h3><span class="dashicons dashicons-awards"></span> <?= __("Premium Content", 'iMoneza') ?></h3>
                    <div class="form-row">
                        <label class="toggle-label"><?= __("Indicate Premium Content:", 'iMoneza') ?></label>
                        <span class="toggle">
                            <input type="radio" id="indicate-premium-content-no" name="imoneza-options[indicate-premium-content]" value="0"<?php if (!$options->isIndicatePremiumContent()) echo " checked"; ?>>
                            <label for="indicate-premium-content-no" class="negative"><?= __("No", 'iMoneza') ?></label>
                            <input type="radio" id="indicate-premium-content-yes" name="imoneza-options[indicate-premium-content]" value="1"<?php if ($options->isIndicatePremiumContent()) echo " checked"; ?>>
                            <label for="indicate-premium-content-yes"><?= __("Yes", 'iMoneza') ?></label>
                        </span>
                    </div>
                    <?php
                    $ipcStyle = sprintf('style="display:%s"', $options->isIndicatePremiumContent() ? 'block' : 'none');
                    ?>
                    <div class="form-row collapsible-on-off" data-on-off-handler="input[name='imoneza-options[indicate-premium-content]']" id="choose-premium-indicator" <?= $ipcStyle ?>>
                        <div class="form-row">
                            <label class="label-for-radio-container"><?= __("Choose an icon:", 'iMoneza') ?></label>
                            <ul class="radio-container">
                                <?php
                                foreach ($this->indicatorClasses as $class) {
                                    echo "<li><label><input type='radio' name='imoneza-options[indicator-class]' value='{$class}'";
                                    if ($options->getPremiumIndicatorIconClass() == $class) echo " checked";
                                    echo "><span class='{$class}'></span></label></li>";
                                }
                                echo '<li class="full-width"><label><input type="radio" name="imoneza-options[indicator-class]" value="imoneza-custom-indicator"';
                                if ($options->getPremiumIndicatorIconClass() == 'imoneza-custom-indicator') echo " checked";
                                $customLabel = $options->getPremiumIndicatorCustomText();
                                echo "><span class='imoneza-custom-indicator'><span>{$customLabel}</span>";
                                echo "<input name='imoneza-options[indicator-text]' id='imoneza-custom-indicator-text' value='" . esc_attr($customLabel) . "' />";
                                echo "</span></label><a id='edit-custom-indicator' href='#'>" . __("edit", 'iMoneza') . "</a></li>";
                                ?>
                            </ul>
                        </div>
                        <div class="form-row color-picker-container">
                            <label><?= __("Customize Indicator Color:", 'iMoneza') ?></label>
                            <input name="imoneza-options[indicator-color]" type="text" class="color-picker" value="<?= esc_attr($options->getPremiumIndicatorCustomColor()) ?>">
                        </div>
                    </div>
                </div>
            </div>
            <aside>
                <div class="i-card">
                    <h4><?= __("Premium Content", 'iMoneza') ?></h4>
                    <p>
                        <?= __("Premium content shouldn't go unnoticed.  Use these settings to indicate which parts of your website truly are premium.", 'iMoneza') ?>
                    </p>
                    <h5><?= __("Indicator Icon and Tag", 'iMoneza') ?></h5>
                    <p>
                        <?= __("If you enable this setting, pick one of the premium indicators for your content.  These icons will be placed before the title
                        of the content wherever it appears.  To mark a piece of content as premium, add the following tag to it:", 'iMoneza') ?>
                        <br>
                        <strong>premium</strong>
                    </p>
                </div>
            </aside>
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