<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Inventory Upload Images for <?php echo $item_data['item_number']; ?></title>
	<style>
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Upload Images - <?php echo $item_data['item_number']; ?> - <?php echo $item_data['item_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item');?></li>
			<li>|</li>
			<li><a href='http://www.langantiques.com/category/<?php echo $item_data['mjr_class_id']; ?>/4/item/<?php echo $item_data['item_number']; ?>/' target='_blank'> <?php echo snappy_image('icons/world.png')?> View Web Page</a></li>
		</ul>
		<h3>Scan Images <span class='small_text'>[<?php echo anchor('inventory/upload_internal_image/' . $item_data['item_id'], 'Upload/Remove Scan Images'); ?>]</span></h3>
		<div class='image_area'>
			<?php foreach($internal_images as $image): ?>
				<?php
					echo anchor('inventory/show_image/' .$image['image_id'] . '/internal', "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $image['image_location'] . '&image_type=' . $image['image_class'] . '&image_size=' . $image['image_size'] . "' /> ");
				?>
			<?php endforeach; ?>
		</div>				
		
		<h3>Web Images <span class='small_text'>[<?php echo anchor('inventory/upload_external_image/' . $item_data['item_id'], 'Upload/Remove Web Images'); ?>]</span></h3>
		<div class='image_area'>
			<?php foreach($external_images as $image): ?>
				<?php
					echo anchor('inventory/show_image/' . $image['image_id'] . '/external', "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $image['image_location'] . '&image_type=' . $image['image_class'] . '&image_size=' . $image['image_size'] . "' />");
				?>
			<?php endforeach; ?>
		</div>				
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>