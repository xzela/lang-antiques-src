<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Edit Menu Element </title>

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
		<h2 class='item'>Admin - Edit Menu Element - <?php echo $element_data['element_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin/menu_list', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Menu List'); ?></li>
			<li>|</li>			
			<li><?php echo anchor('admin/menu_sub_add/' . $element_data['element_id'], 'Add A Sub Element'); ?></li>
			<li>|</li>
			<li><?php echo anchor('admin/menu_sub_reorder/' . $element_data['element_id'], 'ReOrder Sub Elements'); ?></li>
			<li>|</li>
		</ul>
		<p>You can Edit a new Menu Element here</p>
		<?php echo form_open('admin/menu_edit/' . $element_data['element_id']);?>
		<div id='change_message' style='display: none'>You've made changes to this record. They won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
		<table class='form_table'>
			<tr>
				<td class='title'>Element Type: </td>
				<td><?php echo $element_types[$element_data['element_type']]?></td>
			</tr>
			<?php if($element_data['element_type'] == 3): ?>
				<tr>
					<td class='title'>Element Name: </td>
					<td><input name='element_name' size='40' type='text' class='input_field' value="<?php echo set_value('element_name'), $element_data['element_name']; ?>" /></td>
				</tr>
				<tr>
					<td class='title'>Element Title:</td>
					<td><input type='text' size='40' name='special_title' class='input_field' value="<?php echo set_value('special_title', $element_data['special_title'])?>" /></td>
				</tr>
			<?php else: ?>
				<tr>
					<td class='title'>Element Name: </td>
					<td><?php echo $element_data['element_name']; ?>
						<?php if($element_data['element_type'] == 1): ?>
							[To rename, <?php echo anchor('admin/major_class_edit/' . $element_data['element_type_id'], 'click here'); ?>]
						<?php else:?>
							[To rename, <?php echo anchor('admin/modifier_edit/' . $element_data['element_type_id'], 'click here'); ?>]
						<?php endif;?>
					</td>
				</tr>
			<?php endif;?>
			<tr>
				<td class='title'>Sequence:</td>
				<td><?php echo $element_data['element_seq']; ?> [To reorder menu elements, <?php echo anchor('admin/menu_reorder', 'click here')?>] </td>
			</tr>
			<tr>
				<td class='title'>Element URL:</td>
				<td><?php echo $element_data['element_url']; ?></td>
			</tr>
			<?php if($element_data['element_type'] == 3): ?>
				<tr>
					<td class='title'>Meta Description:</td>
					<td>
						<textarea name='meta_description' class='input_field' rows='2' cols='40'><?php echo set_value('meta_description', $element_data['meta_description']); ?></textarea>
					</td>
				</tr>
				<tr>
					<td class='title'>Page Paragraph:</td>
					<td>
						<textarea name='page_paragraph' class='input_field' rows='4' cols='40'><?php echo set_value('page_paragraph', $element_data['page_paragraph']); ?></textarea>
					</td>
				</tr>
				<tr>
					<td></td>
					<td><?php echo validation_errors(); ?></td>
				</tr>
				<tr>
					<td></td>
					<td><input type='submit' value='Submit Changes' /></td>
				</tr>
			<?php endif;?>
		</table>
		<?php echo form_close(); ?>
		<h3>Current Sub Menu Elements:</h3>
		<table class='customer_table'>
			<tr>
				<th>Sub Menu ID</th>
				<th>Sub Element Name</th>
				<th>Sub Element Link</th>
				<th>Sub Element Seq</th>
				<th>Enabled</th>
				<th>Options</th>
			</tr>
			<?php if(sizeof($sub_elements) > 0): ?>
				<?php foreach($sub_elements as $sub): ?>
					<tr>
						<td><?php echo $sub['sub_element_id']; ?></td>
						<td><?php echo $sub['sub_element_name']; ?></td>
						<td><?php echo $sub['sub_element_url']; ?></td>
						<td><?php echo $sub['sub_element_seq']; ?></td>
						<td><?php echo $sub_states[$sub['sub_element_status']]?></td>
						<td>
							<?php echo form_open('admin/menu_sub_delete'); ?>
								<input type='hidden' name='parent_element_id' value='<?php echo $element_data['element_id']; ?>' />
								<input type='hidden' name='sub_element_id' value='<?php echo $sub['sub_element_id']; ?>' />
								<input type='submit' class='warning' value='Delete Sub Element' />
							<?php echo form_close();?>
							<?php echo form_open('admin/menu_sub_status');?>
								<input type='hidden' name='sub_element_id' value='<?php echo $sub['sub_element_id']; ?>' />
								<input type='hidden' name='element_id' value='<?php echo $element_data['element_id']; ?>' />
								<?php if($sub['sub_element_status'] == 1): ?>
									<input type='hidden' name='status' value='<?php echo $sub['sub_element_status']; ?>' />
									<input type='submit' value='Disable Element' />
								<?php else: ?>
									<input type='hidden' name='status' value='<?php echo $sub['sub_element_status']; ?>' />
									<input type='submit' value='Enable Element' />
								<?php endif;?>
							<?php echo form_close(); ?>							
						</td>
					</tr>					
				<?php endforeach;?>
			<?php else: ?>
				<tr>
					<td colspan='5'>No Sub Menu Elements Found...</td>
				</tr>
			<?php endif;?>
		</table>
		
		<div>
			<h3>Delete Menu Element?</h3>
			<?php if(sizeof($sub_elements) > 0): ?>
				<div class='nodelete_admin_item'>
					<p class='warning'>
						<strong>Hold up! </strong>You can't delete this Menu Element yet.
						Reduce the number of sub menu elements down to zero (0).
					</p>
				</div>				
			<?php else: ?>
				<div class='delete_admin_item'>
					<p class='warning'>Are you sure you want to delete this Menu Element?</p>
					<?php echo form_open('admin/menu_delete'); ?>
						<input name='element_id' type='hidden' value='<?php echo $element_data['element_id']?>' />
						<input name='menu_id' type='hidden' value='<?php echo $element_data['element_menu']?>' />
						
						<input type='submit' value='Delete This Menu Element' />
					<?php echo form_close(); ?>
				</div>
			<?php endif;?>
		</div>		
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>