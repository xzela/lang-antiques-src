<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Grouped Major Class/Minor Class Report</title>
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
?>
	<div id="content">
		<h2>Grouped Major Class/Minor Class Cost and Retail Report </h2>
		<ul id="submenu">
			<li><?php echo anchor('reports', '<< Back to Reports Main'); ?></li>
			<li>|</li>
		</ul>
		<h3>Grouped Major Class/Minor Class  Cost and Retail Report: </h3>
		<?php echo form_open('reports/grouped_major_minor_class_report'); ?>
			<table class='form_table'>
				<tr>
					<td class='title'>Major Class:</td>
					<td>
						<select name='major_class_id'>
							<option></option>
							<?php foreach($major_classes as $major): ?>
								<?php if($this->input->post('major_class_id') == $major['major_class_id']): ?>
									<option value='<?php echo $major['major_class_id']; ?>' selected ><?php echo '[' . $major['major_class_id'] . '] ' . $major['major_class_name']; ?> </option>
								<?php else:?>
									<option value='<?php echo $major['major_class_id']; ?>' ><?php echo '[' . $major['major_class_id'] . '] ' . $major['major_class_name']; ?> </option>
								<?php endif;?>
								
							<?php endforeach; ?>
						</select>					
					</td>			
				</tr>
				<tr>
					<td class='title'>Minor Class:</td>
					<td>
						<select name='minor_class_id'>
							<option></option>
							<?php foreach($minor_classes as $minor): ?>
								<?php if($this->input->post('minor_class_id') == $minor['minor_class_id']): ?>
									<option value='<?php echo $minor['minor_class_id']; ?>' selected ><?php echo '[' . $minor['minor_class_id'] . '] ' . $minor['minor_class_name']; ?> </option>
								<?php else: ?>
									<option value='<?php echo $minor['minor_class_id']; ?>' ><?php echo '[' . $minor['minor_class_id'] . '] ' . $minor['minor_class_name']; ?> </option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>					
					</td>
				</tr>
				<tr>
					<td class='title'></td>
					<td><input type='submit' value='Run Report' /></td>
				</tr>
			</table>
		<?php echo form_close(); ?>
		<table class='customer_table' >
			<tr>
				<th>Major Class</th>
				<th>Minor Class</th>
				<th>Total Cost</th>
				<th>Total Retail</th>
			</tr>
			<?php $t_cost = 0;?>
			<?php $t_retail = 0;?>
			<?php foreach($report_data as $data):?>
				<?php
					 $t_cost += $data['purchase_price'];
					 $t_retail += $data['item_price'];
				?>
				<tr>
					<td>[<?php echo $data['mjr_class_id']; ?>] <?php echo $major_classes[$data['mjr_class_id']]['major_class_name']?></td>
					<td>[<?php echo $data['min_class_id']; ?>] <?php echo @$minor_classes[$data['min_class_id']]['minor_class_name']?></td>
					<td>$<?php echo number_format($data['purchase_price'],2); ?></td>
					<td>$<?php echo number_format($data['item_price'],2); ?></td>
				</tr>
			<?php endforeach;?>
			<tr>
				<td class='title' colspan='2'>Total:</td>
				<td>$<?php echo number_format($t_cost, 2);?></td>
				<td>$<?php echo number_format($t_retail, 2);?></td>
			</tr>
		</table>		
		<p>Reports Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>