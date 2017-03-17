<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Edit Item Purchase Information </title>
	<?php echo snappy_style('calendar.css'); ?>
	<?php echo snappy_script('calendar_us.js'); ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<script type="text/javascript">
		var base_url = <?php echo '"' . base_url() . '"' ?>;

		var id = <?php echo $item_data['item_id']; ?>;

		var url = 'inventory/AJAX_updateItemField';


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
	<?php
		//Array used for category checkboxes...
		$yes_no_array = array(0 => "", 1 => "checked");
	?>
	<?php //echo snappy_script('inventory/jquery.inventory.js'); ?>

	<style type='css/text'>
	form.inplaceeditor-form{
		width: 250px;
	}

	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Inventory - Edit Item Purchase Information</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
		</ul>
		<div>
			<?php echo form_open('inventory/seller/' . $item_data['item_id']. '/edit', 'name="edit_seller_form"'); ?>
			<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
			<table class='form_table'>
				<tr>
					<td class='title'>Seller:</td>
					<td>
						<?php echo $seller_data['name']; ?>
						<?php $sesson_data = $this->session->userdata('seller'); ?>
						<?php if(isset($sesson_data['id']) && $sesson_data['id'] == $item_data['seller_id']): ?>
							<div>This is currently the default seller. <input type="submit" name="clear_seller" value="Remove As Default" /></div>
						<?php else: ?>
							<input type="submit" name="set_seller" value="Set as Default" />
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td class='title'>Phone:</td>
					<td><?php echo $seller_data['phone']; ?></td>
				</tr>
				<tr>
					<td class='title' style='vertical-align: top;'>Address:</td>
					<td>
						<?php echo $seller_data['address']; ?> <br />
						<?php echo $seller_data['city']; ?> <?php echo $seller_data['state']; ?> <?php echo $seller_data['zip']; ?> <br />
						<?php echo $seller_data['country']; ?>
					</td>
				</tr>
				<tr>
					<td class='title'>Purchase Date:</td>
					<td>
						<?php if($item_data['purchase_date'] != ''): ?>
							<input id='purchase_date' name="purchase_date" type="text" class='input_field' value="<?php echo date('m/d/Y', strtotime($item_data['purchase_date'])); ?>" />
						<?php else: ?>
							<input id='purchase_date' name="purchase_date" type="text" class='input_field' value="" />
						<?php endif; ?>
							<script type="text/javascript">
							A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
							new tcal ({
								// form name
								'formname': 'edit_seller_form',
								// input name
								'controlname': 'purchase_date',
								// callback
								'callback' : function(str) {
									$('#purchase_date').trigger('keyup');
								}
							});
							</script>
					</td>
				</tr>
				<tr>
					<td class='title'>Purchase Price:</td>
					<td>
						$<input type='text' name='purchase_price' class='input_field' value='<?php echo set_value('purchase_price', $item_data['purchase_price']); ?>' />
					</td>
				</tr>
				<tr>
					<td colspan='2'>
						<?php echo validation_errors(); ?>
					</td>
				</tr>
				<tr>
					<td colspan='2' style='text-align: center;'>
						<input type='submit' value='Save Changes'/>
					</td>
				</tr>
			</table>
			<?php echo form_close(); ?>
			<h3>Remove This Seller From Item</h3>
			<div class='delete_admin_item'>
				<p>If you remove the seller by mistake you'll have to re enter the seller information again.</p>
				<?php echo form_open('inventory/seller_remove', 'style="display: inline;"'); ?>
					<input type='hidden' name='item_id' value='<?php echo $item_data['item_id']; ?>' />
					<input type='submit' name='remove_seller_submit' value='Remove This Seller' />
				<?php echo form_close(); ?>
			</div>
		</div>
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>