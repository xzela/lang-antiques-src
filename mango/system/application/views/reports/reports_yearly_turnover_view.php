<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Yearly Turnover Report</title>
	<style type='text/css'>
	table {
		border-collapse: collapse;
	}
	
	th {
		background-color: #ccc;
	}
	
	td {
		padding: 2px;
		vertical-align: top;
		border-bottom: 1px dashed #ccc;
	}
	
	td.right_side {
		border-right: 1px solid #999;
	}
	
	td.left_side {
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
		<h2>Run Yearly Turnover Report </h2>
		<ul id="submenu">
			<li><?php echo anchor('reports/turnover', '<< Back to Reports Main'); ?></li>
			<li>|</li>
		</ul>
		<h3>Yearly Turnover Report: </h3>
		<table>
			<tr>
				<td class='heading'>Major Class</td>
				<td class='heading'>Count</td>
				<td class='heading'>Cost</td>
				<td class='heading'>Retail</td>
			</tr>
			<?php foreach($major_keys as $key) : ?>
				<?php 
					$group_total_cost = 0;
					$group_total_price = 0;
				?>
				<tr>
					<td class='left_side'><?php echo $key; ?></td>
					<td><?php echo sizeof($major_group[$key]); ?></td>
				
				<?php foreach($major_group[$key] as $item): ?>
					<?php 
						$grand_total_count++;
						$grand_total_cost += $item['purchase_price'];
						$group_total_cost += $item['purchase_price'];
						$grand_total_price += $item['sale_price'];
						$group_total_price += $item['sale_price'];
					?>
				<?php endforeach;?>
					<td>$<?php echo number_format($group_total_cost,2);?></td>
					<td class='right_side'>$<?php echo number_format($group_total_price,2);?></td>
				</tr>
			<?php endforeach;?>
				<tr>
					<td colspan='3' class="sum left_side title">Grand Total Count:</td>
					<td  class="sum right_side" ><?php echo $grand_total_count; ?></td>
				</tr>
				<tr>
					<td colspan='3' class="sum left_side title">Grand Total Cost:</td>
					<td  class="sum right_side" >$<?php echo number_format($grand_total_cost,2); ?></td>
				</tr>
				<tr>
					<td colspan='3' class="sum left_side title">Grand Total Price:</td>
					<td class="sum right_side" >$<?php echo number_format($grand_total_price,2); ?></td>
				</tr>
				
		</table>
		<p>Reports Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>