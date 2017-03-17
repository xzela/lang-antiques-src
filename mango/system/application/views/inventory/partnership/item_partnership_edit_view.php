<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Inventory Partnerships For Item: <?php echo $item_data['item_name']; ?></title>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	
		<script type='text/javascript'>
		var base_url = <?php echo '"' . base_url() . '"'; ?>;
		var json = {"fields" : {}};
		var our_percent = <?php echo $company_ownership; ?>;
		var their_percent = <?php echo $partnership_data['percentage']; ?>;
		$(document).ready(function() {
			var our = $('#our_ownership');
			our.html(our_percent);

			
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

			$('#their_percentage').bind("keyup", function() {
				var our_percent = <?php echo $company_ownership; ?>;
				var per_err = $('#percent_error');
				per_err.html('');
				$('#form_submit').attr('disabled', true);
				if($(this).val().length > 0) {
					var p = parseFloat($(this).val());
					if(isNaN(p)) {
						per_err.html('That\'s not a number');
						per_err.show('slow');
						our.html(our_percent);
					}
					else {
						new_val = $(this).val() - their_percent;
						if(new_val > 0) { //they have lowered their share
							our_percent = our_percent + -new_val;
						}
						else {
							our_percent = our_percent - new_val;
						}
						if(parseFloat(our_percent) < 0) {
							per_err.html('Whoa Whoa Whoa, that percent is too much!');
							per_err.show('slow');	
						} 
						else {
							$('#form_submit').attr('disabled', false);
						}
						our.html(our_percent);
					}
				}
				else {
					our.html(our_percent);
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
		<h2>Edit Partnership for: <?php echo $item_data['item_number']; ?> - <?php echo $item_data['item_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/partnership/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Partnership List'); ?></li>
			<li>|</li>
		</ul>
		<p>Currently, <strong><?php echo $company_data['company_name']; ?></strong> owns <strong><?php echo number_format($company_ownership,2); ?>%</strong> of this item.</p>
		<?php echo form_open('inventory/partnership_edit/' . $partnership_data['partnership_id']); ?>
		<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
		<table class='form_table'>
			<tr>
				<td class='title'>Partner Name: </td>
				<td><?php echo $partnership_data['partner_data']['name']; ?></td>
			</tr>
			<tr>
				<td class='title'>Phone: </td>
				<td><?php echo $partnership_data['partner_data']['phone']; ?></td>
			</tr>
			<tr>
				<td class='title'>Address: </td>
				<td><?php echo $partnership_data['partner_data']['address']; ?></td>
			</tr>
			<tr>
				<td class='title'>Their Ownership: </td>
				<td>
					<input type='hidden' name='partnership_id' value='<?php echo $partnership_data['partnership_id']; ?>' />
					<input id='their_percentage' class='input_field' name='percentage' type='text' size='5' value='<?php echo set_value('percentage', $partnership_data['percentage']); ?>' />% 
				</td>
			</tr>
			<tr>
				<td class='title'>Our Ownsership: </td>
				<td>
					<span id='our_ownership' ></span>%
				</td>
			</tr>			
			<tr>
				<td colspan='2'><?php echo validation_errors(); ?><span id='percent_error' class='warning'></span></td>
			</tr>
			<tr>
				<td></td>
				<td><input id='form_submit' type='submit' value='Update Partnership' /></td>
			</tr>
		</table>
		<?php echo form_close(); ?>
		<?php echo form_open('inventory/partnership_delete');?>
			<div class='delete_admin_item'>
				<h3>Delete Partnership?</h3>
				<p>If you need to delete this partnership, click on the Delete Partnership button to remove this partnership.</p>
				<input type='hidden' name='item_id' value='<?php echo $partnership_data['item_id']; ?>' />
				<input type='hidden' name='partner_id' value='<?php echo $partnership_data['partner_id']; ?>' />
				<input type='hidden' name='partnership_id' value='<?php echo $partnership_data['partnership_id']; ?>' />
				<button type='submit' value='Delete Partnership'><?php echo snappy_image('icons/cross.png');?> Delete Partnership</button>
			</div>
		<?php echo form_close(); ?>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>