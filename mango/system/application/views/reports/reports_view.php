<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Reports!</title>
	<style type="text/css">
		.admin_ul li.new a {
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
		<h2>Reports!</h2>
		<h2 class="admin_h2">Catalogue Reports!</h2>
		<h3 class="admin_h3">Catalogue Report Options:</h3>
		<ul class="admin_ul">
			<li><?php echo anchor('reports/list_catalogue_reports', 'List Catalogue Reports'); ?> </li>
			<li><?php echo anchor('reports/create_catalogue_report/0', 'New Catalogue Inventory Report');?></li>
			<li><?php echo anchor('reports/create_catalogue_report/1', 'New Catalogue Image Report');?></li>
		</ul>

		<!--
		<h2 class="admin_h2">System Reports</h2>
		<h3 class="admin_h3">System Report Options:</h3>
		<ul class="admin_ul">
			<li>View ASAP Report</li>
			<li>Clear ASAP Report</li>
		</ul>
		-->

		<h2 class="admin_h2">Inventory Reports!</h2>
		<h3 class="admin_h3">Inventory Report Options:</h3>
		<ul class="admin_ul">
			<li><?php echo anchor('reports/inventory_check_report', 'Inventory Check Report'); ?></li>
			<li><?php echo anchor('reports/grand_total_report', 'Inventory Grand Total Report'); ?></li>
			<li><?php echo anchor('reports/grouped_major_minor_class_report', 'Group Major Class/Minor Class Cost and Retail Report'); ?></li>
			<!-- <li><?php echo anchor('reports/detailed_category_cost_retail_report', 'Detailed Category Cost and Retail Report'); ?></li> -->
			<li><?php echo anchor('reports/turnover', 'New Inventory Turnover Report'); ?></li>

		</ul>


		<h2 class="admin_h2">Sales Reports!</h2>
		<h3 class="admin_h3">Sales Report Options:</h3>
		<ul class="admin_ul">
			<li class="new"><?php echo anchor('reports/daily_monies_report', 'Daily Monies Report'); ?></li>
			<li><?php echo anchor('reports/monthly_sales_report', 'Run a Monthly Sales Report'); ?></li>
			<li><?php echo anchor('reports/open_memo', 'Open Memo Report'); ?></li>
			<li><?php echo anchor('reports/store_credit', 'Store Credit Report')?></li>
			<li><?php echo anchor('reports/layaways', 'Layaway Report')?></li>
		</ul>


		<p>Reports Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>