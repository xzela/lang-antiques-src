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
			<?php if(!$action): ?>
				<li><?php echo anchor('appraisals/appraisal_list/1', 'Show Me Every Appraisal')?></li>
			<?php else: ?>
				<li><?php echo anchor('appraisals/appraisal_list/0', 'Show Me My Appraisals')?></li>
			<?php endif;?>
		</ul>
		<?php echo $pagination; ?>
		<table id='invoice_list'>
			<tr>
				<th>Appraisal ID</th>
				<th>Invoice ID</th>
				<th>Customer</th>
				<th>Item Number</th>
				<th>Appraiser</th>
				<th>Date</th>
				<th>Options</th>
			</tr>
			<?php if(sizeof($appraisals) > 0):?>
				<?php foreach($appraisals as $appraisal):?>
					<tr>
						<td><?php echo $appraisal['appraisal_id']; ?></td>
						<td><?php echo anchor('sales/invoice/' . $appraisal['invoice_id'], $appraisal['invoice_id']); ?></td>
						<td><?php echo anchor('customer/edit/' . $appraisal['customer_id'], $appraisal['buyer_name']); ?></td>
						<td><?php echo anchor('inventory/edit/' . $appraisal['item_id'], $appraisal['item_number']); ?></td>
						<td><?php echo $appraisal['appraiser_name']; ?></td>
						<td><?php echo date('M d, Y', strtotime($appraisal['appraisal_date']));?></td>
						<td><?php echo anchor('sales/appraisal/' . $appraisal['appraisal_id'], 'View Appraisal'); ?></td>
					</tr>
				<?php endforeach;?>
			<?php else: ?>
				<tr>
					<td colspan='8' class='warning'>Nothing Found</td>
				</tr>
			<?php endif;?>
		</table>
		<?php echo $pagination; ?>
		<p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>