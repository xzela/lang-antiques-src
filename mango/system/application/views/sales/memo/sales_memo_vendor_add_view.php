<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<title><?php echo $this->config->item('project_name'); ?> - Invoices - Create Vendor Memo</title>
	
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('jquery.autocomplete.css'); //autoloaded ?>
		
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.autocomplete.js'); ?>

	<?php echo snappy_script('vendor/vendor_main.js'); ?>


	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>';
	/**
	* loads the search functions on startup
	*/
	var acOption = {
			minChars: 2,
			dataType: 'json',
			scrollHeight: 600,
			cacheLength: 20,
			extraParams: {	
				format: 'json'
			},
			parse: function(data) {
				var parsed = [];
				data = data.people;
				for(var i = 0; i < data.length ; i++) {
					parsed[parsed.length] = {
						data: data[i],
						value: data[i].contact,
						result: data[i].contact
					};
				}
				return parsed;
			},
			formatItem: function(item) {
				str = item.contact;
				str += '<br /> ' + item.contact_name;
				str += '<br /> ' + item.phone;
				str += '<br /> ' + item.address + ' ' + item.city;
				return str;
			}
		};		
		$(document).ready(function() {
			$("#vendor_input")
				.autocomplete(base_url+'vendor/AJAX_get_vendor_names/', acOption)
				.attr('name', 'contact')
				.after('<input type="hidden" name="user_id" id="ac_result">')
				.result(function(e, item) {
					document.location = base_url + 'sales/create_memo/vendor/' + item.vendor_id;
				});
		});
	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Invoices - Create Vendor Memo</h2>
		<ul id='submenu'>
			<li><?php echo anchor('sales', '<< Back to Sales'); ?></li>
		</ul>
		<div class='new_customer'>
			<h3>Search for a Vendor</h3>		
			Enter a Vendor name: <input id='vendor_input' name='vendor_input' type='text' style='width: 250px;' /> 
			<p>If the Vendor you are looking for doesn't exist, you can always add them.</p>
		</div>
		<div class='new_customer'>
		<h3>or... Add New Vendor</h3>

		<?php echo form_open('sales/create_memo/vendor') ?>
		<table class='form_table'>
			<tr>
				<td class='title'><span class='warning'>*</span>Company Name:</td>
				<td colspan='3'><input name="name" type="text" size='40' value="<?php echo set_value('name'); ?>" /></td>
			</tr>
			<tr>
				<td class='title'>Fed Tax ID:</td>
				<td colspan='3'><input name="tax_id" type="text" size='40' value="<?php echo set_value('tax_id'); ?>" /></td>
			</tr>
			<tr>
				<td class='title'><span class='warning'>*</span>First Name:</td>
				<td><input name="first_name" type="text" value="<?php echo set_value('first_name'); ?>" /></td>
				<td class='title'><span class='warning'>*</span>Last Name:</td>
				<td><input name="last_name" type="text" value="<?php echo set_value('last_name'); ?>" /></td>
			</tr>
			<tr>
				<td class="title">Phone:</td>
				<td><input name="phone" type="text" value="<?php echo set_value('phone'); ?>" /></td>
				<td class="title">Fax:</td>
				<td><input name="fax" type="text" value="<?php echo set_value('fax'); ?>" /></td>
			</tr>
			<tr>
				<td class="title">Alt Phone:</td>
				<td colspan='3'><input name="alt_phone" type="text" value="<?php echo set_value('alt_phone'); ?>" /></td>
			</tr>
			<tr>
				<td class="title">Email:</td>
				<td colspan='3'><input name="email" type="text" size='50' value="<?php echo set_value('email'); ?>" /></td>
			</tr>					
			<tr>
				<td class="title">Address:</td>
				<td colspan="3" nowrap>
					<input name="address" type="text" size="70" value="<?php echo set_value('address'); ?>"/> <br />
					<input name="address2" type="text" size="70" value="<?php echo set_value('address2'); ?>"/>
				</td>
			</tr>

			<tr>
				<td class="title">City:</td>
				<td>
					<input name="city" type="text" value="<?php echo set_value('city'); ?>"/>
				</td>
				<td class="title">State/Zip:</td>
				<td>
					<input name="state" type="text" size='3' value="<?php echo set_value('state'); ?>" maxlength='2'/>

					<input name="zip" type="text" size='7' value="<?php echo set_value('zip'); ?>" maxlength='11'/>
				</td>						
			</tr>						
			<tr>
				<td class="title">Country:</td>
				<td colspan='3'><input name="country" type="text" size='50' value="<?php echo set_value('county'); ?>" /> </td>
			</tr>
			<tr>
				<td class="title">Notes:</td>

				<td colspan="3">
					<textarea name="notes" cols="60" rows="4"><?php echo set_value('notes'); ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="text-align: right;;" class="warning">* Indicates required field.</td>
			</tr>
			<tr>
				<td colspan='4' style="text-align: center;">
					<?php echo validation_errors();  ?>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="text-align: center;" >
					<input name="vendor_sbmt" type="submit" value="Save Vendor and Create Invoice" />
				</td>
			</tr>
			
		</table>
		<?php echo form_close();?>
		</div>
		<p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>