<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Edit Material: <?php echo $material_data['material_name']; ?> </title>

	<?php echo snappy_style('styles.css'); //autoloaded ?>
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
		<h2 class='item'>Admin - Edit Materials: <?php echo $material_data['material_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin/material_list', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Material List'); ?></li>
		</ul>
		<p>Here, edit this Material</p>
		<?php echo form_open('admin/material_edit/' . $material_data['material_id']); ?>
		<div id='change_message' style='display: none'>You've made changes to this record. They won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
		
		<table class='form_table'>
			<tr>
				<td class='title'>Material ID: </td>
				<td><?php echo $material_data['material_id']; ?></td>
			</tr>
			<tr>
				<td class='title'>Material Name: </td>
				<td>
					<input type='text' name='material_name' class='input_field' value='<?php echo set_value('material_name', $material_data['material_name']); ?>' />
				</td>
			</tr>
			<tr>
				<td class='title'>Active: </td>
				<td>
					<select name='active' class='input_field'>
					<?php if($material_data['active']):?>
						<option value='0' >No</option>
						<option value='1' selected>Yes</option>
					<?php else: ?>
						<option value='0' selected>No</option>
						<option value='1'>Yes</option>
					<?php endif;?>
					</select>
				</td>
			</tr>
			<tr>
				<td class='title'>Uses Karats: </td>
				<td>
					<select name='karats' class='input_field'>
					<?php if($material_data['karats']):?>
						<option value='0' >No</option>
						<option value='1' selected>Yes</option>
					<?php else: ?>
						<option value='0' selected>No</option>
						<option value='1'>Yes</option>
					<?php endif;?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan='2'>
					<?php echo validation_errors(); ?>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type='submit' value='Update Material' /></td>
			</tr>			
		</table>
		<?php echo form_close(); ?>
		
		<div>
			<h3>Delete Modifier</h3>
			<?php if($material_data['item_count'] > 0): ?>
				<div class='nodelete_admin_item'>
					<p>You cannot delete this material. It is currently applied to <?php echo $material_data['item_count']; ?> different item(s). 
					<br />
					Reduce this number to 0 (zero) to delete this modifier.
					</p>
				</div>
			<?php else: ?>
				<div class='delete_admin_item'>
					<p>This item is no longer applied to any items. It is safe to delete.</p>
					<?php echo form_open('admin/material_delete/'); ?>
						<input name='material_id' type='hidden' value='<?php echo $material_data['material_id']; ?>' />
						<input type='submit' value='Delete This Material' />
						
					<?php echo form_close(); ?>
				</div>
			<?php endif;?>
		</div>
		<p id='page_end'>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>