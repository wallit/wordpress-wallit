<?php
$automaticallyManage = __('Override Default Pricing Options', 'iMoneza');
$manuallyManage = __('Manage this resource with iMoneza', 'iMoneza');
$displayForCheckbox = $this->dynamicallyCreateResources ? $automaticallyManage : $manuallyManage;
$autoStyle = !$this->dynamicallyCreateResources ? " style='display: none'" : '';
$manualStyle = $this->dynamicallyCreateResources ? " style='display: none'" : '';

echo "<p id='message-automatically-manage'{$autoStyle}>";
echo __('iMoneza will automatically manage this resource for you using your default pricing options.', 'iMoneza');
echo '</p>';
echo "<p id='message-manually-manage'{$manualStyle}>";
echo __('iMoneza is not automatically managing your resources.', 'iMoneza');
echo '</p>';

echo '<p><label>';
echo '<input type="checkbox" value="1" id="show-override-pricing" name="override-pricing"';
if ($this->overrideChecked) echo "checked='checked'"; 
echo "/><span data-automatically-manage='{$automaticallyManage}' data-manually-manage='{$manuallyManage}'>{$displayForCheckbox}</span>";
echo "</label></p>";

echo '<div id="override-pricing"';
if ($this->overrideChecked) echo " style='display:block'";
echo '><label for="pricing-group-id">Pricing Group:</label>';

echo '<select name="pricing-group-id" id="pricing-group-id">';
/** @var \iMoneza\Data\PricingGroup $pricingGroup */
foreach ($this->pricingGroups as $pricingGroup) {
    $selected = ($pricingGroup->getPricingGroupID() == $this->pricingGroupSelected ? ' selected="selected"' : '');
    printf('<option value="%s"%s>%s</option>', $pricingGroup->getPricingGroupID(), $selected, $pricingGroup->getName());
}
echo '</select>';

echo '</div>';