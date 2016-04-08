<p id="message-automatically-manage"<?php if (!$this->dynamicallyCreateResources) echo " style='display: none'"; ?>>iMoneza will automatically manage this resource for you using your default pricing options.</p>
<p id="message-manually-manage"<?php if ($this->dynamicallyCreateResources) echo " style='display: none'"; ?>>iMoneza is not automatically managing your resources.</p>
<?php
$automaticallyManage = 'Override Default Pricing Options';
$manuallyManage = 'Manage this resource with iMoneza';
$display = $this->dynamicallyCreateResources ? $automaticallyManage : $manuallyManage;
?>
<p><label><input type="checkbox" value="1" id="show-override-pricing" name="override-pricing" <?php if ($this->overrideChecked) echo "checked='checked'"; ?>/><span data-automatically-manage="<?= $automaticallyManage ?>" data-manually-manage="<?= $manuallyManage ?>"><?= $display ?></span></label></p>
<div id="override-pricing"<?php if ($this->overrideChecked) echo " style='display:block'"; ?>>
    <label for="pricing-group-id">Pricing Group:</label>
    <select name="pricing-group-id" id="pricing-group-id">
        <?php
        /** @var \iMoneza\Data\PricingGroup $pricingGroup */
        foreach ($this->pricingGroups as $pricingGroup) {
            $selected = ($pricingGroup->getPricingGroupID() == $this->pricingGroupSelected ? ' selected="selected"' : '');
            printf('<option value="%s"%s>%s</option>', $pricingGroup->getPricingGroupID(), $selected, $pricingGroup->getName());
        }
        ?>
    </select>
</div>