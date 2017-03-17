<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<title><?php echo $this->config->item('project_name'); ?> - Print Catalogue Inventory Report: <?php echo $report_data['report_name']; ?></title>
	<script type='text/javascript'>
	window.onload = function() {
		window.print();
	}	
	</script>
	<style type='text/css'>
		body {
			background-color: #fff;
			margin: 20px;
			font-family: Lucida Grande, Verdana, Sans-serif;
			font-size: 14px;
			color: #4F5155;		
		}
		h2 {
			text-align: center;
		}
		div.item {
			padding: 5px;
			margin: 5px;
			border: 1px solid #999;
			background-color: #e8e8e8;
			page-break-inside: avoid;
		}
		
		div.image {
			float: left;
		}
		
		div.item div.image img {
			border: 1px solid #999;
		}
		
		div.item_info {
			padding: 2px;
			margin: 5px;
			margin-left: 80px;
			border: 1px solid #999;
			background-color: #fff;
			page-break-inside: avoid;
		}
		
		div.item_info .underline {
			text-decoration: underline;
		}
		
		div.clear {
			clear: both;
		}
	</style>
</head>
<body>
	<h2>Catalogue Inventory Report: <?php echo $report_data['report_name']; ?></h2>
	<div>
		<?php if(sizeof($report_items) > 0): ?>
			<?php foreach($report_items as $item): ?>
				<div class='item'>				
					
					<div class='image'>
					<?php if(sizeof($item['image_array']['external_images']) > 0): ?>
						<?php
							echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['external_images'][0]['image_location'] . '&image_type=' . $item['image_array']['external_images'][0]['image_class'] . '&image_size=' . $item['image_array']['external_images'][0]['image_size'] . "' />";
						?>
					<?php elseif(sizeof($item['image_array']['internal_images']) > 0):?>
						<?php 
						echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $item['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $item['image_array']['internal_images'][0]['image_size'] . "' />";
						?>
					<?php else: ?>
						<?php echo snappy_image('no_image.jpg', '', '' , 'height="75px" width="75px"'); ?>
					<?php endif; ?>
					</div>
					<div class='item_info'>
						<strong><?php echo $item['item_number']; ?> - </strong>
						<?php echo $item['item_name']; ?> - $<?php echo number_format($item['item_price'],2); ?>
					</div>
					<?php //item materials ?>
					<?php if(sizeof($item['item_materials']) > 0):?>
					<div class='item_info'>
						<strong>Materials:</strong>
						<?php foreach($item['item_materials'] as $material): ?>
							<span class='underline'><?php echo $material['material_name']; ?>
							<?php if($material['karat'] != null): ?>
								(<?php echo $material['karat']; ?>k)
							<?php endif;?>
							</span>
						<?php endforeach;?>
					</div>
					<?php endif;?>
					
					<?php //item Diamonds ?>
					<?php if(sizeof($item['item_diamonds']) > 0):?>
					<div class='item_info'>
						<?php foreach($item['item_diamonds'] as $diamond): ?>
							<strong><?php echo $diamond['diamond_name']; ?>:</strong>
							Shape: <span class='underline'><?php $diamond['diamond_shape']; ?></span>,
							Weight: <span class='underline'><?php echo $diamond['d_carats']; ?> ctw</span>,
							Quantity: <span class='underline'><?php echo $diamond['d_quantity']; ?></span>,
							Color: <span class='underline'><?php echo $diamond['color']; ?></span>,
							Clarity: <span class='underline'><?php echo $diamond['clarity']; ?></span>
							<?php if($diamond['d_cert_by'] != ''): ?>
								Cert: <span class='underline'><?php echo $diamond['d_cert_by']; ?></span>
							<?php endif;?>
							<br />
						<?php endforeach;?>
					</div>
					<?php endif;?>
						
					<?php //item Gemstone ?>
					<?php if(sizeof($item['item_gemstones']) > 0):?>
					<div class='item_info'>
						<?php foreach($item['item_gemstones'] as $gemstone): ?>
							<strong><?php echo $gemstone['gemstone_name']; ?>:</strong>
							Weight: <span class='underline'><?php echo $gemstone['gem_carat']; ?></span>,
							Quantity: <span class='underline'><?php echo $gemstone['gem_quantity']; ?></span>
							<br />
						<?php endforeach;?>
					</div>
					<?php endif;?>					

					<?php //item Pearls ?>
					<?php if(sizeof($item['item_pearls']) > 0):?>
					<div class='item_info'>
						<?php foreach($item['item_pearls'] as $pearl): ?>
							<strong><?php echo $pearl['pearl_name']; ?>:</strong>
							Weight: <span class='underline'><?php echo $pearl['p_weight']; ?></span>,
							Quantity: <span class='underline'><?php echo $pearl['p_quantity']; ?></span>
							<br />
						<?php endforeach;?>
					</div>
					<?php endif;?>
					<?php //item Opals ?>
					<?php if(sizeof($item['item_opals']) > 0):?>
					<div class='item_info'>
						<?php foreach($item['item_opals'] as $opal): ?>
							<strong><?php echo $opal['opal_name']; ?>:</strong>
							Weight: <span class='underline'><?php echo $opal['o_carat']; ?></span>,
							Quantity: <span class='underline'><?php echo $opal['o_quantity']; ?></span>
							<br />
						<?php endforeach;?>
					</div>
					<?php endif;?>
							
					<?php //item Jadeite ?>
					<?php if(sizeof($item['item_jadeite']) > 0):?>
					<div class='item_info'>
						<?php foreach($item['item_jadeite'] as $jade): ?>
							<strong><?php echo $jade['jade_name']; ?>:</strong>
							Weight: <span class='underline'><?php echo $jade['j_carat']; ?></span>,
							Quantity: <span class='underline'><?php echo $jade['j_quantity']; ?></span>
							<br />
						<?php endforeach;?>
					</div>
					<?php endif;?>						
				</div>
				<div class='clear'></div>
			<?php endforeach;?>
		<?php else: ?>
			<div class='warning'>No Items found. Try adding some.</div>
		<?php endif;?>
	</div>
</body>
</html>