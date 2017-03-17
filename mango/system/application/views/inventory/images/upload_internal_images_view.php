<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Inventory Upload External Images for <?php echo $item_data['item_number']; ?></title>
	<?php echo snappy_script('ajax/prototype.js'); ?>
	<?php echo snappy_script('ajax/scriptaculous.js'); ?>
	<?php echo snappy_script('ajax/controls.js'); ?>
	<?php echo snappy_script('ajax/effects.js'); ?>
	<?php echo snappy_script('inventory/photograph.js'); ?>
	<script type='text/javascript'>
		var base_url = '<?php echo base_url(); ?>';
	</script>
	
	<style>
	.area {
		border: 1px solid #999;
		padding: 5px;
		margin: 5px;
		background-color: #efefef;
		
	}
	#sort_images {
		
	}
	#sort_images li {
		padding: 5px;
		margin: 5px;
		border: 1px solid #d9d9d9;
		background-color: #fcfcfc;
	}
	#sort_images li img {
		padding: 5px;
		margin: 5px;
		border: 1px solid #d9d9d9;
		background-color: #fff;
	}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Upload Internal Images - <?php echo $item_data['item_number']; ?> - <?php echo $item_data['item_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
		</ul>
		<h3>Select a File to Upload</h3>
		<div class='area'>
			<?php $attr = array('enctype' => 'multipart/form-data'); ?>
			<?php echo form_open('inventory/upload_internal_image/' . $item_data['item_id'] . '/true', $attr);?>
				<table>
					<tr>
						<td>File:</td>
						<td>
							<input id="imgfile" name="imgfile" type="file" size='70'/>
							<input name="mjr_class_id" type="hidden" value='<?php echo $item_data['mjr_class_id']; ?>'/>
						</td>
					</tr>
					<tr>
						<td></td>
						<td><input id="upload_submit" name="upload_submit" type="submit"  value='Upload Image' /></td>
					</tr>
					
				</table>
				<?php echo $upload_messages; ?>
			<?php echo form_close(); ?>
		</div>
		<h3>Applied Images</h3>
		<div class='area'>
			<ol id='sort_images'>
			<?php if(sizeof($internal_images) > 0): ?>
				<?php foreach($internal_images as $image): ?>
					<li id='seq_<?php echo $image['image_id'] ?>'>
						<div>
						<?php
							echo "<div><strong>File Name:</strong> " . $image['image_name'] . "</div>";
							echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $image['image_location'] . '&image_type=' . $image['image_class'] . '&image_size=' . $image['image_size'] . "' /> <br />";
							echo anchor('inventory/remove_image/' . $item_data['item_id'] . '/' . $image['image_id']. '/internal', 'Remove This Image', 'class="red"');
						?>	
						</div>	
					</li>
				<?php endforeach; ?>
			<?php else: ?>			
					<li>No Images Found</li>
			<?php endif; ?>
			</ol>
		</div>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>