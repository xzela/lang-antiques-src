<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Inventory - Add Item to <?php echo $item_data['item_number']; ?></title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Assemble Item into <?php echo $item_data['item_number'];?> : <?php echo $item_data['item_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/assemble/' . $item_data['item_id'], 'View Assembled List'); ?></li>
		</ul>
		<div class=''>
		<?php echo form_open('inventory/assemble_add_item/' . $item_data['assemble_id']. '/' . $item_data['item_id']);?>
			Enter Item Number: 
			<input type='text' name='item_number' />
			<input type='submit' name='submit_button' value='Add Item'/>
		<?php echo form_close();?>
		</div>
		<?php echo validation_errors(); ?>
		<div>
			<h3>Current Items Assembled</h3>
			<table class='customer_table'>
				<tr>
					<th>Item Number</th>
					<th>Item Name</th>
					<th>Seller</th>
					<th>Cost</th>
					<th>Options</th>
				</tr>				
				<?php if(sizeof($assembled_items) > 0):?>
					<?php foreach($assembled_items as $item):?>
						<tr>
							<td><?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_number'])?></td>
							<td><?php echo $item['item_name']?></td>
							<td>
								<?php if(isset($item['seller_data']) && sizeof($item['seller_data']) > 0): ?>
									<?php echo $item['seller_data']['name']; ?>
								<?php else: ?>
									N/A
								<?php endif;?>
							</td>
							<td>$<?php echo number_format($item['purchase_price'],2); ?></td>
							<td><?php echo anchor('inventory/assemble_remove_item/' . $item_data['assemble_id'] . '/' . $item['item_id'], 'Remove Item');?> </td>
						</tr>
					<?php endforeach;?>
				<?php else: ?>
					<tr>
						<td colspan='5'>No Items Found...</td>
					</tr>
				<?php endif;?>
			</table>
		</div>
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>
</body>
</html>