<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>	
	
	
	<?php echo snappy_style('calendar.css'); ?>
	<?php echo snappy_script('calendar_us.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Customer Edit Special Order - <?php echo $special_order['order_id']?></title>
	<script type='text/javascript'>
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
		<h2>Customer Edit Special Order - <?php echo $special_order['order_id']; ?></h2>
		<ul id="submenu">
			<li><?php echo anchor('customer/edit/' . $customer['customer_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Customer'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/special_orders/' . $customer['customer_id'], 'List Customer Special Orders'); ?></li>
			<li>|</li>
		</ul>
		<?php $attributes = array('method' => 'post', 'name' => 'add_special_order_form');?>
		<?php echo form_open('customer/edit_special_order/' . $customer['customer_id'] . '/' . $special_order['order_id'], $attributes) ?>
		<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
		<table class='form_table'>
		
			<tr>
				<td class='title'><span class='warning'>*</span>Order ID:</td>
				<td><?php echo $special_order['order_id']; ?></td>
			</tr>
			<tr>
				<td class='title'><span class='warning'>*</span>Order Description:</td>
				<td>
					<textarea class='input_field' name="order_description" cols='50' rows='4'><?php echo set_value('order_description', $special_order['order_description']); ?></textarea>
				</td>
			</tr>
			<tr>
				<td class='title'><span class='warning'>*</span>Company Name:</td>
				<td>
					<input type='text'  class='input_field' name='company_name' value='<?php echo set_value('company_name', $special_order['company_name']); ?>' />
				</td>
			</tr>
			<tr>
				<td class="title"><span class='warning'>*</span>Order Date:</td>
				<td>
					<input name="order_date"  class='input_field' type="text" value="<?php echo set_value('order_date', date('m/d/Y', strtotime($special_order['order_date']))); ?>"/>
					<script type="text/javascript">
					A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
					new tcal ({
						// form name
						'formname': 'add_special_order_form',
						// input name
						'controlname': 'order_date'
					});
					</script>
				</td>
			</tr>
			<tr>
				<td class="title">Invoice ID:</td>
				<td colspan='3'><input name="invoice_id" class='input_field' type="text" value="<?php echo set_value('invoice_id', $special_order['invoice_id']); ?>" /> </td>
			</tr>
			<tr>
				<td class='title'>Status: </td>
				<td>
					<select name='order_status'>
						<?php foreach($order_status as $status): ?>
							<?php if($special_order['order_status'] == $status['name']): ?>
								<option value='<?php echo $status['status_id']; ?>' selected><?php echo $status['name']; ?></option>
							<?php else: ?>
								<option value='<?php echo $status['status_id']; ?>'><?php echo $status['name']; ?></option>
							<?php endif;?>
						<?php endforeach;?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='4' style="text-align: center;">
					<?php echo validation_errors();  ?>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="text-align: center;" >
					<input name="customer_sbmt" type="submit" value="Save" />  | <?php echo anchor('customer/edit/' . $customer['customer_id'], 'Cancel'); ?>
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