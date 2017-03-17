<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Run Inventory Grand Total Report</title>
	<style type='text/css'>
	table {
		border-collapse: collapse;
	}
	
	th {
		background-color: #ccc;
		border-top: 1px solid #999;
	}
	
	td {
		padding: 2px;
		vertical-align: top;
		border-bottom: 1px dashed #ccc;
	}
	
	td.right_side,th.right_side {
		border-right: 1px solid #999;
	}
	
	td.left_side,th.left_side {
		border-left: 1px solid #999;
	}
	
	td.heading {
		font-size: 16px;
		font-weight: bold;
		border-bottom: 2px solid #999;
		padding-top: 10px;
	}
	td.title {
		text-align: right;
		font-weight: bold;
	}
	td.sum {
		border-bottom: 1px solid #999;
	}
	span.lang {
		color: green;
	}
	span.fk {
		color: red;
	}
	</style>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
	
	$grand_total_count = 0;
	$grand_total_cost = 0;
	$grand_total_price = 0;
?>
	<div id="content">
		<h2>Grand Total Inventory ReporT</h2>
		<ul id="submenu">
			<li><?php echo anchor('reports', '<< Back to Reports Main'); ?></li>
			<li>|</li>
			<li><?php echo anchor('printer/report_grand_total', 'Print Grand Total Report', 'target="_blank"')?></li>
		</ul>
		<h2>Here's what this report does: </h2>
		<p>This report include all items which are: Available(id:1), Out on Job(id:2), Pending Sale(id:3), Out on Memo(id:4)</p>
		
		<h3><span class='lang'>Lang</span> Grand Total Inventory Report: </h3>
		<table>
			<tr>
				<th class='left_side'>Major Class ID</th>
				<th>Major Class</th>
				<th>Quantity</th>
				<th>Cost</th>
				<th>% of Cost</th>
				<th>Retail</th>
				<th class='right_side'>% of Retail</th>
			</tr>
			<?php
				$t_cost = 0;
				$t_retail = 0;
				$t_count = 0;
				$g_cost = 0;
				$g_retail = 0;
				$g_count = 0;
			?>
			<?php foreach($inventory as $row):?>
				<?php $t_cost += $row['cost']; ?>
				<?php $t_retail += $row['price']; ?>
				<?php $t_count += $row['quantity']; ?>
				<tr>
					<td class='left_side'><?php echo $row['mjr_class_id']; ?></td>
					<td><?php echo $row['mjr_class_name']; ?></td>
					<td><?php echo $row['quantity']; ?></td>
					<td>$<?php echo number_format($row['cost'],2); ?></td>
					<td><?php echo number_format($row['pert_cost'],2); ?></td>
					<td>$<?php echo number_format($row['price'],2); ?></td>
					<td class='right_side'><?php echo number_format($row['pert_price'],2); ?></td>
				</tr>
			<?php endforeach;?>
			<?php
				$g_count += $t_count; 
				$g_cost += $t_cost;
				$g_retail += $t_retail;
			?>
			<tr>
				<td class='title sum left_side' colspan='2'>Normal LANG Grand Total:</td>
				<td class='sum'><?php echo $t_count; ?></td>
				<td class='sum'>$<?php echo number_format($t_cost,2); ?></td>
				<td class='sum'></td>
				<td class='sum'>$<?php echo number_format($t_retail,2); ?></td>
				<td class='sum right_side'></td>
			</tr>
		</table>
		<h3><span class='fk'>FK</span> Grand Total Inventory Report (minor class:91):</h3>
		<table>
			<tr>
				<th class='left_side'>Major Class ID</th>
				<th>Major Class</th>
				<th>Quantity</th>
				<th>Cost</th>
				<th>% of Cost</th>
				<th>Retail</th>
				<th class='right_side'>% of Retail</th>
			</tr>
			<?php
				$t_cost = 0;
				$t_retail = 0;
				$t_count = 0;
			?>
			<?php foreach($fk_inventory as $row):?>
				<?php $t_cost += $row['cost']; ?>
				<?php $t_retail += $row['price']; ?>
				<?php $t_count += $row['quantity']; ?>
				<tr>
					<td class='left_side'><?php echo $row['mjr_class_id']; ?></td>
					<td><?php echo $row['mjr_class_name']; ?></td>
					<td><?php echo $row['quantity']; ?></td>
					<td>$<?php echo number_format($row['cost'],2); ?></td>
					<td><?php echo number_format($row['pert_cost'],2); ?></td>
					<td>$<?php echo number_format($row['price'],2); ?></td>
					<td class='right_side'><?php echo number_format($row['pert_price'],2); ?></td>
				</tr>
			<?php endforeach;?>
			<?php
				$g_count += $t_count; 
				$g_cost += $t_cost;
				$g_retail += $t_retail;
			?>
			<tr>
				<td class='title sum left_side' colspan='2'>Normal FK Grand Total:</td>
				<td class='sum'><?php echo $t_count; ?></td>
				<td class='sum'>$<?php echo number_format($t_cost,2); ?></td>
				<td class='sum'></td>
				<td class='sum'>$<?php echo number_format($t_retail,2); ?></td>
				<td class='sum right_side'></td>
			</tr>
		</table>		
		<h3><span class='lang'>Lang</span> Grand Total Consignment Report: </h3>
		<table>
			<tr>
				<th class='left_side'>Major Class ID</th>
				<th>Major Class</th>
				<th>Quantity</th>
				<th>Cost</th>
				<th>% of Cost</th>
				<th>Retail</th>
				<th class='right_side'>% of Retail</th>
			</tr>
			<?php
				$t_cost = 0;
				$t_count = 0;
				$t_retail = 0; 
			?>
			<?php foreach($consignment as $row):?>
				<?php $t_cost += $row['cost']; ?>
				<?php $t_count += $row['quantity']; ?>
				<?php $t_retail += $row['price']; ?>
				<tr>
					<td class='left_side'><?php echo $row['mjr_class_id']; ?></td>
					<td><?php echo $row['mjr_class_name']; ?></td>
					<td><?php echo $row['quantity']; ?></td>
					<td>$<?php echo number_format($row['cost'],2); ?></td>
					<td><?php echo number_format($row['pert_cost'],2); ?></td>
					<td>$<?php echo number_format($row['price'],2); ?></td>
					<td class='right_side'><?php echo number_format($row['pert_price'],2); ?></td>
				</tr>
			<?php endforeach;?>
			<?php
				$g_count += $t_count;
				$g_cost += $t_cost;
				$g_retail += $t_retail;
			?>			
			<tr>
				<td class='title sum left_side' colspan='2'>Consignment LANG Grand Total:</td>
				<td class='sum'><?php echo $t_count; ?></td>
				<td class='sum'>$<?php echo number_format($t_cost,2); ?></td>
				<td class='sum'></td>
				<td class='sum'>$<?php echo number_format($t_retail,2); ?></td>
				<td class='sum right_side'></td>
			</tr>
		</table>
		<h3><span class='fk'>FK</span> Grand Total Consignment Report (minor class:93): </h3>
		<table>
			<tr>
				<th class='left_side'>Major Class ID</th>
				<th>Major Class</th>
				<th>Quantity</th>
				<th>Cost</th>
				<th>% of Cost</th>
				<th>Retail</th>
				<th class='right_side'>% of Retail</th>
			</tr>
			<?php
				$t_cost = 0;
				$t_count = 0;
				$t_retail = 0; 
			?>
			<?php foreach($fk_consignment as $row):?>
				<?php $t_cost += $row['cost']; ?>
				<?php $t_count += $row['quantity']; ?>
				<?php $t_retail += $row['price']; ?>
				<tr>
					<td class='left_side'><?php echo $row['mjr_class_id']; ?></td>
					<td><?php echo $row['mjr_class_name']; ?></td>
					<td><?php echo $row['quantity']; ?></td>
					<td>$<?php echo number_format($row['cost'],2); ?></td>
					<td><?php echo number_format($row['pert_cost'],2); ?></td>
					<td>$<?php echo number_format($row['price'],2); ?></td>
					<td class='right_side'><?php echo number_format($row['pert_price'],2); ?></td>
				</tr>
			<?php endforeach;?>
			<?php
				$g_count += $t_count;
				$g_cost += $t_cost;
				$g_retail += $t_retail;
			?>
			<tr>
				<td class='title sum left_side' colspan='2'>Consignment FK Grand Total:</td>
				<td class='sum'><?php echo $t_count; ?></td>
				<td class='sum'>$<?php echo number_format($t_cost,2); ?></td>
				<td class='sum'></td>
				<td class='sum'>$<?php echo number_format($t_retail,2); ?></td>
				<td class='sum right_side'></td>
			</tr>
			<tr>
				<td class='title sum left_side' colspan='2'>Total Grand Total:</td>
				<td class='sum'><?php echo $g_count; ?></td>
				<td class='sum'>$<?php echo number_format($g_cost,2); ?></td>
				<td class='sum'></td>
				<td class='sum'>$<?php echo number_format($g_retail,2); ?></td>
				<td class='sum right_side'></td>
			</tr>
		</table>
		<p>Reports Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>