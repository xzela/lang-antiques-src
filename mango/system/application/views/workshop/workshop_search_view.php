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

	<?php echo snappy_script('workshop/workshop_main.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Search For A Workshop</title>
	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>';
	window.onload = function () { 
		new Ajax.Autocompleter('workshop_input', 
				'workshop_results', 
				base_url+'workshop/AJAX_get_workshop_names/',{
					frequency: 1,
					paramName: "value",
					minChars: 2,
					afterUpdateElement: getSelectionId  
					});
	}

	/**
	* Used by the AJAX call to get the workshop_id
	* and send the user to the edit screen
	*/
	function getSelectionId(text, li) {
		document.location = base_url+'workshop/edit/' + li.id;
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
		height:64px;
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
		<h2>Workshop Search:</h2>
		<ul id="submenu">
			<li><?php echo anchor('workshop/search_workshops', snappy_image('icons/find.png') . 'Search For a Workshop'); ?></li>
			<li>|</li>
			<li><?php echo anchor('workshop/add', snappy_image('icons/basket_add.png') . 'Create New Workshop'); ?></li>
			<li>|</li>
			<li><?php echo anchor('workshop/list_workshops', 'List All Workshops'); ?></li>
			<li>|</li>
		</ul>
		<div>
			Search Name: <input id='workshop_input' name='workshop_input' type='text' style='width: 250px;' /> <input type='button' value='Search' />
			<div id='workshop_results' class="autocomplete">
			</div>
		</div>
		<p>Workshop Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>