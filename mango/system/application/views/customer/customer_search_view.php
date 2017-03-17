<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<?php echo snappy_script('ajax/prototype.js'); ?>
	<?php echo snappy_script('ajax/scriptaculous.js'); ?>
	<?php echo snappy_script('ajax/controls.js'); ?>
	<?php echo snappy_script('ajax/effects.js'); ?>

	<?php echo snappy_script('customer/customer_main.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Search For Customers</title>
	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>';
	window.onload = function () { 
		new Ajax.Autocompleter('customer_input', 
				'customer_results', 
				base_url+'customer/AJAX_get_customer_names/',{
					frequency: 1,
					paramName: "value",
					minChars: 2,
					afterUpdateElement: getSelectionId  
					});
	}

	function getSelectionId(text, li) {
		document.location = base_url+'customer/edit/' + li.id;
	}
	</script>
	<style>
	div.autocomplete {
	  position:relative;
	  width:350px;
	  background-color:white;
	  border:1px solid #888;
	  margin:0;
	  padding:0;
	  display:block;
	  overflow: auto;
	  height: 400px;
	}
	div.autocomplete ul {
	  list-style-type:none;	 
	  margin:0;
	  padding:0;
	}
	div.autocomplete ul li.selected { background-color: #ffb;}
	div.autocomplete ul li {
		border-bottom: 1px dashed #999;
		list-style-type:none;
		display:block;
		margin:10px;
		padding:2px;
		height:50px;
		cursor:pointer;
	}
	
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Customers</h2>
		<ul id="submenu">
			<li><?php echo anchor('customer/add', snappy_image('icons/user_add.png') . 'Create New Customer'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/list_customers', 'List All Customers'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/search_customers', snappy_image('icons/find.png') . 'Search For a Customer'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/generate_mailing_list', 'Generate Mailing List'); ?></li>
			<li>|</li>
		</ul>
		<div style='border: 1px solid #999999; padding: 10px; margin: 3px;'>
			Search Name: <input id='customer_input' name='customer_input' type='text' style='width: 250px;' /> <input type='button' value='Search' />
			<div id='customer_results' class="autocomplete">
			</div>
		</div>
		<p>Customer Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>