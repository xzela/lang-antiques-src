<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Add Item to Catalogue Inventory Report: <?php echo $report_data['report_name']; ?></title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Add Item to Catalogue Report: <?php echo $report_data['report_name']; ?></h2>
		<ul id="submenu">
			<li><?php echo anchor('reports/catalogue_report/' . $report_data['report_id'] . '/' . $report_data['report_type'], '<< Back to Catalogue Reports'); ?></li>
			<li>|</li>
		</ul>
		<h3>Add Item to Catalogue Report: </h3>
		<?php echo form_open('reports/catalogue_report/' . $report_data['report_id'] . '/' . $report_data['report_type'] . '/add/');?>
		<table class='item_information'>
			<tr>
				<td class='title' >Item Number: </td>
				<td><input type='text' name='item_number' /></td>
			</tr>
			<tr>
				<td colspan='2'><?php echo validation_errors(); ?></td>
			</tr>
			<tr>
				<td></td>
				<td><input type='submit' name='submit' value='Add Item' /></td>
			</tr>
			
		</table>
		<?php echo form_close();?>
		<p>Reports Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>