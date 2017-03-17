		<table class='form_table' >
			<tr>
				<td class='title'>First Name:</td>
				<td>
					<div id='first_name_div' class='editable_field' style='width: 233px;'><?php echo $customer['first_name']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('first_name_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/first_name', {size: 15, <?php echo $options; ?>});
					</script>
				</td>
				<td class='title'>Last Name:</td>
				<td>
					<div id='last_name_div' class='editable_field' style='width: 233px;'><?php echo $customer['last_name']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('last_name_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/last_name', {size: 15, <?php echo $options; ?>});
					</script>
				</td>
			</tr>
			<tr>
				<td class='title'>Spouse First:</td>
				<td>
					<div id='spouse_first_div' class='editable_field' style='width: 233px;'><?php echo $customer['spouse_first']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('spouse_first_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/spouse_first', {size: 15, <?php echo $options; ?>});
					</script>
				</td>
				<td class='title'>Spouse Last:</td>
				<td>
					<div id='spouse_last_div' class='editable_field' style='width: 233px;'><?php echo $customer['spouse_last']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('spouse_last_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/spouse_last', {size: 15, <?php echo $options; ?>});
					</script>
				</td>
			</tr>		
			<tr>
				<td class="title">Home Phone:</td>
				<td>
					<div id='home_phone_div' class='editable_field' style='width: 233px;'><?php echo $customer['home_phone']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('home_phone_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/home_phone', {size: 15, <?php echo $options; ?>});
					</script>
				</td>
				<td class="title">Work Phone:</td>
				<td>
					<div id='work_phone_div' class='editable_field' style='width: 233px;'><?php echo $customer['work_phone']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('work_phone_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/work_phone', {size: 15, <?php echo $options; ?>});
					</script>
				</td>
			</tr>
			<tr>
				<td class="title">Email:</td>
				<td colspan='3'>
					<div id='email_div' class='editable_field' style='width: 440px;'><?php echo $customer['email']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('email_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/email', {size: 40, <?php echo $options; ?>});
					</script>
				</td>
			</tr>					
			<tr>
				<td class="title">Address:</td>
				<td colspan="3" nowrap>
					<div id='address_div' class='editable_field' style="width: 440px;"><?php echo $customer['address']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('address_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/address', {size: 40, <?php echo $options; ?>});
					</script>
					<input type="checkbox" name="mailing_list" <?php echo ($customer['mailing_list']?'checked':''); ?> onclick='javascript:getMailingListState(<?php echo $customer['customer_id'] ?>, this)' /> 
					Mailing List
					<div id='mailing_message' style="display:none;">
						<div id='mailing_content' class='warning'></div>
					</div>
				</td>
			</tr> 
			<tr>
				<td class="title">City:</td>
				<td>
					<div id='city_div' class='editable_field' style='width: 233px;'><?php echo $customer['city']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('city_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/city', {size: 15, <?php echo $options; ?>});
					</script>
				</td>
				<td class="title">State/Zip:</td>
				<td>
					<div id='state_div' class='editable_field' style='width: 233px;'><?php echo $customer['state']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('state_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/state', {size: 15, <?php echo $options; ?>});
					</script>
					<div id='zip_div' class='editable_field' style='width: 233px;'><?php echo $customer['zip']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('zip_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/zip', {size: 15, <?php echo $options; ?>});
					</script>				
				</td>						
			</tr>						
			<tr>
				<td class="title">Country:</td>
				<td colspan='3'>
					<div id='country_div' class='editable_field' style='width: 233px;'><?php echo $customer['country']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('country_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/country', {size: 15, <?php echo $options; ?>});
					</script>
				</td>
			</tr>
			<tr>
				<td class="title">Notes:</td>
				<td colspan="3">
					<div id='notes_div' class='editable_field'><?php echo $customer['notes']; ?></div>
					<script type="text/javascript">
						new Ajax.InPlaceEditor('notes_div', base_url + 'customer/AJAX_updateCustomerField/<?php echo $customer['customer_id']; ?>/notes', {cols: 50, rows: 6, <?php echo $options; ?>});
					</script>
				</td>
			</tr>			
		</table>