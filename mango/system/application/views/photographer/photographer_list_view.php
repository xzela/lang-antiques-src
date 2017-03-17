<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Photographer - List Photographed</title>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<script type="text/javascript">
	var base_url = <?php echo "'" . base_url() . "'"; ?>;
	
	$(document).ready(function() {
		$('.photographed').bind('click', function() {
			var id = $(this).closest("tr").attr('id');
			$.post(base_url + 'photographer/AJAX_updatePhotoStatus', {
					item_id: id,
					value: 2,
					column: 'photo_queue'
				},
				$(this).closest("tr").fadeOut('slow'),
				'json'
			);
		});
		
		$('.edited').bind('click', function() {
			var id = $(this).closest("tr").attr('id');
			$.post(base_url + 'photographer/AJAX_updatePhotoStatus', {
					item_id: id,
					value: 2,
					column: 'edit_queue'
				},
				$(this).closest("tr").fadeOut('slow'),
				'json'
			);
		});		

		$('.remove_photo').bind('click', function() {
			var id = $(this).closest('tr').attr('id');
			$.post(base_url + 'photographer/AJAX_updatePhotoStatus', {
					item_id: id,
					value: 0,
					column: 'photo_queue'
				},
				$(this).closest('tr').fadeOut('slow'),
				'json'
			);
		});
		
		$('.remove_edit').bind('click', function() {
			var id = $(this).closest('tr').attr('id');
			$.post(base_url + 'photographer/AJAX_updatePhotoStatus', {
					item_id: id,
					value: 0,
					column: 'edit_queue'
				},
				$(this).closest('tr').fadeOut('slow'),
				'json'
			);
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
		<h2>Photographed Listings</h2>
		<ul id='submenu'>
			<li><?php echo anchor('photographer', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Photographer Main'); ?></li>
			<li>|</li>
			
			
			<?php if($type == 2): ?>
				<li><?php echo anchor('photographer/list_photographed', 'View Photograph Queue'); ?></li>
				<li>|</li>
				<li><?php echo anchor('photographer/list_edited', 'Show Non-Photographed'); ?></li>
			<?php endif ?>
			
			<?php if($type == 0): ?>
				<li><?php echo anchor('photographer/list_edited_only_photo', 'View Edit Queue'); ?></li>
			<?php endif ?>

			<?php if($type == 1): ?>
				<li><?php echo anchor('photographer/list_photographed	', 'View Photograph Queue'); ?></li>
				<li>|</li>
				<li><?php echo anchor('photographer/list_edited_only_photo', 'Show Only Photographed'); ?></li>														
			<?php endif; ?>
		</ul>
		<div class='pagination'>
			<?php echo $pagination;?>		
		</div>
		<table class="customer_table">
			<tr>
				<th width='150px'>
					Item Number
					<?php echo anchor('photographer/' . $this->uri->segment('2') . '/item_number/asc', snappy_image('icons/arrow_down.png', '', 'pagination_sort')) ?>
					<?php echo anchor('photographer/' . $this->uri->segment('2') . '/item_number/desc', snappy_image('icons/arrow_up.png', '', 'pagination_sort')) ?>					
				</th>
				<th style='width: 45%;'>
					Title
					<?php echo anchor('photographer/' . $this->uri->segment('2') . '/item_name/asc', snappy_image('icons/arrow_down.png', '', 'pagination_sort')) ?>
					<?php echo anchor('photographer/' . $this->uri->segment('2') . '/item_name/desc', snappy_image('icons/arrow_up.png', '', 'pagination_sort')) ?>					
				</th>
				<th>
					Queue Status
				</th>
				<th>Options</th>
			</tr>
			<?php if(isset($items['items']) && sizeof($items['items']) > 0 ): ?>
				<?php foreach($items['items'] as $item): ?>
					<tr id='<?php echo $item['item_id'] ?>'>
						<td nowrap>
							<?php
								echo anchor('inventory/edit/'. $item['item_id'],  $item['item_number']);
							?>
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
						<td >
							<?php echo $item['icon_status']; ?>
							<?php if($item['high_res_image'] == 1): ?>
								<?php echo snappy_image('icons/photo_add.png'); ?> Needs High Res Image
							<?php endif;?> 
						</td>
						<td class='end' nowrap>
							<?php if($type == 0): ?>
								<?php echo anchor('inventory/upload_external_image/' . $item['item_id'], snappy_image('icons/camera_go.png') . 'Upload Web Image', "class='green'"); ?><br />
								<a class='red remove_photo' href="javascript:void(0)"> <?php echo snappy_image('icons/cross.png'); ?> Remove From Queue</a><br />
								<a class='green photographed' href="javascript:void(0)" > <?php echo snappy_image('icons/tick.png'); ?> Mark as Photographed</a>
							<?php elseif($type == 1 || $type == 2): ?>
								<?php echo anchor('inventory/upload_external_image/' . $item['item_id'], snappy_image('icons/camera_go.png') . 'Upload Web Image', "class='green'"); ?><br />
								<a class='red remove_edit' href="javascript:void(0)"> <?php echo snappy_image('icons/cross.png'); ?> Remove From Queue</a><br />
								<a class='green edited' href="javascript:void(0)" > <?php echo snappy_image('icons/tick.png'); ?> Mark as Edited</a>
							
							<?php endif; ?>
							<br />Price: $<?php echo number_format($item['item_price'], 2);?>
						</td>
					</tr>
				<?php endforeach ?>
			<?php else: ?>
				<tr>
					<td colspan='5' >Nothing for you to do, Yay!</td>
				</tr>
			<?php endif;?>
		
		</table>
		<div class='pagination'>
			<?php echo $pagination;?>		
		</div>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>