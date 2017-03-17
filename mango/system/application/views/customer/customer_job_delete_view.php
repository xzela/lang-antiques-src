<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Customer Delete Job: <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?></title>

	<script type="text/javascript">
	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class='item'>Delete Job For: <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?></h2>
		<ul id="submenu">
			<li><?php echo anchor('customer/jobs/' . $customer['customer_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Customer Jobs'); ?></li>
			<li>|</li>
		</ul>
		<h3 class='warning'>Are you Sure you want to delete this Job?</h3>
		<?php echo form_open('customer/delete_job/' . $customer['customer_id'] . '/' . $credit['job_id']) ;?>
		
		<?php echo form_close(); ?>
		<p>Customer Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>