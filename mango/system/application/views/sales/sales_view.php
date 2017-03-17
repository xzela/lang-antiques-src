<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title>Project Mango - Sales and Invoices</title>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class="admin_h2">Invoices</h2>
		<h3 class="admin_h3">Invoice Options:</h3>
		<ul class="admin_ul">
			<li><?php echo anchor('sales/create_invoice/customer', snappy_image('icons/user.png') . ' New Customer Invoice'); ?></li>
			<li><?php echo anchor('sales/create_invoice/vendor', snappy_image('icons/group.png') . ' New Vendor Invoice'); ?></li>
			<li><?php echo anchor('sales/search/memos', snappy_image('icons/layout_link.png') . '  Convert Memo to Invoice'); ?></li>
		</ul>
		<h2 class="admin_h2">Memos</h2>
		<h3 class="admin_h3">Memo Options:</h3>
		<ul class="admin_ul">
			<li><?php echo anchor('sales/create_memo/vendor', snappy_image('icons/layout_add.png') . ' New Vendor Memo'); ?></li>
		</ul>
		<h2 class="admin_h2">Internet Sales</h2>
		<h3 class="admin_h3">Internet Sale Options:</h3>
		<ul class="admin_ul">
			<li><?php echo anchor('sales/search/internet', snappy_image('icons/magnifier.png') . ' View Internet Sales'); ?></li>
		</ul>
		<h2 class="admin_h2">Searching</h2>
		<h3 class="admin_h3">Search Options:</h3>
		<ul class="admin_ul">
			<li><?php echo anchor('sales/search_id', snappy_image('icons/magnifier.png') . ' Search for Invoice/Memo'); ?></li>
			<li><?php echo anchor('sales/search/all', snappy_image('icons/magnifier.png') . ' View All Sales'); ?></li>
			<li><?php echo anchor('sales/search/vendor-invoice', snappy_image('icons/magnifier.png') . ' View All Vendor Invoices'); ?></li>
			<li><?php echo anchor('sales/search/memos', snappy_image('icons/magnifier.png') . ' View All Memos'); ?></li>
			<li><?php echo anchor('sales/search/open-memos', snappy_image('icons/magnifier.png') . ' View Open Memos'); ?></li>
			<li><?php echo anchor('sales/search_returns', snappy_image('icons/magnifier.png') . ' View All Returns'); ?></li>
			<li><?php echo anchor('appraisals/appraisal_list', snappy_image('icons/magnifier.png') . ' View All Appraisals'); ?></li>
		</ul>
		<h2 class"admin_h2">Gateway</h2>
		<h3 class"admin_h3">Gateway Options</h3>
		<ul class='admin_ul'>
			<li><?php echo anchor('sales/gateway_results', 'View Gateway Results'); ?></li>
		</ul>
		<p>Sales and Invoices Section of Project Mango</p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>