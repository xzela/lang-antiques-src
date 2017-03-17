<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 	
	<title><?php echo $this->config->item('project_name'); ?> - Invoices - Create Customer Invoice</title>
	
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('jquery.autocomplete.css'); //autoloaded ?>
		
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.autocomplete.js'); ?>

	<?php echo snappy_script('customer/customer_main.js'); ?>


	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>';

	var acOption = {
			minChars: 1, //number of chars in text box, 1;
			dataType: 'json',
			scrollHeight: 600,
			cacheLength: 0,
			max: 50,
			extraParams: {	
				format: 'json'
			},
			parse: function(data) {
				var parsed = [];
				if(typeof(data.people) != 'undefined') {
					data = data.people;	
					for(var i = 0; i < data.length ; i++) {
						parsed[parsed.length] = {
							data: data[i],
							value: data[i].contact,
							result: data[i].contact
						};
					}
				}
				return parsed;
			},
			formatItem: function(item) {
				str = item.contact + '[' + item.customer_id + ']';
				str += '<br /> ' + item.spouse;
				str += '<br /> ' + item.phone;
				str += '<br /> ' + item.address + ' ' + item.city;
				
				return str;
			}
		};
		
			
		$(document).ready(function() {
			$("#customer_input")
				.autocomplete(base_url+'customer/AJAX_get_customer_names/', acOption)
				.attr('name', 'contact')
				.after('<input type="hidden" name="user_id" id="ac_result">')
				.result(function(e, item) {
					document.location = base_url + 'sales/create_invoice/customer/add/' + item.customer_id;
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
		<h2>Invoices - Create Customer Invoice</h2>
		<ul id='submenu'>
			<li><?php echo anchor('sales', '<< Back to Sales'); ?></li>
		</ul>
		<div class='new_customer' >
			<h3>Search for a Customer</h3>		
			Enter a Customer name: <input id='customer_input' name='customer_input' type='text' style='width: 250px;' /> 
			<p>
				If the customer you are looking for doesn't exist, you can always add them.
			</p>
		</div>
		<div class='new_customer'>
			<h3>or... Add New Customer</h3>
	
			<?php echo form_open('sales/create_invoice_customer_add') ?>
			<table class='form_table'>
				<tr>
					<td class='title'><span class='warning'>*</span>First Name:</td>
					<td><input name="first_name" type="text" value="<?php echo set_value('first_name'); ?>" /></td>
					<td class='title'><span class='warning'>*</span>Last Name:</td>
					<td><input name="last_name" type="text" value="<?php echo set_value('last_name'); ?>" /></td>
				</tr>
				<tr>
					<td class='title'>Spouse First:</td>
					<td><input name="spouse_first" type="text" value="<?php echo set_value('spouse_first'); ?>" /></td>
					<td class='title'>Spouse Last:</td>
					<td><input name="spouse_last" type="text" value="<?php echo set_value('spouse_last'); ?>" /></td>
				</tr>			
				<tr title="Phone numbers should look like this: 415-555-0123">
					<td class="title">Home Phone:</td>
					<td><input name="home_phone" type="text" value="<?php echo set_value('home_phone'); ?>" /></td>
					<td class="title">Work Phone:</td>
					<td><input name="work_phone" type="text" value="<?php echo set_value('work_phone'); ?>" /></td>
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
						<?php echo form_checkbox('mailing_list', 'foobar', true); ?> Mailing List							
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
						<input name="customer_sbmt" type="submit" value="Save Customer and Create Invoice" /> 
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