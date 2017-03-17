<?php
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />


	<title>
		Return #<?php echo $return_data['return_id']; ?> for
			<?php if($return_data['buyer_type'] == 1 || $return_data['buyer_type'] == 3):?>
				<?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?>
			<?php elseif($return_data['buyer_type'] == 2):?>
				<?php echo  $buyer_data['name']; ?>
			<?php endif; ?>
	</title>
	<script type="text/javascript">
	window.onload = function() {
		//window.print();
	}
	</script>
	<style type='text/css'>
	body {
		font-family: Lucida Grande, Verdana, Sans-serif;
		font-size: 14px;
		color: #4F5155;

	}
	#print_body {
		width: 950px;
		margin: 0 auto;
	}


	h1, h4 {
		padding-top: 0px;
		padding-bottom: 0px;
		margin-top: 0px;
		margin-bottom: 0px;
	}
	#print_header_logo {
		float: left;
		clear: left;
	}
	#print_header_logo p, #print_header p {
		margin: 0px;
	}
	#print_header {
		float: right;
		clear: right;
	}
	.break {
		clear: both;
	}
	.invoice_table {
		border: 1px solid #999999;
		margin: 2px;
		margin-top: 8px;
		margin-bottom: 8px;
		border-collapse: collapse;
	}
	.invoice_table th {
		background-color: #e1e1e1;
		border-bottom: 1px dashed #999999;
	}

	.invoice_table td.header {
		background-color: #e1e1e1;
		border-bottom: 1px dashed #999999;
		border-top: 1px solid #999999;
		font-weight: bold;
	}
	.invoice_table td {
		vertical-align: top;
		border-bottom: 1px dashed #c1c1c1;
	}
	.invoice_table td.top {
		border-top: 1px solid #999999;
	}

	.invoice_table td.title {
		font-weight: bold;
		text-align: right;
	}
	.invoice_table td.left {
		border-left: 1px solid #c1c1c1;
	}
	.invoice_table td.nonborder {
		border-bottom: 0;
	}
	h1 span.small_text {
		font-size: 15px;
	}
	</style>
</head>
<body id='print_body'>
	<div id='print_header_logo' style=''>
		<img src='<?php echo $company_logo['image_location']; ?>' />
		<br />
		<br />
		<br />
		<h4><?php echo $company_data['company_name']; ?></h4>
		<p><?php echo $company_data['address']; ?></p>
		<p><?php echo $company_data['city']; ?>, <?php echo $company_data['state']; ?> <?php echo $company_data['zip']; ?></p>
		<p>Tel: <?php echo $company_data['phone_number']; ?></p>
		<p>Fax: <?php echo $company_data['fax_number']; ?></p>
	</div>
	<div id='print_header' style=''>
		<?php if($return_data['buyer_type'] == 1): ?>
			<h1>Return: <span class='small_text'> in store sale</span></h1>
		<?php elseif($return_data['buyer_type'] == 2): ?>
			<h1>Return: <span class='small_text'>vendor sale</span></h1>
		<?php elseif($return_data['buyer_type'] == 3): ?>
			<h1>Return: <span class='small_text'>internet sale</span></h1>
		<?php else: ?>
			<h1>Return</h1>
		<?php endif;?>
		<h4>Return ID: <?php echo $return_data['return_id']; ?></h4>
		<h4>Original Invoice ID: <?php echo $return_data['invoice_id']; ?></h4>
		<h4>Return Date: <?php echo date('F d, Y', strtotime($return_data['date'])); ?></h4>
		<h4>Original Invoice Date: <?php echo date('F d, Y', strtotime($invoice_data['sale_date'])); ?></h4>
		<br />
		<h4 style='margin-top: 10px;'>
			<?php if($return_data['buyer_type'] == 1 || $return_data['buyer_type'] == 3):?>
				<?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?>
			<?php elseif($return_data['buyer_type'] == 2):?>
				<?php echo  $buyer_data['name']; ?>
			<?php endif; ?>
		</h4>
			<p><?php echo $buyer_data['address']; ?></p>
			<p><?php echo $buyer_data['city']; ?>, <?php echo $buyer_data['state']; ?> <?php echo $buyer_data['zip']; ?></p>
			<p>Home: <?php echo $buyer_data['phone'];?></p>
			<p>Work: <?php echo $buyer_data['other_phone'];?></p>
	</div>
	<div class='break'></div>
	<br />
		<table class='invoice_table'>
			<tr>
				<th nowrap>Number</th>
				<th>Description</th>
				<th nowrap>Retail Price</th>
				<th nowrap>Tax</th>
			</tr>
			<?php if(sizeof($return_items) > 0 ):?>
				<?php foreach($return_items as $item):?>
				<tr>
					<td>
						<div style='text-align: center;'><?php echo $item['item_number']; ?></div>
							<?php if(sizeof($item['image_array']['external_images']) > 0): ?>
								<?php
									echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['external_images'][0]['image_location'] . '&image_type=' . $item['image_array']['external_images'][0]['image_class'] . '&image_size=' . $item['image_array']['external_images'][0]['image_size'] . "' />";
								?>
							<?php elseif(sizeof($item['image_array']['internal_images']) > 0):?>
								<?php
								echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $item['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $item['image_array']['internal_images'][0]['image_size'] . "' />";
								?>
							<?php else: ?>
								No Image Provided
							<?php endif; ?>
					</td>
					<td class='left'>
						<h4><?php echo $item['item_name'];?></h4>
						<p><?php echo $item['item_description'];?></p>
					</td>
					<td class='left'>$<?php echo number_format($item['sale_price'], 2); ?></td>
					<td class='left'>$<?php echo number_format($item['sale_tax'], 2); ?></td>
				</tr>
				<?php endforeach;?>
			<?php endif;?>
			<?php if(sizeof($special_items) > 0):?>
				<tr>
					<td colspan='5' class='header'>Special Orders</td>
				</tr>
				<?php foreach($special_items as $item):?>
					<tr>
						<td colspan='2'><?php echo $item['item_description']; ?></td>
						<td class='left'>$<?php echo number_format($item['item_price'], 2); ?></td>
						<td class='left'>$<?php echo number_format($item['item_tax'], 2); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endif;?>
			<tr>
				<td class='top' ></td>
				<?php if($return_data['refund_type'] == 1 ): //store_credit ?>
					<td class='top title ' style='text-align: right;'>Total Store Credit:</td>
				<?php else: //cash return ?>
					<td class='top title' style='text-align: right;'>Total Return:</td>
				<?php endif;?>
				<td class='left top' colspan='2'>$<?php echo number_format(($return_data['refund']), 2); ?></td>
			</tr>
		</table>
		<?php if($return_data['note'] != ''): ?>
			<h3>Notes:</h3>
			<p>
				<?php echo $return_data['note']; ?>
			</p>
		<?php endif;?>
</body>
</html>