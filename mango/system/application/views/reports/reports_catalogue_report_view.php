<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Catalogue Inventory Report: <?php echo $report_data['report_name']; ?></title>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<script type='text/javascript'>
	$(document).ready(function() {
		$('.open_delete').bind('click', function() {
			var div = $('#delete_div');
			if(div.is(':hidden')) {
				div.slideDown('fast');
			}
			else {
				div.slideUp('fast');
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
		<h2>Catalogue Inventory Report: <?php echo $report_data['report_name']; ?></h2>
		<ul id="submenu">
			<li><?php echo anchor('reports/list_catalogue_reports', '<< Back to Report List'); ?></li>
			<li>|</li>
			<li><?php echo anchor('reports/catalogue_report/' . $report_data['report_id'] .'/' . $report_data['report_type'] . '/add', snappy_image('icons/add.png') . ' Add Item to Report'); ?></li>
			<li>|</li>
			<li><?php echo anchor('printer/catalogue_report/' . $report_data['report_id'] . '/' . $report_data['report_type'], snappy_image('icons/printer.png') .  'Print Report', 'target="_blank"'); ?></li>
			<li>|</li>
			<?php if($report_data['report_type']): ?>
				<li><?php echo anchor('reports/convert_catalogue_report/' . $report_data['report_id'] . '/0', snappy_image('icons/page_refresh.png') .  'Convert to Inventory Report'); ?></li>
				<li>|</li>
			<?php else:?>
				<li><?php echo anchor('reports/convert_catalogue_report/' . $report_data['report_id'] . '/1', snappy_image('icons/page_refresh.png') .  'Convert to Image Report'); ?></li>
				<li>|</li>
			<?php endif;?>
			<li><span class='fake_link open_delete'><?php echo snappy_image('icons/cross.png');?> Delete Report</span></li>
		</ul>
		<div id='delete_div' class='delete_admin_item' style='display:none;'>
			<h3>Delete Catalogue Report</h3>
			<p class='warning'>Are you sure you want to delete this Catalogue Report?</p>
			<?php echo form_open('reports/remove_catalogue_report'); ?>
				<input type='hidden' name='report_id' value='<?php echo $report_data['report_id']; ?>' />
				<input type='submit' name='delete_submit' value='Yes, Delete Report' /> | <span class='fake_link open_delete'>Cancel</span>
			<?php echo form_close(); ?>
		</div>
		<h3>Items on Report</h3>
		<table class='customer_table'>
			<tr>
				<th width='100px'>Item Number</th>
				<th width='60%'>Item Description</th>
				<th nowrap>Item Price</th>
				<th nowrap>Options</th>
			</tr>
			<?php if(sizeof($report_items) > 0): ?>
				<?php foreach($report_items as $item): ?>
					<tr>
						<td>
							<?php echo $item['item_number']; ?>
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
								<?php echo snappy_image('no_image.jpg', '', '' , 'height="75px" width="75px"'); ?>
							<?php endif; ?>
						</td>
						<td>
							<strong><?php echo $item['item_name']; ?></strong><br />
							<?php echo $item['item_description']; ?>
						</td>
						<td>$<?php echo number_format($item['item_price'], 2); ?></td>
						<td>
							<?php echo anchor('inventory/edit/' . $item['item_id'], snappy_image('icons/magnifier.png') . 'View Item')?>
							<br />
							<?php echo anchor('reports/catalogue_report/' . $report_data['report_id'] . '/' . $report_data['report_type'] . '/remove/' . $item['item_id'], snappy_image('icons/cross.png') . 'Remove Item', 'class="red"')?>
						</td>
					</tr>
				<?php endforeach;?>
			<?php else: ?>
				<tr>
					<td colspan='5' class='warning'>No Items found. Try adding some.</td>
				</tr>
			<?php endif;?>
		</table>
		<p>Reports Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>