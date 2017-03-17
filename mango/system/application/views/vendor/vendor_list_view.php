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

	<?php echo snappy_script('vendor/vendor_main.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Vendor List</title>
	<script type="text/javascript">

	var base_url = '<?php echo base_url(); ?>';
 
	var acOption = {
			minChars: 2,
			dataType: 'json',
			scrollHeight: 600,
			cacheLength: 0,
			max: 50,
			extraParams: {	
				format: 'json'
			},
			parse: function(data) {
				var parsed = [];
				if(typeof(data.people) != 'undefined') {
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
				console.log(item);
				str = item.contact;
				if(item.type == 1) { //1=customer, 2=vendor
					str += '<br /> ' + item.spouse;
				}
				else {
					if(item.phone != null && item.contact_name.length > 2) {
						str += '<br /> ' + item.contact_name;
					}
				}
				if(item.phone != null && item.phone.length > 2) {
					str += '<br /> ' + item.phone;
				}
				
				str += '<br /> ' + item.address + ' ' + item.city;
				return str;
			}
		};		
		$(document).ready(function() {
			$("#vendor_input")
				.autocomplete(base_url+'vendor/AJAX_get_vendor_names/', acOption)
				.attr('name', 'contact')
				.after('<input type="hidden" name="user_id" id="ac_result">')
				.result(function(e, item) {
					document.location = base_url+'vendor/edit/'+ item.vendor_id;
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
		<h2>Vendor: List All Vendors</h2>
		<ul id="submenu">
			<li><?php echo anchor('vendor/add', snappy_image('icons/group_add.png') . 'Create New Vendor'); ?></li>
			<li>|</li>
			<li><?php echo anchor('vendor/list_vendors', 'List All Vendors'); ?></li>
			<li>|</li>
		</ul>
		
		<div style='border: 1px solid #999999; padding: 10px; margin: 3px;'>
			Search Name: <input id='vendor_input' name='vendor_input' type='text' style='width: 250px;' /> <input type='button' value='Search' />
		</div>
		<div class='pagination'>
			<?php echo $pagination;?>		
		</div>
		<table class="customer_table">
			<tr>
				<th>Company Name</th>
				<th>Name</th>
				<th>Contact</th>
				<th>Address</th>
				<th>Options</th>
			</tr>
			<?php if($vendors['num_rows'] > 0):?>
				<?php unset($vendors['num_rows']);?>
				<?php foreach($vendors as $vendor): ?>
					<tr>
						<td><?php echo anchor('vendor/edit/' . $vendor['vendor_id'], $vendor['name']); ?></td>
						<td><?php echo $vendor['first_name']; ?> <?php echo $vendor['last_name']; ?></td>
						<td>
							<?php echo $vendor['email']; ?><br />
							<?php echo $vendor['phone']; ?> <br />
						</td>
						<td>
							<?php echo $vendor['address']; ?><br />
							<?php echo $vendor['city']; ?> <?php echo $vendor['state']; ?>, <?php $vendor['zip']; ?> 
						</td>
						<td><?php echo anchor('vendor/edit/' . $vendor['vendor_id'], 'Edit'); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else:?>
				<tr><td colspan='5'>No Vendors Found! </td></tr>
			<?php endif;?>
		</table>
		<div class='pagination'>
			<?php echo $pagination;?>		
		</div>
		<p>Vendor Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>