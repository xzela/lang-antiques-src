<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
		
	<title><?php echo $this->config->item('project_name'); ?> - Customer View Job: <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?></title>

	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>/';
	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Customer View Job: <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?></h2>
		<ul id="submenu">
			<li><?php echo anchor('customer/jobs/' . $customer['customer_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Customer Jobs'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/edit_job/' . $job_data['job_id'], snappy_image('icons/pencil.png') . ' Edit This Job'); ?></li>
			<li>|</li>
		</ul>
		<table class='form_table'>
			<tr>
				<td class='title'>Item Description:</td>
				<td colspan='3'><?php echo $job_data['item_description']; ?></td>
			</tr>
			<tr>
				<td class='title'>Workshop:</td>
				<td colspan='3'><?php echo anchor('workshop/edit/' . $workshop['workshop_id'], $workshop['name']); ?></td>
			</tr>
			<tr>
				<td class='title'>Requester:</td>
				<td><?php echo $user_name; ?></td>
				<td class='title'>Inspector: </td>
				<td><?php echo $inspector_name; ?></td>
			</tr>
			<tr>
				<td class="title">Open Date:</td>
				<td><?php echo $job_data['open_date']; ?></td>
				<td class="title">Act. Return Date:</td>
				<td><?php echo $job_data['act_return_date']; ?></td>
			</tr>
			<tr>
				<td class="title">Est. Return Date:</td>
				<td><?php echo $job_data['est_return_date']; ?></td>
			</tr>						
			<tr>
				<td class="title">Est. Price:</td>
				<td >$<?php echo number_format($job_data['est_price'],2); ?></td>
				<td class="title">Act. Price:</td>
				<td >$<?php echo number_format($job_data['act_price'], 2); ?></td>			
			</tr>
			<tr>
				<td class="title">Job Cost:</td>
				<td colspan='3'>$<?php echo number_format($job_data['job_cost'],2); ?></td>
			</tr>			
			<tr>
				<td class="title">Instructions:</td>
				<td colspan="3"><?php echo $job_data['instructions']; ?></td>
			</tr>
			<tr>
				<td colspan='4' style='text-align: center;'>
					This Job was <?php echo $job_data['status_text'];?> on <?php echo $job_data['close_date']; ?>
				</td>
			</tr>
		</table>
		<p>Customer Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>