<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('jquery.autocomplete.css'); //autoloaded ?>
		
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.autocomplete.js'); ?>
	
	<?php echo snappy_script('customer/customer_main.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Customers</title>
	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>';

	var acOption = {
		minChars: 1, //number of chars in text box, 1 (+1);
		dataType: 'json',
		scrollHeight: 600,
		cacheLength: 0,
		max: 50,
		extraParams: {	
			format: 'json'
		},
		parse: function(data) {
			var parsed = [];
			if(typeof(data.people) != 'undefined') { //if undefined, return empty array
				data = data.people;	
				for(var i = 0; i < data.length ; i++) {
					parsed[parsed.length] = {
						data: data[i],
						value: data[i].contact,
						result: data[i].contact
					};
				}
			}
			return parsed;
		},
		formatItem: function(item) {
			str = item.contact + '[' + item.customer_id + ']';
			if(item.spouse != null && item.spouse.length > 2) {
				str += '<br /> ' + item.spouse;	
			}
			if(item.phone != null && item.phone.length > 2) {
				str += '<br /> ' + item.phone;
			}
			if(item.address != null &&  item.city != null) {
				str += '<br /> ' + item.address + ' ' + item.city;
			}
			return str;
		}
	};
	
	$(document).ready(function() {
		$("#customer_input")
			.autocomplete(base_url+'customer/AJAX_get_customer_names/', acOption)
			.attr('name', 'contact')
			.after('<input type="hidden" name="user_id" id="ac_result">')
			.result(function(e, item) {
				//top.location.href = base_url + 'customer/edit/' + item.customer_id;
				document.location = base_url + 'customer/edit/' + item.customer_id;
			});
	});
	</script>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Customers: List All Customers</h2>
		<ul id="submenu">
			<li><?php echo anchor('customer/add', snappy_image('icons/user_add.png') . 'Create New Customer'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/list_customers', 'List All Customers'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/generate_mailing_list', 'Generate Mailing List'); ?></li>
			<li>|</li>
		</ul>
		
		<div style='border: 1px solid #999999; padding: 10px; margin: 3px;'>
			Search Name: <input id='customer_input' name='customer_input' type='text' style='width: 250px;' /> <input type='button' value='Search' />
		</div>
		<div class='pagination'>
				<?php echo $pagination;?>
		</div>
		<table class="customer_table">
			<tr>
				<th>Name</th>
				<th>Spouse</th>
				<th>Contact</th>
				<th>Address</th>
				<th>Options</th>
			</tr>
			<?php if($customers['num_rows'] > 0): ?>
				<?php unset($customers['num_rows']); ?>
				<?php foreach($customers as $customer): ?>
					<tr>
						<td><?php echo anchor('customer/edit/' . $customer['customer_id'], $customer['first_name'] . ' ' . $customer['last_name']); ?></td>
						<td><?php echo $customer['spouse_first']; ?> <?php echo $customer['spouse_last']; ?></td>
						<td>
							<?php echo $customer['email']; ?><br />
							<?php echo $customer['home_phone']; ?> <br />
						</td>
						<td>
							<?php echo $customer['address']; ?><br />
							<?php echo $customer['city']; ?> <?php echo $customer['state']; ?>, <?php $customer['zip']; ?> 
						</td>
						<td class='end'><?php echo anchor('customer/edit/' . $customer['customer_id'], 'Edit'); ?></td>
					</tr>
				<?php endforeach; ?>
			
			<?php else: ?>
				<tr><td colspan='5'>No Customers Found!</td></tr>
			<?php endif;?>
		</table>
		<div class='pagination'>
			<?php echo $pagination;?>		
		</div>
		<p>Customer Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>