<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<title><?php echo $this->config->item('project_name'); ?> - Add Seller to Inventory Item</title>

	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('jquery.autocomplete.css'); //autoloaded ?>

	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.autocomplete.js'); ?>

	<?php echo snappy_script('vendor/vendor_main.js'); ?>
	<?php echo snappy_script('customer/customer_main.js'); ?>


	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>';

	item_id = '<?php echo $item_data['item_id']; ?>';

	/**
	* loads the search functions on startup
	*/
	var acOption = {
			minChars: 1, //number of chars in text box, 1 (+1);
			dataType: 'json',
			scrollHeight: 600,
			cacheLength: 0,
			max: 50,
			extraParams: {
				format: 'json'
			},
			parse: function(data) {
				var parsed = [];
				if(typeof(data.people) != 'undefined') { //if undefined, return empty array
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
					str += '<br /> ' + item.contact_name;
				}
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
					document.location = base_url+'inventory/AJAX_apply_seller/' + item_id + '/2/' + item.customer_id;
				});
			$("#vendor_input")
				.autocomplete(base_url+'vendor/AJAX_get_vendor_names/', acOption)
				.attr('name', 'contact')
				.after('<input type="hidden" name="user_id" id="ac_result">')
				.result(function(e, item) {
					document.location = base_url+'inventory/AJAX_apply_seller/' + item_id + '/1/' + item.vendor_id;
				});
			$('#add_vendor_link')
				.click(function() {
					if($('#vendor_form').is(":hidden")) {
						$('#vendor_form').slideDown(1500);
						$('#add_vendor_link').html('Close Form');
					}
					else {
						$('#vendor_form').slideUp(1500);
						$('#add_vendor_link').html('Open Vendor Form');
					}
				});
			$('#add_customer_link')
				.click(function () {
					if($('#customer_form').is(":hidden")) {
						$('#customer_form').slideDown(1500);
						$('#add_customer_link').html('Close Form');
					}
					else {
						$('#customer_form').slideUp(1500);
						$('#add_customer_link').html('Open CustomerForm');
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
		<h2>Inventory - Add Seller to Inventory Item</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
		</ul>
		<div>
			<h3>This Item was a Vendor Buy</h3>
			<div>
				Search Name:
				<input id='vendor_input' name='vendor_input' type='text' style='width: 250px;' />
				<a id='add_vendor_link' href='javascript:void(0)' >Open Vendor Form</a>
				<div id='vendor_form' style='display: none;' class='new_customer'>
				<?php echo form_open('inventory/add_vendor_seller/' . $item_data['item_id']); ?>
					<table class='form_table'>
						<tr>
							<td class='title'><span class='warning'>*</span>Company Name:</td>
							<td colspan='3'><input name="name" type="text" size='40' /></td>
						</tr>
						<tr>
							<td class='title'>Fed Tax ID:</td>
							<td colspan='3'><input name="tax_id" type="text" size='40' /></td>
						</tr>
						<tr>
							<td class='title'><span class='warning'>*</span>First Name:</td>
							<td><input name="first_name" type="text"  /></td>
							<td class='title'><span class='warning'>*</span>Last Name:</td>
							<td><input name="last_name" type="text"  /></td>
						</tr>
						<tr>
							<td class="title">Phone:</td>
							<td><input name="phone" type="text"   /></td>
							<td class="title">Fax:</td>
							<td><input name="fax" type="text"  /></td>
						</tr>
						<tr>
							<td class="title">Alt Phone:</td>
							<td colspan='3'><input name="alt_phone" type="text"  /></td>
						</tr>
						<tr>
							<td class="title">Email:</td>
							<td colspan='3'><input name="email" type="text" size='50'  /></td>
						</tr>
						<tr>
							<td class="title">Address:</td>
							<td colspan="3" nowrap>
								<input name="address" type="text" size="70" />
								<br />
								<input name="address2" type="text" size="70" />
							</td>
						</tr>

						<tr>
							<td class="title">City:</td>
							<td>
								<input name="city" type="text"  />
							</td>
							<td class="title">State:</td>
							<td>
								<input name="state" type="text" size='3'   maxlength='2'/>
								<strong>Zip:</strong>
								<input name="zip" type="text" size='7'   maxlength='11'/>
							</td>
						</tr>
						<tr>
							<td class="title">Country:</td>
							<td colspan='3'><input name="country" type="text" size='50' /> </td>
						</tr>
						<tr>
							<td class="title">Notes:</td>
							<td colspan="3">
								<textarea name="notes" cols="60" rows="4"></textarea>
							</td>
						</tr>
						<tr>
							<td colspan='4' style="text-align: center;">
								<div id='vendor_form_results'></div>
							</td>
						</tr>
						<tr>
							<td colspan="4" style="text-align: right;;" class="warning">* Indicates required field.</td>
						</tr>
						<tr>
							<td colspan="4" style="text-align: center;" >
								<input name="vendor_sbmt" type="submit" value="Apply This Vendor" />
							</td>
						</tr>
					</table>
					<?php echo form_close(); ?>
				</div>
			</div>
			<h3>This Item was a Private Buy</h3>
			<div>
				<script type="text/javascript">

				</script>
				Search Name:
				<input id='customer_input' name='customer_input' type='text' style='width: 250px;' />
				<a id='add_customer_link' href='javascript:void(0)' >Open Customer Form</a>
				<div id='customer_form' style='display: none;' class='new_customer'>
					<?php echo form_open('inventory/add_customer_seller/' . $item_data['item_id']); ?>
						<table class='form_table'>
							<tr>
								<td class='title'><span class='warning'>*</span>First Name:</td>
								<td><input name="first_name" type="text" value="" /></td>
								<td class='title'><span class='warning'>*</span>Last Name:</td>
								<td><input name="last_name" type="text" value="" /></td>
							</tr>
							<tr>
								<td class='title'></td>
								<td></td>
								<td class='title'>Middle:</td>
								<td><input name="middle_name" type="text" value="" /></td>
							</tr>
							<tr>
								<td class='title'>Spouse First:</td>
								<td><input name="spouse_first" type="text" value="" /></td>
								<td class='title'>Spouse Last:</td>
								<td><input name="spouse_last" type="text" value="" /></td>
							</tr>
							<tr>
								<td class='title'></td>
								<td></td>
								<td class='title'>Spouse Middle:</td>
								<td><input name="spouse_middle" type="text" value="" /></td>
							</tr>
							<tr title="Phone numbers should look like this: 415-555-0123">
								<td class="title">Home Phone:</td>
								<td><input name="home_phone" type="text" value="" /></td>
								<td class="title">Work Phone:</td>
								<td><input name="work_phone" type="text" value="" /></td>
							</tr>
							<tr>
								<td class="title">Email:</td>
								<td colspan='3'><input name="email" type="text" size='50' value="" /></td>
							</tr>
							<tr>
								<td class="title">Address:</td>
								<td colspan="3" nowrap>
									<input name="address" type="text" size="70" value=""/>
									<br />
									<input name="address2" type="text" size="70" value=""/>
									<?php echo form_checkbox('mailing_list', 'on', true); ?> Mailing List
								</td>
							</tr>

							<tr>
								<td class="title">City:</td>
								<td>
									<input name="city" type="text" value=""/>
								</td>
								<td class="title">State:</td>
								<td>
									<input name="state" type="text" size='3' value="" maxlength='2'/>
									<strong>Zip:</strong>
									<input name="zip" type="text" size='7' value="" maxlength='11'/>
								</td>
							</tr>
							<tr>
								<td class="title">Country:</td>
								<td colspan='3'><input name="country" type="text" size='50' value="" /> </td>
							</tr>
							<tr>
								<td class="title">Notes:</td>

								<td colspan="3">
									<textarea name="notes" cols="60" rows="4"></textarea>
								</td>
							</tr>
							<tr>
								<td colspan="4" style="text-align: right;;" class="warning">* Indicates required field.</td>
							</tr>
							<tr>
								<td colspan='4' style="text-align: center;">
									<div id='customer_form_results'></div>
								</td>
							</tr>
							<tr>
								<td colspan="4" style="text-align: center;" >
									<input name="customer_sbmt" type="submit" value="Apply This Customer" />
								</td>
							</tr>
						</table>
					<?php form_close(); ?>
				</div>
			</div>

		</div>
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>