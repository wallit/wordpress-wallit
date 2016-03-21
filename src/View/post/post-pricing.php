<p id="message-automatically-manage"<?php if (!$dynamicallyCreateResources) echo " style='display: none'"; ?>>iMoneza will automatically manage this resource for you using your default pricing options.</p>
<p id="message-manually-manage"<?php if ($dynamicallyCreateResources) echo " style='display: none'"; ?>>iMoneza is not automatically managing your resources.</p>
<?php
$automaticallyManage = 'Override Default Pricing Options';
$manuallyManage = 'Manage this resource with iMoneza';
$display = $dynamicallyCreateResources ? $automaticallyManage : $manuallyManage;
?>
<p><label><input type="checkbox" value="1" id="show-override-pricing" name="override-pricing" /><span data-automatically-manage="<?= $automaticallyManage ?>" data-manually-manage="<?= $manuallyManage ?>"><?= $display ?></span></label></p>
<div id="override-pricing">
    <label for="pricing-group-id">Pricing Group:</label>
    <select name="pricing-group-id" id="pricing-group-id">
        <?php
        /** @var \iMoneza\Data\PricingGroup $pricingGroup */
        foreach ($pricingGroups as $pricingGroup) {
            $selected = ($pricingGroup == $pricingGroupSelected ? ' selected="selected"' : '');
            printf('<option value="%s"%s>%s</option>', $pricingGroup->getPricingGroupID(), $selected, $pricingGroup->getName());
        }
        ?>
    </select>
</div>