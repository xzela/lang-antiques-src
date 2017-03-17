<?php echo $this->config->item('tax_rate_location'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>	

	
	<title><?php echo $this->config->item('project_name'); ?> - Invoices - Edit Inventory Item on Invoice #<?php echo $invoice_data['invoice_id']; ?></title>
	<script type="text/javascript">

	var tax_rate = <?php echo TAX_RATE; //constant in controller sales.php ?>;
	var json = {"fields" : {}};

	$(document).ready(function () {
		var content = $('.input_field').val();

		$('#calc_tax').bind('click', function() {
			var price = $('#sale_price');
			var tax = $('#sale_tax');
			if(!isNaN(price.val())) {
				tax.val(Math.round((price.val() * tax_rate)*100)/100);
				tax.trigger('change');
			}
			else {
				alert('Dude, "' + price.val() + '" that is not a number');
			}
		});

		
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
	<style type='text/css'>
	table.invoice_info_table td.title {
		font-weight: bold;
		text-align: right;
		vertical-align: top;
	}
	
	table.invoice_info_table {
		border: 1px solid #9c9c9c;
	}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>
			Invoices - Edit Inventory Item on Invoice #<?php echo $invoice_data['invoice_id']; ?> for:
			<?php if($invoice_data['buyer_type'] == 1):?>
				<?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?>
			<?php elseif($invoice_data['buyer_type'] == 2):?>
				<?php echo $buyer_data['name']; ?>
			<?php endif; ?>			 
		</h2>
		<ul id="submenu">
			<li><?php echo anchor('sales/invoice/' . $invoice_data['invoice_id'], '<< Back to Invoice'); ?></li>
			<li>|</li>
		</ul>		
		<h3>Invoice Information:</h3>
		<table class='form_table'>
			<tr>
				<td class='title'>Invoice ID:</td>
				<td><?php echo $invoice_data['invoice_id']; ?></td>
				<td class='title'>Sales Slip Number:</td>
				<td>
					<?php echo $invoice_data['sales_slip_number']; ?>
				</td>
			</tr>
			<tr>
				<td class='title'>Invoice Date:</td>
				<td>
					<?php echo $invoice_data['sale_date']; ?>
				</td>
				<td class='title'>Invoice Type:</td>
				<td>
					<?php echo $invoice_type_text[$invoice_data['invoice_type']]; ?>
				</td>
			</tr>
			<tr>
				<td class='title'>Sales Person:</td>
				<td>
					<?php echo $sales_people_display[$invoice_data['user_id']]['first_name'] . ' ' . $sales_people_display[$invoice_data['user_id']]['last_name']  ; ?>
				</td>
				<?php if($invoice_data['buyer_type'] == 1):?>
					<td class='title'>Customer Name:</td>
					<td><?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?></td>
				<?php elseif($invoice_data['buyer_type'] == 2):?>
					<td class='title'>Vendor Name:</td>
					<td><?php echo $buyer_data['name']; ?></td>
				<?php endif; ?>
			</tr>
		</table>
		<?php echo form_open('sales/edit_invoice_item/' . $invoice_data['invoice_id'] . '/' . $item_data['item_id']); ?>
		<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
		<table class='form_table'>
			<tr>
				<td class='title'>Item Title:</td>
				<td>
					<?php echo $item_data['item_name']; ?> <br />
					<?php if(sizeof($item_data['image_array']['external_images']) > 0): ?>
						<?php
							echo anchor('inventory/edit/' . $item_data['item_id'] , "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item_data['image_array']['external_images'][0]['image_location'] . '&image_type=' . $item_data['image_array']['external_images'][0]['image_class'] . '&image_size=' . $item_data['image_array']['external_images'][0]['image_size'] . "' />");
						?>
					<?php elseif(sizeof($item_data['image_array']['internal_images']) > 0):?>
						<?php 
						echo anchor('inventory/edit/' . $item_data['item_id'], "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item_data['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $item_data['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $item_data['image_array']['internal_images'][0]['image_size'] . "' />");
						?>
					<?php else: ?>
						No Image Provided						
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td class='title'>Item Price:</td>
				<td>
					$<input id='sale_price' type='text' name='sale_price' class='input_field' value='<?php echo set_value('sale_price', $invoice_item_data['sale_price']); ?>'/> 
					[<a id='calc_tax' href='javascript:void(0)'>Calc Tax</a>]
				</td>
			</tr>
			<tr>
				<td class='title'>Order Tax:</td>
				<td>
					$<input id='sale_tax' type='text' name='sale_tax' class='input_field' value='<?php echo set_value('sale_tax', $invoice_item_data['sale_tax']); ?>' /> 
				</td>
			</tr>
			<tr>
				<td colspan='2'><?php echo validation_errors(); ?></td>
			</tr>
			<tr>
				<td colspan='2' style='text-align: center;'>
					<input type='submit' value='Update Item' />
					| 
					[<?php echo anchor('sales/invoice/' . $invoice_data['invoice_id'], 'Cancel'); ?>]
				</td>
			</tr>
		</table>
		<?php echo form_close(); ?>
		<p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>