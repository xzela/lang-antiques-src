<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 


	<title><?php echo $this->config->item('project_name'); ?> - Inventory Check Report Report</title>
	<script type="text/javascript">

	window.onload = function() {
		window.print();
	}
	
	</script>
	<style type='text/css'>
	body {
		font-family: Lucida Grande, Verdana, Sans-serif;
		font-size: 14px;
		color: #4F5155;
	}
	tr {
		page-break-inside: avoid; 
	}
	td {
		vertical-align: top;
		border-bottom: 1px dashed #666;
	}
	
	span.warning {
		color: red;
	}
	
	</style>
</head>
<body>
	<h1>Inventory Check Report</h1>				
		<table class='customer_table'>
			<tr>
				<th width='100px'>Item Number</th>
				<th >Item Name</th>
				<th>Cost</th>
				<th >Price</th>
				<th width='180px'>Status</th>
				<th width='70px'>Quantity</th>
			</tr>
			<?php 
				$t_cost = 0;
				$t_retail = 0;
				$t_count = 0;
			?>
			<?php foreach($report_data as $item): ?>
				<?php
					$t_cost += $item['purchase_price'];
					$t_retail += $item['item_price'];
					$t_count += $item['item_quantity'];
				?>
				<tr>
					<td>
						<?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_number']); ?>
							<?php if($images): ?>
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
						<?php endif;?>						
					</td>
					<td><?php echo $item['item_name']; ?></td>
					<td>$<?php echo number_format($item['purchase_price'],2); ?></td>
					<td>$<?php echo number_format($item['item_price'],2); ?></td>
					<td>
						<?php echo $status[$item['item_status']]['name']; ?>
						<?php if($item['item_status'] == 0): //sold ?>
							<?php if(sizeof($item['buyer_data']) > 0): ?>								
								<br /> <?php echo $item['buyer_data']['name']; ?>
							<?php else: ?>
								[<span class=warning>No Invoice Found</span>]
							<?php endif;?>
						<?php elseif($item['item_status'] == 2): //out on job ?>
							<?php if(sizeof($item['workshop_data']) > 0): ?>
								<br /> 
								<?php echo anchor('workshop/edit/' .$item['workshop_data'][0]['workshop_id'] ,$item['workshop_data'][0]['name']); ?>
							<?php else: ?>
								[<span class=warning>No Job Found</span>]
							<?php endif;?>
						<?php elseif($item['item_status'] == 3): //pending sale ?>
							<?php if(sizeof($item['buyer_data']) > 0): ?>
								[<?php echo 'Invoice: ' . $item['invoice_data']['invoice_id']; ?>]
								<br /> <?php echo anchor($item['buyer_data']['link'], $item['buyer_data']['name']) ?>
							<?php else:?>
								[<span class=warning>No Invoice Found</span>]
							<?php endif;?>
						<?php elseif($item['item_status'] == 4): //out on memo ?>
							<?php if(sizeof($item['memo_data']) > 0): ?>
								[<?php echo 'Memo: ' . $item['memo_data']['invoice_id']; ?>]
								<br /> 
								<?php echo anchor($item['memo_data']['buyer_data']['link'], $item['memo_data']['buyer_data']['name']) ?>
							<?php else:?>
								[<span class=warning>No Memo Found</span>]
							<?php endif;?>									
						<?php elseif($item['item_status'] == 7): //returned to Consignee ?>
							<?php if(sizeof($item['seller_data']) > 0): ?>
								<br /> <?php echo anchor($item['seller_data']['link'], $item['seller_data']['name']); ?>
							<?php else:?>
								<br />  [<span class=warning>No Seller Found</span>]
							<?php endif;?>
							
						<?php endif;?>
					</td>
					<td><?php echo $item['item_quantity']; ?></td>
				</tr>
			<?php endforeach;?>
			<tr>
				<td></td>
				<td class='title' style='text-align: right;'><strong>Total:</strong></td>
				<td><strong>$<?php echo number_format($t_cost, 2);?></strong></td>
				<td><strong>$<?php echo number_format($t_retail, 2);?></strong></td>
				<td></td>
				<td><strong><?php echo $t_count; ?></strong></td>
			</tr>
		</table>
</body>
</html>