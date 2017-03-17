<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - List All Items with Material </title>

	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>index.php/';
	</script>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class='item'>Admin - List All Items with Material</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin/material_edit/' . $material_data['material_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . 'Back to Material'); ?></li>
			<li>|</li>			
		</ul>
		<p>Here is a list of all the known Items with this Material.</p>
		<table class='customer_table'>
			<tr>
				<th>Item Number</th>
				<th>Item Name</th>
				<th>Description</th>
				<th>Status</th>
			</tr>
			<?php foreach($items as $item):?>
				<tr>
					<td>
						<?php echo anchor('inventory/edit/'. $item['item_id'],  $item['item_number']); ?>
						<br />
						<?php if(sizeof($item['image_array']['external_images']) > 0): ?>
							<?php
								echo anchor('inventory/edit/' . $item['item_id'] , "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['external_images'][0]['image_location'] . '&image_type=' . $item['image_array']['external_images'][0]['image_class'] . '&image_size=' . $item['image_array']['external_images'][0]['image_size'] . "' />");
							?>
						<?php elseif(sizeof($item['image_array']['internal_images']) > 0):?>
							<?php 
							echo anchor('inventory/edit/' . $item['item_id'], "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $item['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $item['image_array']['internal_images'][0]['image_size'] . "' />");
							?>
						<?php else: ?>
							No Image Provided						
						<?php endif; ?>					
					</td>
					<td><?php echo $item['item_name']; ?></td>
					<td><?php echo $item['item_description']; ?></td>
				</tr>
			<?php endforeach;?>
		</table>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>