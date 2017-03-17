<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Invoices - Add Special Item to Invoice </title>
	<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
	var tax_rate = <?php echo TAX_RATE; //constanct in controller sales.php ?>;
	var json = {"fields" : {}};

	$(document).ready(function () {
		var content = $('.input_field').val();
		$('#calc_tax').bind('click', function() {
			var price = $('#order_price');
			var tax = $('#order_tax');
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
			Invoices - Add Special Item to Invoice #<?php echo $invoice_data['invoice_id']; ?> for:
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
		<table class='item_information'>
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
					<?php echo $sales_people[$invoice_data['user_id']]['first_name'] . ' ' . $sales_people[$invoice_data['user_id']]['last_name']  ; ?>
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
		<?php echo form_open('sales/add_invoice_special_item/' . $invoice_data['invoice_id']); ?>
		<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
		<table class='item_information'>
			<tr>
				<td class='title'>Type:</td>
				<td>
					<select class='input_field' name='order_type'>
						<?php foreach($special_item_type as $type):?>
							<option value='<?php echo $type['type_value']; ?>' ><?php echo $type['type_name']; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class='title'>Description:</td>
				<td>
					<textarea  class='input_field' name='description' cols='50' rows='3'><?php echo set_value('description');?></textarea>
				</td>
			</tr>
			<tr>
				<td class='title'>Order Price:</td>
				<td>
					$<input class='input_field' id='order_price' type='text' name='order_price' value='<?php echo set_value('order_price'); ?>'/> 
					[<a id='calc_tax' href='javascript:void(0)'>Calc Tax</a>]
				</td>
			</tr>
			<tr>
				<td class='title'>Order Tax:</td>
				<td>
					$<input class='input_field' id='order_tax' type='text' name='order_tax' value='<?php echo set_value('order_tax', '0.00'); ?>' /> 
				</td>
			</tr>
			<tr>
				<td colspan='2'><?php echo validation_errors(); ?></td>
			</tr>
			<tr>
				<td colspan='2' style='text-align: center;'>
					<input type='submit' value='Save Order' />
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