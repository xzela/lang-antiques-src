<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Edit Customer Shipping </title>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>	
	
	<script type="text/javascript">
	var json = {"fields" : {}};

	$(document).ready(function () {
		var content = $('.input_field').val();
		$.each($('.input_field'), function(i, val) {
			json.fields[i] = {"name" : this.name, "value" : this.value};
		}); 
		
		$('.input_field').bind('keyup change', function(event) {
			var index = $('.input_field').index(this); 
			var content = json.fields[index].value;
			
			//alert(content + '=' + $(this).val());
			var div = $('#change_message');
			if($(this).val() != content) {
				$(this).css('color', 'red');
				$(this).css('border', '1px solid red');
				if(div.is(':hidden')) {
					div.slideDown('slow');
				}
			}
			else {
				$(this).css('color', 'black');
				$(this).css('border', '1px solid #333333');
				var b = true;
				$.each($('.input_field'), function(i, val) {
					if(this.value != json.fields[i].value) {
						b = false;
					}
				});
				if(b) {
					div.slideUp('slow');
				}
			}	
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
		<h2 class='item'>Edit Customer: <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?></h2>
		<ul id="submenu">
			<li><?php echo anchor('customer/edit/' . $customer['customer_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Customer'); ?></li>
			<li>|</li>
		</ul>
		<h3>Shipping Address Address</h3>
		<?php echo form_open('customer/edit_shipping/' . $customer['customer_id']);?>
		<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
		<table class='form_table' >
			<tr>
				<td class='title' >Customer ID: </td>
				<td colspan='3'><div style='width: 400px'><?php echo $customer['customer_id']; ?></div></td>
			</tr>
			<tr>
				<td class='title'>Ship Contact:</td>
				<td colspan='3'>
					<input type='text' id='ship_contact' name='ship_contact' class='input_field' value='<?php echo set_value('ship_contact', $customer['ship_contact']); ?>' />
				</td>
			</tr>
			<tr>
				<td class="title">Ship Phone:</td>
				<td>
					<input type='text' id='ship_phone' name='ship_phone' class='input_field' value='<?php echo set_value('ship_phone', $customer['ship_phone']); ?>' />
				</td>
				<td class="title">Ship Other Phone:</td>
				<td>
					<input type='text' id='ship_other_phone' name='ship_other_phone' class='input_field' value='<?php echo set_value('ship_other_phone', $customer['ship_other_phone']); ?>' />
				</td>
			</tr>
			<tr>
				<td class="title">Ship Address Line 1:</td>
				<td colspan="3" >
					<input type='text' id='ship_address' name='ship_address' class='input_field' size='70' value='<?php echo set_value('ship_address', $customer['ship_address']); ?>' />
				</td>
			</tr> 
			<tr>
				<td class="title">Ship Address Line 2:</td>
				<td colspan="3" >
					<input type='text' id='ship_address2' name='ship_address2' class='input_field' size='70' value='<?php echo set_value('ship_address2', $customer['ship_address2']); ?>' />
				</td>
			</tr> 
			<tr>
				<td class="title">Ship City:</td>
				<td>
					<input type='text' id='ship_city' name='ship_city' class='input_field' value='<?php echo set_value('ship_city', $customer['ship_city']); ?>' />
				</td>
			</tr>
			<tr>
				<td class="title">Ship State</td>
				<td>
					<input type='text' id='ship_state' name='ship_state' class='input_field' size='2' value='<?php echo set_value('ship_state', $customer['ship_state']); ?>' />
				</td>
				<td class='title'>Ship Zip</td>
				<td>
					<input type='text' id='ship_zip' name='ship_zip' class='input_field' size='7' value='<?php echo set_value('ship_zip', $customer['ship_zip']); ?>' />
				</td>						
			</tr>						
			<tr>
				<td class="title">Ship Country:</td>
				<td colspan='3'>
					<input type='text' id='ship_country' name='ship_country' class='input_field' value='<?php echo set_value('ship_country', $customer['ship_country']); ?>' />
				</td>
			</tr>
			<tr>
				<td></td>
				<td colspan='3'><?php echo validation_errors(); ?></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type='submit' value='Update Shipping' />
				</td>
			</tr>
		</table>
		<?php echo form_close(); ?>
		<p>Customer Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>