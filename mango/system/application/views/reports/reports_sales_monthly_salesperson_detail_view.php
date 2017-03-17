<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Detailed Monthly Sales Report For: <?php echo $salesperson['first_name'] . ' ' . $salesperson['last_name']; ?></title>
	<style type='text/css'>
	#report_table {
		border: 1px solid #999;
		border-collapse: collapse;
	}
	#report_table td.title {
		border-bottom: 1px solid #999;
		border-top: 1px solid #999;
		font-weight: bold;
		font-size: 16px;
		color: #fff;
		background-color: #aaa;
	}
	#report_table td.invoice {
		border-bottom: 1px dashed #999;
		border-top: 1px solid #999;
		padding: 5px;
		background-color: #ddd;
	}	
	#report_table td.item {
		border-bottom: 1px dashed #999;
		padding: 5px;
		background-color: #fff;
	}	
	#report_table td.sum {
		text-align: right;
		font-weight: bold;
		padding: 5px;
	}
	
	#report_table td.sub_header {
		font-weight: bold;
	}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Detailed Monthly Sales Report For: <?php echo $salesperson['first_name'] . ' ' . $salesperson['last_name']; ?></h2>
		<ul id="submenu">		
			<li><?php echo anchor('reports/run_monthly_salesperson_report/' . $month . '/' . $year, '<< View Monthly Report'); ?></li>
			<li>|</li>
			<li><?php echo anchor('reports', '<< Back to Reports Main'); ?></li>
			<li>|</li>
		</ul>
		
		<h3>Monthly Sales Report By Sales Person: </h3>
		
		<table id='report_table'>
			<tr>
				<td class='title' colspan='4'>Normal Sales</td>
			</tr>
			<?php foreach($report_data as $data):?>
				<tr>
					<td class='invoice'><?php echo $data['invoice_id']?> </td>
					<td class='invoice'><?php echo date('m/d/Y', strtotime($data['sale_date']))?></td>
					<td class='invoice'>
						<?php if($data['buyer_type'] == 1 || $data['buyer_type'] == 3): //customer?>
							<?php echo anchor('customer/edit/' . $data['buyer_id'], $data['buyer_data']['first_name'] . ' ' . $data['buyer_data']['last_name']); ?>
							<?php if($data['buyer_type'] == 3):?>
								Internet Salez!
							<?php endif;?>
						<?php else: ?>
							<?php echo anchor('vendor/edit/' . $data['buyer_id'], $data['buyer_data']['name']); ?>							
						<?php endif;?>
					</td>
					<td class='invoice'><?php echo anchor('sales/invoice/' . $data['invoice_id'], 'View Invoice'); ?></td>
				</tr>
				<?php foreach($data['item_data'] as $item):?>
				<tr>
					<td class='item'></td>
					<td class='item'><?php echo $item['item_number']; ?></td>
					<td class='item'><?php echo $item['item_name']; ?></td>
					<td class='item'>$<?php echo number_format($item['sale_price'], 2); ?></td>
				</tr>
				<?php endforeach;?>
				<?php foreach($data['special_data'] as $item):?>
				<tr>
					<td class='item'></td>
					<td class='item' colspan='2'><?php echo $item['item_description']; ?></td>
					<td class='item'>$<?php echo number_format($item['item_price'], 2); ?></td>
				</tr>
				<?php endforeach;?>
			<?php endforeach;?>
			<tr>
				<td class='title' colspan='4' >Layaway Payments</td>
			</tr>
			<tr>
				<td class='sub_header'></td>
				<td class='sub_header'>Date/Person</td>
				<td class='sub_header'>Invoice</td>
				<td class='sub_header'>Amount</td>
			</tr>
			<?php if(isset($layaway_data['extra']) && sizeof($layaway_data['extra']) > 0 ):?>
				<?php foreach($layaway_data['extra'] as $data): ?>
					<tr>
						<td class='item'></td>
						<td class='item'><?php echo $data['payment_date']; ?>
							<?php if($data['buyer_type'] == 1 || $data['buyer_type'] == 3): //customer?>
								<?php echo anchor('customer/edit/' . $data['buyer_id'], $data['buyer_data']['first_name'] . ' ' . $data['buyer_data']['last_name']); ?>
								<?php if($data['buyer_type'] == 3):?>
									Internet Salez!
								<?php endif;?>
							<?php else: ?>
								<?php echo anchor('vendor/edit/' . $data['buyer_id'], $data['buyer_data']['name']); ?>
							<?php endif;?>
						</td>
						<td class='item'>
							<?php echo $data['invoice_id'] ?> - <?php echo anchor('sales/invoice/' . $data['invoice_id'], 'View Invoice'); ?>
						</td>
						<td class='item'>$<?php echo number_format($data['amount'], 2);?></td>
					</tr>
				<?php endforeach;?>
			<?php else: ?>
				<tr>
					<td colspan='4'>No Layaways found</td>
				</tr>
			<?php endif;?>
				<tr>
					<td colspan='3' class='sum'>Total Layaway Payments:</td>
					<td>$<?php echo number_format($layaway_data['basic']['amount'], 2);?></td>
				</tr>
			<tr>
				<td class='title' colspan='5'>Returns</td>
			</tr>
			<tr>
				<td class='sub_header'></td>
				<td class='sub_header'>Date/Person</td>
				<td class='sub_header'>Invoice</td>
				<td class='sub_header'>Amount</td>
			</tr>
			<?php if(isset($return_data['extra']) && sizeof($return_data['extra']) > 0 ) : ?>
				<?php foreach($return_data['extra'] as $data):?>
					<tr>
						<td class='item'></td>
						<td class='item'><?php echo $data['date']; ?>
							<?php if($data['buyer_type'] == 1 || $data['buyer_type'] == 3): //customer?>
								<?php echo anchor('customer/edit/' . $data['buyer_id'], $data['buyer_data']['first_name'] . ' ' . $data['buyer_data']['last_name']); ?>
								<?php if($data['buyer_type'] == 3):?>
									Internet Salez!
								<?php endif;?>
							<?php else: ?>
								<?php echo anchor('vendor/edit/' . $data['buyer_id'], $data['buyer_data']['name']); ?>
							<?php endif;?>
						</td>
						<td class='item'>
							<?php echo $data['invoice_id'] ?> - <?php echo anchor('sales/invoice/' . $data['invoice_id'], 'View Invoice'); ?>
						</td>
						<td class='item warning'>$-<?php echo number_format($data['refund'], 2);?></td>
					</tr>				
				<?php endforeach;?>
			<?php else: ?>
				<tr>
					<td colspan='4'>No Returns found</td>
				</tr>
			<?php endif;?>
			<tr>
				<td></td>
				<td></td>
				<td class='item sum'>Total Sum of Sales:</td>
				<td class='item' >$<?php echo number_format(($invoice_data['basic']['amount'] + $layaway_data['basic']['amount']) + ($return_data['basic']['amount']), 2); ?></td>
			</tr>
		</table>
		<p>Reports Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>