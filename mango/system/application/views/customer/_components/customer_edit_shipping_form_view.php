
		<table class='form_table'>
			<tr>
				<td class="title">Address:</td>
				<td colspan="3" nowrap>
					<div id='ship_address_div' class='editable_field' style="width: 440px;"><?php echo $customer['ship_address']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('ship_address_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/ship_address', {size: 40, <?php echo $options; ?>});
					</script>
				</td>
			</tr> 
			<tr>
				<td class="title">City:</td>
				<td>
					<div id='ship_city_div' class='editable_field' style='width: 233px;'><?php echo $customer['ship_city']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('ship_city_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/ship_city', {size: 15, <?php echo $options; ?>});
					</script>
				</td>
				<td class="title">State/Zip:</td>
				<td>
					<div id='ship_state_div' class='editable_field' style='width: 233px;'><?php echo $customer['ship_state']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('ship_state_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/ship_state', {size: 15, <?php echo $options; ?>});
					</script>
					<div id='ship_zip_div' class='editable_field' style='width: 233px;'><?php echo $customer['ship_zip']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('ship_zip_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/ship_zip', {size: 15, <?php echo $options; ?>});
					</script>				
				</td>						
			</tr>						
			<tr>
				<td class="title">Country:</td>
				<td colspan='3'>
					<div id='ship_country_div' class='editable_field' style='width: 233px;'><?php echo $customer['ship_country']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('ship_country_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/ship_country', {size: 15, <?php echo $options; ?>});
					</script>
				</td>
			</tr>		
		</table>