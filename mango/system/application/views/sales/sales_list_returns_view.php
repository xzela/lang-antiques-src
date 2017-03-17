<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - <?php echo $search_name; ?></title>
	<style>
		#invoice_list {
			width: 100%;
			font-size: 14px;
			border: 1px solid #9f9f9f;
			border-collapse: collapse;
		}
		#invoice_list th {
			background-color: #c9c9c9;
			border-bottom: 1px solid #9f9f9f;
		}
		#invoice_list td {
			padding: 3px;
			border-bottom: 1px dashed #c9c9c9;
			vertical-align: top;
		}
		#invoice_list td.option {
			border-left: 1px solid #c9c9c9;
		}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2><?php echo $search_name; ?></h2>
		<ul id="submenu">
			<li><?php echo anchor('sales', '<< Back to Sales Main'); ?></li>
			<li>|</li>
		</ul>
		<div class='pagination'>
			<?php echo $pagination;?>		
		</div>
		<table id='invoice_list'>
			<tr>
				<th>ID</th>
				<th>Buyer</th>
				<th>Amount</th>
				<th>Date</th>
				<th>Refund Type</th>
				<th>Options</th>
			</tr>
			<?php if(sizeof($returns) > 0):?>
				<?php foreach($returns as $return):?>
					<tr>
						<td><?php echo $return['return_id']; ?></td>
						<td>
							<?php if($return['buyer_type'] == 3):?>
								<b>Internet Sale:</b> <?php echo $return['buyer_name'];?>
							<?php else: ?>
								<?php echo $return['buyer_name'];?>
							<?php endif;?>
						</td>
						<td>$<?php echo number_format($return['refund'], 2); ?></td>
						<td><?php echo date('M d, Y', strtotime($return['date'])); ?></td>
						<td><?php echo $credit_type[$return['refund_type']]['name'];?></td>
						<td class='option'>
							<?php echo anchor('sales/returns/' . $return['return_id'], 'View Return Slip'); ?>
							<br />
							<?php echo anchor('sales/invoice/' . $return['invoice_id'], 'View Invoice'); ?>
						</td>
					</tr>
				<?php endforeach;?>
			<?php else: ?>
				<tr>
					<td colspan='8' class='warning'>Nothing Found</td>
				</tr>
			<?php endif;?>
		</table>
		<div class='pagination'>
			<?php echo $pagination;?>		
		</div>
			
		<p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>