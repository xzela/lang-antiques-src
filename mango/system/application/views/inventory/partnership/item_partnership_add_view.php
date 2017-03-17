<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<title><?php echo $this->config->item('project_name'); ?> - Add Partnership to Item: <?php echo $item_data['item_name']; ?></title>
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('jquery.autocomplete.css'); //autoloaded ?>
	
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.autocomplete.js'); ?>

	<?php echo snappy_script('vendor/vendor_main.js'); ?>
	<?php echo snappy_script('customer/customer_main.js'); ?>
	<script type="text/javascript">	
	var item_id = '<?php echo $item_data['item_id']; ?>';
	var percent = <?php echo $company_ownership; ?>;
	var base_url = '<?php echo base_url(); ?>';
	 
	var acOption = {
			minChars: 2,
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
				str = item.contact;
				if(item.type == 1) { //1=customer, 2=vendor
					str += '<br /> ' + item.spouse;
				}
				else {
					if(item.contact_name.length > 2) {
						str += '<br /> ' + item.contact_name;
					}
				}
				if(item.phone.length > 2) {
					str += '<br /> ' + item.phone;
				}
				
				str += '<br /> ' + item.address + ' ' + item.city;
				return str;
			}
		};		
		$(document).ready(function() {
			
			var our = $('#our_ownership');
				our.html(percent);
			
			$("#vendor_input")
				.autocomplete(base_url+'vendor/AJAX_get_vendor_names/', acOption)
				.attr('name', 'contact')
				.after('<input type="hidden" name="user_id" id="ac_result">')
				.result(function(e, item) {
					//document.location = base_url+'vendor/edit/'+ item.vendor_id;
					//alert(item.vendor_id);
					$('#p_id').get(0).value = item.vendor_id;
					$('#p_name').html(item.contact);
					$('#p_phone').html(item.phone);
					$('#p_address').html(item.address + ', ' + item.city);
					if($('#p_id').val() != '') {
						$('#submit_div').show('slow');
					}
				});
			
			$('#their_percentage').bind("keyup", function(){
					var diff = 0;
					var per_err = $('#percent_error');
					per_err.html('');
					$('#form_submit').attr('disabled', true);
					if($(this).val().length > 0) {
						var p = parseFloat($(this).val());
						if(isNaN(p)) {
							per_err.html('That\'s not a number');
							per_err.show('slow');
							our.html(percent);
						}
						else {
							if(p > parseFloat(percent)) {
								per_err.html('Whoa Whoa Whoa, that percent is too much!');
								per_err.show('slow');		
							} 
							else {
								$('#form_submit').attr('disabled', false);
							}								
							diff = parseFloat(percent) - p;
							our.html(diff);
						}
					}
					else {
						our.html(percent);
						per_err.hide('slow');	
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
		<h2>Add Partnership for: <?php echo $item_data['item_number']; ?> - <?php echo $item_data['item_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/partnership/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Partnerships'); ?></li>
		</ul>
		<p>Currently, <strong><?php echo $company_data['company_name']; ?></strong> owns <strong><?php echo number_format($company_ownership,2); ?>%</strong> of this item.</p>
		<h2>First, Search for a Partner <span class='small_text'>[Searches the Vendor database]</span></h2>
		<table class='form_table'>
			<tr>
				<td class='title'>Search Name:</td>
				<td>
					<input id='vendor_input' name='vendor_input' type='text' style='width: 250px;' />
				</td>	
			</tr>
		</table>
		<h2>Second, Verify Partner Information</h2>
		<?php echo form_open('inventory/partnership_add/' . $item_data['item_id']); ?>
		<table class='form_table'>
			<tr>
				<td class='title'>Contact:</td>
				<td>
					<input id='p_id' name='partner_id' type='hidden' value='' />
					<input name='item_id' type='hidden' value='<?php echo $item_data['item_id']; ?>' />
					<span id='p_name'>N/A</span>
				</td>
			</tr>
			<tr>
				<td class='title'>Phone:</td>
				<td>
					<span id='p_phone'>N/A</span>
				</td>
			</tr>
			<tr>
				<td class='title'>Address:</td>
				<td>
					<span id='p_address'>N/A</span>				
				</td>
			</tr>
		</table>
		<div id='submit_div' style='display: none;'>
			<h2>Third, Enter Percentage of Ownership: </h2>
			<table class='form_table'>
				<tr>
					<td class='title'>Their Ownership: </td>
					<td>
						<input id='their_percentage' name='their_percentage' type='text' size='5' />%
						<div id='percent_error' class='warning' style='display: none;'></div>
					</td>
				</tr>
				<tr>
					<td class='title'>Our Ownsership: </td>
					<td>
						<span id='our_ownership' ></span>%
					</td>
				</tr>
				<tr>
					<td class='title'></td>
					<td ><input id='form_submit' type='submit' value='Add Partner' disabled /></td>
				</tr>
			</table>
			
		</div>
		<?php echo validation_errors(); ?>
		<?php echo form_close(); ?>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>