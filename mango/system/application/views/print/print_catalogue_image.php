<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<title><?php echo $this->config->item('project_name'); ?> - Print Catalogue Image Report: <?php echo $report_data['report_name']; ?></title>
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
			width: 120px;
			height: 100px;
			text-align: center;
			float: left;
			padding: 5px;
			margin: 5px;
			border: 1px solid #999;
			background-color: #e8e8e8;
			page-break-inside: avoid;
		}
		
		div.image {
			
		}
		#page_size {
			width: 800px;
			margin: 0 auto;
		}
		div.item div.image img {
			border: 1px solid #999;
		}
		
	</style>
</head>
<body>
	<h2>Catalogue Image Report: <?php echo $report_data['report_name']; ?></h2>
	<div id='page_size'>
		<?php if(sizeof($report_items) > 0): ?>
			<?php foreach($report_items as $item): ?>
				<div class='item'>		
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
					<br />
					<strong><?php echo $item['item_number']; ?></strong>
				</div>
			<?php endforeach;?>
		<?php else: ?>
			<div class='warning'>No Items found. Try adding some.</div>
		<?php endif;?>
	</div>
</body>
</html>