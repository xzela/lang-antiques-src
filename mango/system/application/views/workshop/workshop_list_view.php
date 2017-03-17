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

	<?php echo snappy_script('workshop/workshop_main.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Workshops</title>
	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>';
	/**
	* loads the search functions on startup
	*/
	var acOption = {
			minChars: 2,
			dataType: 'json',
			scrollHeight: 600,
			cacheLength: 20,
			extraParams: {	
				format: 'json'
			},
			parse: function(data) {
				var parsed = [];
				data = data.people;
				for(var i = 0; i < data.length ; i++) {
					parsed[parsed.length] = {
						data: data[i],
						value: data[i].contact,
						result: data[i].contact
					};
				}
				return parsed;
			},
			formatItem: function(item) {
				str = item.contact;
				str += '<br /> ' + item.contact_name;
				str += '<br /> ' + item.phone;
				str += '<br /> ' + item.address + ' ' + item.city;
				return str;
			}
		};		
		$(document).ready(function() {
			$("#workshop_input")
				.autocomplete(base_url+'workshop/AJAX_get_workshop_names/', acOption)
				.attr('name', 'contact')
				.after('<input type="hidden" name="user_id" id="ac_result">')
				.result(function(e, item) {
					document.location = base_url + 'workshop/edit/' + item.workshop_id;
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
		<h2>Workshops: List All Workshops</h2>
		<ul id="submenu">
			<li><?php echo anchor('workshop/add', snappy_image('icons/basket_add.png') . 'Create New Workshop'); ?></li>
			<li>|</li>
			<li><?php echo anchor('workshop/list_workshops', 'List All Workshops'); ?></li>
			<li>|</li>
		</ul>
		<div style='border: 1px solid #999999; padding: 10px; margin: 3px;'>
			Search Name: <input id='workshop_input' name='workshop_input' type='text' style='width: 250px;' /> <input type='button' value='Search' />
		</div>

		<div class='pagination'>
			<?php echo $pagination;?>		
		</div>
		<table class="customer_table">
			<tr>
				<th>Company Name</th>
				<th>Contact Name</th>
				<th>Contact</th>
				<th>Address</th>
				<th>Options</th>
			</tr>
			<?php if($workshops['num_rows'] > 0):?>
				<?php unset($workshops['num_rows']); ?>
				<?php foreach($workshops as $workshop): ?>
					<tr>
						<td><?php echo anchor('workshop/edit/' . $workshop['workshop_id'], $workshop['name']); ?></td>
						<td><?php echo $workshop['first_name']; ?> <?php echo $workshop['last_name']; ?></td>
						<td>
							<?php echo $workshop['phone']; ?><br />
							<?php echo $workshop['email']; ?> <br />
						</td>
						<td>
							<?php echo $workshop['address']; ?><br />
							<?php echo $workshop['city']; ?> <?php echo $workshop['state']; ?>, <?php $workshop['zip']; ?> 
						</td>
						<td><?php echo anchor('workshop/edit/' . $workshop['workshop_id'], 'Edit'); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else:?>
				<tr><td colspan='5'>No Workshops Found!</td></tr>
			<?php endif;?>
		</table>
		<div class='pagination'>
			<?php echo $pagination;?>		
		</div>
		<p>Workshop Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>