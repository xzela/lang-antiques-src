<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Link item with Mango (langdb01)</title>
	<style type='text/css'>
		.submit_form {
			border: 1px solid #a1a1a1;
			padding: 5px;
			margin-left: 5px;
		}
		
		.submit_form h3 {
			border-bottom: 1px dashed #909090;
		}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Link item with Mango (langdb01)</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . 'Back to Item'); ?></li>
		</ul>
		
		<?php echo form_open('inventory/create_database_link/' . $item_data['item_id']);?>
		<div class='submit_form'>
			<h3>Enter the Lang Item Number: </h3>
			<input type='text' name='lang_item_number' value='' />
			<input type='submit' name='lang_item_number_submit' value='Create Link' />
		</div>
		<?php echo form_close();?>
		<h3>Frandango Item Information:</h3>
		<table class='form_table'>
			<tr>
				<td class='title'>Images:</td>
				<td>
					<?php if(sizeof($item_data['image_array']['external_images']) > 0): ?>
						<?php foreach($item_data['image_array']['external_images'] as $image): ?>
							<?php
								echo anchor('inventory/show_image/' . $image['image_id'] . '/external', "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $image['image_location'] . '&image_type=' . $image['image_class'] . '&image_size=' . $image['image_size'] . "' />");
							?>
						<?php endforeach; ?>
					<?php elseif(sizeof($item_data['image_array']['internal_images']) > 0):?>
						<?php foreach($item_data['image_array']['internal_images'] as $image): ?>
							<?php
								echo anchor('inventory/show_image/' .$image['image_id'] . '/internal', "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $image['image_location'] . '&image_type=' . $image['image_class'] . '&image_size=' . $image['image_size'] . "' /> ");
							?>
						<?php endforeach; ?>
					<?php else: ?>
						No Image Provided						
					<?php endif; ?>		
				</td>
			</tr>
			<tr>
				<td class='title'>Frandango ID:</td>
				<td><?php echo $item_data['item_id']; ?></td>
			</tr>
			<tr>
				<td class='title'>Item Number:</td>
				<td><?php echo $item_data['item_number']; ?></td>
			</tr>
			<tr>
				<td class='title'>Title:</td>
				<td><?php echo $item_data['item_name']; ?></td>
			</tr>
		</table>
		
		<?php if($this->input->post('lang_item_number_submit')): ?>
			<?php if(sizeof($lang_data) > 0): ?>
				<table class='form_table'>
					<tr>
						<td class='title'>Images:</td>
						<td>
							<?php if(sizeof($lang_data['image_array']['external_images']) > 0): ?>
							<?php foreach($lang_data['image_array']['external_images'] as $image): ?>
								<?php
									echo anchor('inventory/show_image/' . $image['image_id'] . '/external', "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $image['image_location'] . '&image_type=' . $image['image_class'] . '&image_size=' . $image['image_size'] . "' />");
								?>
							<?php endforeach; ?>
						<?php elseif(sizeof($lang_data['image_array']['internal_images']) > 0):?>
							<?php foreach($lang_data['image_array']['internal_images'] as $image): ?>
								<?php
									echo anchor('inventory/show_image/' .$image['image_id'] . '/internal', "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $image['image_location'] . '&image_type=' . $image['image_class'] . '&image_size=' . $image['image_size'] . "' /> ");
								?>
							<?php endforeach; ?>
						<?php else: ?>
							No Image Provided						
						<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td class='title'>Mango ID:</td>
						<td><?php echo $lang_data['item_id']; ?></td>
					</tr>
					<tr>
						<td class='title'>Mango Number:</td>
						<td><?php echo $lang_data['item_number']; ?></td>
					</tr>
					<tr>
						<td class='title'>Mango Title:</td>
						<td><?php echo $lang_data['item_name']; ?></td>
					</tr>
				</table>
				<?php echo form_open('inventory/create_database_link/' . $item_data['item_id']);?>
					<div class='submit_form'>
						<h3>Are you sure you want to link these two items?</h3>
						<input type='hidden' name='fran_id' value='<?php echo $item_data['item_id'] ?>' />
						<input type='hidden' name='lang_id' value='<?php echo $lang_data['item_id'] ?>' />
						<input type='submit' name='create_link' value='Yes, Link these two'/> | <?php echo anchor('inventory/create_database_link/' . $item_data['item_id'], 'No Way!')?>
					</div>
				<?php echo form_close();?>			
			<?php else: ?>
				<h2 class='warning'>Whoa... whatever you typed in didn't return any results</h2>
				<h3>Check the number and try again.</h3>
			<?php endif;?>
			
		<?php endif; ?>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>