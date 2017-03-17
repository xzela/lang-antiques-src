<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Inventory</title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Inventory Listings - <?php echo $search_name; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/add', snappy_image('icons/page_add.png') . 'Create New Item'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory_list/list_all_items', 'All Items'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory_list/list_available_items', 'Available Items'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory_list/list_online_items', 'Online Items'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory_list/list_sold_items', 'Sold Items'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory_list/list_non_sold_items_with_images', 'Non-Sold w/ Web Images'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory_list/list_non_sold_items_without_images', 'Non-Sold w/o Web Images'); ?></li>
		</ul>
		<div class='pagination'>
			<?php echo $pagination; ?>
			<div style='margin-left: 5px;'>
				<?php echo $items['num_rows']; ?> items matched your criteria.
			</div>
		</div>
		<table class="inventory_list_table">
			<tr>
				<th nowrap>
					Item Number
					<?php echo anchor($direction_url . 'item_number/asc', snappy_image('icons/arrow_down.png', '', 'pagination_sort')) ?>
					<?php echo anchor($direction_url . 'item_number/desc', snappy_image('icons/arrow_up.png', '', 'pagination_sort')) ?>
				</th>
				<th>Status</th>
				<th>
					Title
					<?php echo anchor($direction_url . 'item_name/asc', snappy_image('icons/arrow_down.png', '', 'pagination_sort')) ?>
					<?php echo anchor($direction_url . 'item_name/desc', snappy_image('icons/arrow_up.png', '', 'pagination_sort')) ?>
				</th>
				<th>
					Description
					<?php echo anchor($direction_url . 'item_description/asc', snappy_image('icons/arrow_down.png', '', 'pagination_sort')) ?>
					<?php echo anchor($direction_url . 'item_description/desc', snappy_image('icons/arrow_up.png', '', 'pagination_sort')) ?>
					
				</th>
				<th nowrap>
					Retail Price
					<?php echo anchor($direction_url . 'item_price/asc', snappy_image('icons/arrow_down.png', '', 'pagination_sort')) ?>
					<?php echo anchor($direction_url . 'item_price/desc', snappy_image('icons/arrow_up.png', '', 'pagination_sort')) ?>
					
				</th>
				<th>Options</th>
			</tr>
			<?php if($items['num_rows'] > 0): ?>
				<?php foreach($items['items'] as $item): ?>
					<tr>
						<td nowrap>
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
						<td nowrap>
							<?php echo $item['icon_web_status']; ?><br />
							<?php echo $item['icon_status']; ?>
						</td>
						<td style='width: 250px;'><?php echo $item['item_name']; ?></td>
						<?php
						$summary = $item['item_description'];
						$limit = 200;
						if (strlen($summary) > $limit) {
								$summary = substr($summary, 0, strrpos(substr($summary, 0, $limit), ' ')) . '<span style="white-space: nowrap"> ...(' . anchor('inventory/edit/'. $item['item_id'], 'more') . ')</span>';
						}				
						?>
						<td><?php echo $summary; ?></td>
						<td>$<?php echo number_format($item['item_price'], 2); ?></td>
						<td nowrap>
							<?php echo anchor('inventory/edit/' . $item['item_id'], 'View Item' ); ?>
						</td>
					</tr>
				<?php endforeach ?>
			<?php else: ?>
				<tr>
					<td colspan='6'>No items found.</td>
				</tr>
			<?php endif;?>
		</table>
		<div class='pagination'>
			<?php echo $pagination;?>		
		</div>
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>
</body>
</html>