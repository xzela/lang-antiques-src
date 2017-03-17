<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Run A Turnover Report</title>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Run A Turnover Report </h2>
		<ul id="submenu">
			<li><?php echo anchor('reports', '<< Back to Reports Main'); ?></li>
			<li>|</li>
		</ul>
		<h3>Monthly Turnover Report: </h3>
		<?php echo form_open('reports/run_monthly_turnover_report/');?>
		<table class='item_information'>
			<tr>
				<td class='title' >Reporting Date: </td>
				<td>
					<select name='month'>
					<?php foreach($months as $month):?>
						<?php if(strftime('%B') == $month['name']): ?>
							<option value='<?php echo $month['id']; ?>' selected><?php echo $month['name']; ?></option>
						<?php else:?>
							<option value='<?php echo $month['id']; ?>' ><?php echo $month['name']; ?></option>
						<?php endif;?>
					<?php endforeach;?>					
					</select>
					<select name='year'>
					<?php foreach($years as $year):?>
						<?php if(strftime('%Y') == $year['name']): ?>
							<option value='<?php echo $year['id']; ?>' selected><?php echo $year['name']; ?></option>
						<?php else:?>
							<option value='<?php echo $year['id']; ?>' ><?php echo $year['name']; ?></option>
						<?php endif;?>
					<?php endforeach;?>
					</select>					
				</td>
			</tr>
			<tr>
				<td></td>
				<td ><input type='submit' value='Run Report' /></td>
			</tr>
		</table>
		<?php echo form_close();?>
		
		<h3>Yearly Turnover Report: </h3>
		<?php echo form_open('reports/run_yearly_turnover_report/');?>
		<table class='item_information'>
			<tr>
				<td class='title' >Reporting Date: </td>
				<td>
					<select name='month1'>
					<?php foreach($months as $month):?>
						<?php if(strftime('%B') == $month['name']): ?>
							<option value='<?php echo $month['id']; ?>' selected><?php echo $month['name']; ?></option>
						<?php else:?>
							<option value='<?php echo $month['id']; ?>' ><?php echo $month['name']; ?></option>
						<?php endif;?>
					<?php endforeach;?>					
					</select>				
					<select name='year1'>
					<?php foreach($years as $year):?>
						<?php if(strftime('%Y') == $year['name']): ?>
							<option value='<?php echo $year['id']; ?>' selected><?php echo $year['name']; ?></option>
						<?php else:?>
							<option value='<?php echo $year['id']; ?>' ><?php echo $year['name']; ?></option>
						<?php endif;?>
					<?php endforeach;?>			
					</select>
					 to 
					<select name='month2'>
					<?php foreach($months as $month):?>
						<?php if(strftime('%B') == $month['name']): ?>
							<option value='<?php echo $month['id']; ?>' selected><?php echo $month['name']; ?></option>
						<?php else:?>
							<option value='<?php echo $month['id']; ?>' ><?php echo $month['name']; ?></option>
						<?php endif;?>
					<?php endforeach;?>					
					</select>					
					<select name='year2'>
					<?php foreach($years as $year):?>
						<?php if(strftime('%Y') == $year['name']): ?>
							<option value='<?php echo $year['id']; ?>' selected><?php echo $year['name']; ?></option>
						<?php else:?>
							<option value='<?php echo $year['id']; ?>' ><?php echo $year['name']; ?></option>
						<?php endif;?>
					<?php endforeach;?>
					</select>			
				</td>
			</tr>
			<tr>
				<td></td>
				<td ><input type='submit' value='View Summary of Report' /> || <input name='download_csv' type='submit' value='Download Excel File' /></td>
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