<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('jquery.jeditable.css');?>

	<title>Project Mango - Edit Workshop: <?php echo $workshop['name']; ?> </title>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.jeditable.js'); ?>
	<?php echo snappy_script('jquery/jquery.conditional.js'); ?>

	<?php echo snappy_script('workshop/workshop_main.js'); ?>
	<script type="text/javascript">
		var base_url = <?php echo "'" . base_url() . "'"; ?>;
		var id = <?php echo $workshop['workshop_id']; ?>;
		var url = 'workshop/AJAX_updateWorkshopField';

		$(document).ready(function() {
			$('#mailing_list').bind('change', function() {
				$.post(base_url + url, {
					workshop_id : id,
					id : 'mailing_list',
					value: ($(this).attr('checked')) ? 1:0
					},
					null,
					"json");
			});

			$('.edit,.textarea_edit').bind('keydown', function(event) {
				object = this;
		        if(event.keyCode==9) {
					$(this).find("input").blur();
					$(this).find("textarea").blur();
					$(this).find("select").blur();
					var nextBox='';
					if ($(".edit,.textarea_edit,.select_edit,.edit_money,.edit_title").index(this) == ($(".edit,.textarea_edit,.select_edit,.edit_money,.edit_title").length-1)) { //at last box
						nextBox=$(".edit:first"); //last box, go to first
					}
					else {
						nextBox = $(".edit,.textarea_edit,.select_edit,.edit_money,.edit_title").get($(".edit,.textarea_edit,.select_edit,.edit_money,.edit_title").index(this)+1);
					}
					$(nextBox).click();
					return false;
				}
		    })
			.If(function() {
					return ($(this).attr('class') == 'textarea_edit') ? true : false;
			})
				.editable(base_url + url, {
					submitdata: {
						workshop_id: id
					},
					type: 'textarea',
					rows: '5',
					cols: '50',
					cssclass: 'inplace_field',
					onblur: 'submit'
				})
		    .Else() //default input text field
		    	.editable(base_url + url, {
			    	submitdata: {
		    			workshop_id: id
			    	},
			    	type: 'text',
		    		cssclass: 'inplace_field',
		    		onblur: 'submit'
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
		<h2 class='item'>Edit Workshop: <?php echo $workshop['name']; ?></h2>
		<ul id="submenu">
			<li><?php echo anchor('workshop', '<< Back to Workshop Main'); ?></li>
			<li>|</li>
			<li><?php echo anchor('workshop/inventory_jobs/' . $workshop['workshop_id'], 'View Inventory Jobs'); ?></li>
			<li>|</li>
			<li><?php echo anchor('workshop/customer_jobs/' . $workshop['workshop_id'], 'View Customer Jobs'); ?></li>
			<li>|</li>
		</ul>
		<table class='form_table' >
			<tr>
				<td class='title'>Workshop ID:</td>
				<td colspan='3'><?php echo $workshop['workshop_id']; ?></td>
			</tr>
			<tr>
				<td class='title'>Company Name:</td>
				<td colspan='3'>
					<div id='name' name='name' class='edit'><?php echo $workshop['name']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'>First Name:</td>
				<td>
					<div id='first_name' name='first_name' class='edit'><?php echo $workshop['first_name']; ?></div>
				</td>
				<td class='title'>Last Name:</td>
				<td>
					<div id='last_name' name='last_name' class='edit'><?php echo $workshop['last_name']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Phone Number:</td>
				<td>
					<div id='phone' name='phone' class='edit'><?php echo $workshop['phone']; ?></div>
				</td>
				<td class="title">Fax Number:</td>
				<td>
					<div id='fax' name='fax' class='edit'><?php echo $workshop['fax']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Cell Phone:</td>
				<td>
					<div id='cell_phone' name='cell_phone' class='edit'><?php echo $workshop['cell_phone']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Email:</td>
				<td colspan='3'>
					<div id='email' name='email' class='edit'><?php echo $workshop['email']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Address Line 1:</td>
				<td colspan="3" nowrap>
					<div id='address' name='address' class='edit'><?php echo $workshop['address']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Address Line 2:</td>
				<td colspan="3" nowrap>
					<div id='address2' name='address2' class='edit'><?php echo $workshop['address2']; ?></div>
					<input id='mailing_list' type="checkbox" name="mailing_list" <?php echo ($workshop['mailing_list']?'checked':''); ?> />
					Mailing List
					<div id='mailing_message' style="display:none;">
						<div id='mailing_content' class='warning'></div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="title">City:</td>
				<td colspan='3'>
					<div id='city' name='city' class='edit'><?php echo $workshop['city']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">State:</td>
				<td>
					<div id='state' name='state' class='edit'><?php echo $workshop['state']; ?></div>
				</td>
				<td class='title'>Zip:</td>
				<td>
					<div id='zip' name='zip' class='edit'><?php echo $workshop['zip']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Country:</td>
				<td colspan='3'>
					<div id='ountry' name='country' class='edit'><?php echo $workshop['country']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Notes:</td>
				<td colspan="3">
					<div id='notes' name='notes' class='textarea_edit'><?php echo $workshop['notes']; ?></div>
				</td>
			</tr>
		</table>
		<h3 class='section'>Shipping Address <span class='small_text'>[<?php echo anchor('workshop/edit_shipping/' . $workshop['workshop_id'], 'Edit Shipping'); ?>] [<?php echo anchor('workshop/copy_billing_address/' . $workshop['workshop_id'], 'Same As Billing Address')?>]</span></h3>
		<table class='form_table'>
			<tr>
				<td class="title">Address:</td>
				<td colspan="3" nowrap>
					<?php echo $workshop['ship_address']; ?> <br />
					<?php echo $workshop['ship_address2']; ?>
				</td>
			</tr>
			<tr>
				<td class="title">City:</td>
				<td colspan='3'>
					<?php echo $workshop['ship_city']; ?>
				</td>
			</tr>
			<tr>
				<td class="title">State:</td>
				<td>
					<?php echo $workshop['ship_state']; ?>
				</td>
				<td class='title'>Zip:</td>
				<td>
					<?php echo $workshop['ship_zip']; ?>
				</td>
			</tr>
			<tr>
				<td class="title">Country:</td>
				<td colspan='3'>
					<?php echo $workshop['ship_country']; ?>
				</td>
			</tr>
		</table>
		<p>Vendor Section of Project Mango</p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>