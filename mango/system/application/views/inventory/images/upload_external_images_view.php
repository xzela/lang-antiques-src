<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Inventory Upload Web Images for <?php echo $item_data['item_number']; ?></title>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery-ui-1.7.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.multi.file.upload.1.47.js'); ?>
	
	<script type='text/javascript'>
		var base_url = <?php echo "'" . base_url() . "'"; ?>;
		var id = <?php echo $item_data['item_id']; ?>;
		
		$(document).ready(function() {
			$(function() {
				$('#sort_images').sortable({
						opacity: 0.8,
						cursor: 'move',
						update: function() {
							var order = $(this).sortable('toArray');
							$.post(base_url + 'photographer/AJAX_updatePhotoSeq', {
									item_id: id,
									order: order.join(",")
								}
							);
						}
					});
			});
		});
		
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
		cursor: move;
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
		<h2>Upload Web Images For <?php echo $item_data['item_number']; ?> - <?php echo $item_data['item_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
			<li>|</li>
			<li><a href='http://www.langantiques.com/category/<?php echo $item_data['mjr_class_id']; ?>/4/item/<?php echo $item_data['item_number']; ?>/' target='_blank'> <?php echo snappy_image('icons/world.png')?> View Web Page</a></li>
			<li>|</li>
			<li><?php echo anchor('inventory/image_edit_phrase/' . $item_data['item_id'], 'Edit Image Phrases'); ?></li>
		</ul>
		<h3>Select a File to Upload</h3>
		<div class='area'>
			<?php $attr = array('enctype' => 'multipart/form-data'); ?>
			<?php echo form_open('inventory/upload_external_image/' . $item_data['item_id'], $attr);?>
				<table class='form_table'>
					<tr>
						<td>File:</td>
						<td>
							<input id="imgfile" name="images[]" class='multi' type="file" size='20' />
							<input name="major_class" type="hidden" value='<?php echo $item_data['mjr_class_id']; ?>'/>
						</td>
					</tr>
					<tr>
						<td></td>
						<td><input id="upload_submit" name="upload_submit" type="submit"  value='Upload Image' /></td>
					</tr>
					<tr>
						<td colspan='2'><?php echo validation_errors(); ?></td>
					</tr>
				</table>
			<?php echo form_close(); ?>
		</div>
		<h3>Applied Images</h3>
		<div class='area'>
			<?php if(sizeof($external_images) > 0): ?>
				<ol id='sort_images'>
				<?php foreach($external_images as $image): ?>	
					<li id='<?php echo $image['image_id'] ?>'>
						<div>
							<div><strong>Sequence:</strong> <?php echo $image['image_seq']; ?></div>
							<div><strong>File Name:</strong> <?php echo $image['image_name']; ?></div>
							<div><strong>Title:</strong><?php echo $image['image_title']; ?></div>
							<img src='<?php echo base_url();?>system/application/views/_global/thumbnail.php?image_location=<?php echo $image['image_location'] . '&image_type=' . $image['image_class'] . '&image_size=' . $image['image_size'] ?>' /> <br />
							<?php echo anchor('inventory/remove_image/' . $item_data['item_id'] . '/' . $image['image_id']. '/external', 'Remove This Image', 'class="red"'); ?>
						</div>						
					</li>
				<?php endforeach; ?>
				</ol>
			<?php else: ?>
				<div>No Images Found</div>			
			<?php endif; ?>
		</div>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>