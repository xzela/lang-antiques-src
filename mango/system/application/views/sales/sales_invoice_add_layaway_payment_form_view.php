<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('calendar.css'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Invoices - Add Layaway Payment to Invoice</title>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>	
	<?php echo snappy_script('calendar_us.js'); ?>
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
	<style type='text/css'>
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');	
?>
	<div id="content">
		<h2>
			Invoices - Add Layaway Payment to Invoice #<?php echo $invoice_data['invoice_id']; ?> for:
			<?php if($invoice_data['buyer_type'] == 1):?>
				Customer - <?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?>
			<?php elseif($invoice_data['buyer_type'] == 2):?>
				Vendor - <?php echo $buyer_data['name']; ?>
			<?php endif; ?>
		</h2>
		<ul id="submenu">
			<li><?php echo anchor('sales/invoice/' . $invoice_data['invoice_id'], '<< Back to Invoice'); ?></li>
			<li>|</li>
		</ul>
		
		<table class='item_information'>
			<tr>
				<td class='title'>Invoice ID:</td>
				<td><?php echo $invoice_data['invoice_id']; ?></td>
				<td class='title'>Sales Slip Number:</td>
				<td><?php echo $invoice_data['sales_slip_number']; ?></td>
			</tr>
			<tr>
				<td class='title'>Invoice Date:</td>
				<td><?php echo $invoice_data['sale_date'] == '0000-00-00' ? '' : date('m/d/Y', strtotime($invoice_data['sale_date'])); ?></td>
				<td class='title'>Sales Person:</td>
				<td><?php echo $sales_people[$invoice_data['user_id']]['first_name'] . ' ' . $sales_people[$invoice_data['user_id']]['last_name']  ; ?></td>
			</tr>
			<tr>
				<td class='title'>Invoice Type:</td>
				<td><?php echo $invoice_type_text[$invoice_data['invoice_type']]; ?></td>
				<?php if($invoice_data['buyer_type'] == 1 || $invoice_data['buyer_type'] == 3):?>
					<td class='title'>Customer Name:</td>
					<td><?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?></td>
				<?php elseif($invoice_data['buyer_type'] == 2):?>
					<td class='title'>Vendor Name:</td>
					<td><?php echo $buyer_data['name']; ?></td>
				<?php endif; ?>				
			</tr>
		</table>
			<?php echo form_open('sales/add_layaway_payment/' . $invoice_data['invoice_id'], 'name="layaway_payment_form"'); ?>
				<div id='change_message' style='display: none'>You've made changes to this record. They won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>	
				<table class='form_table'>
					<tr>
						<td class='title'>Payment Method:</td>
						<td>
							<select id='payment_method' name='payment_method'>
								<?php foreach($payment_methods as $method):?>
									<?php if(set_value('payment_method') == $method['id']): ?>
										<option value='<?php echo $method['id']; ?>' selected><?php echo $method['name']; ?></option>
									<?php else: ?>
										<option value='<?php echo $method['id']; ?>'><?php echo $method['name']; ?></option>
									<?php endif;?>
								<?php endforeach;?>
							</select>								
						</td>
					</tr>
					<tr>
						<td class='title'>Payment Amount</td>
						<td>$<input id='payment_amount' name='payment_amount' type='text' value='<?php echo set_value('payment_amount'); ?>'/></td>
					</tr>
					<tr>
						<td class="title">Payment Date:</td>
						<td>
							<input type="text" id='payment_date' name="payment_date" value='<?php echo set_value('payment_date', date('m/d/Y')); ?>' />
							<script language="JavaScript">
								A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
								new tcal ({
									// form name
									'formname': 'layaway_payment_form',
									// input name
									'controlname': 'payment_date'
								});
							</script>								
						</td>
					</tr>
					<tr>
						<td class='title'></td>
						<td>
							<?php echo validation_errors(); ?>
						</td>
					</tr>
					<tr>
						<td class='title'></td>
						<td>
							<input type='hidden' name='invoice_id' value='<?php echo $invoice_data['invoice_id']; ?>' />
							<input name='layaway_payment_submit' type='submit' value='Add Payment' />
							|
							<?php echo anchor('sales/invoice/' . $invoice_data['invoice_id'], 'Cancel'); ?>						
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