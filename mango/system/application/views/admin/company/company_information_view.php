<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Company Information</title>
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
		<h2>Company Information</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
		</ul>
		<p>
			You can modify the Company Information.
			This information will show up on Invoices and Memos. 
		</p>					
		<div>
		<?php echo form_open('admin/company_information'); ?>
			<div id='change_message' style='display: none'>You've made changes to this record. They won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
				<table class='form_table'>
					<tr>
						<td class="title" >Company Name:</td>
						<td>
							<input name="company_name" class='input_field' maxlength="64" type="text" size="30" value="<?php echo set_value('company_name', $company_data['company_name']); ?>" />
						</td>
					</tr>	
					<tr>
						<td class="title" >Phone Number:</td>
						<td><input name="phone_number" class='input_field' maxlength="64" type="text" size="30" value="<?php echo set_value('phone_number', $company_data['phone_number']); ?>"/></td>
					</tr>	
					<tr>
						<td class="title" >Fax Number:</td>
						<td>
							<input name="fax_number" class='input_field' maxlength="64" type="text" size="30" value="<?php echo set_value('fax_number', $company_data['fax_number']); ?>" />
						</td>
					</tr>	
					<tr>
						<td class="title" >Email:</td>
						<td>
							<input name="email" class='input_field' type="text" maxlength="256" size="50" value="<?php echo set_value('email', $company_data['email']); ?>"/>
						</td>
					</tr>	
					<tr>
						<td class="title" >Address:</td>
						<td>
							<input name="address" class='input_field' type="text" maxlength="256" size="50" value="<?php echo set_value('address', $company_data['address']); ?>"/>
						</td>
					</tr>
					<tr>
						<td class="title" >City:</td>
						<td>
							<input name="city" class='input_field' type="text" maxlength="64" size="50" value="<?php echo set_value('city', $company_data['city']); ?>"/>
						</td>
					</tr>
					<tr>
						<td class="title" >State:</td>
						<td>
							<input name="state" type="text" class='input_field' maxlength="2" size="5" value="<?php echo set_value('state', $company_data['state']); ?>"/>
							<strong>Zip:</strong>
							<input name="zip" type="text" class='input_field' maxlength="64" size="10" value="<?php echo set_value('zip', $company_data['zip']); ?>"/>
						</td>
					</tr>
					<tr>
						<td colspan="4"><?php echo validation_errors();  ?></td>
					</tr>
					<tr>
						<td></td>
						<td colspan="2">
							<input type="submit" value="Submit Changes" />
							|
							<?php echo anchor('admin', 'Cancel');?>
						</td>
					</tr>
				</table>
		<?php echo form_close(); ?>
		</div>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>