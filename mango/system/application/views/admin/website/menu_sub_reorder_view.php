<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Reorder Menu Elements </title>

	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery-ui-1.7.2.js'); ?>

	<script type="text/javascript">
	var base_url = <?php echo "'" . base_url() . "'"; ?>;
	var p_id = <?php echo $element_data['element_id']; ?>;
	$(document).ready(function() {
		$(function() {
			$('.menu_items').sortable({
					opacity: 0.8,
					cursor: 'move',
					update: function() {
						var order = $(this).sortable('toArray');
						//alert(order);
						$.post(base_url + 'website/AJAX_updateSubMenuElementSeq', {
								parent_id: p_id,
								id: $(this).attr('id'),
								order: order.join(",")
							}
						);
					}
				});
		});
	});	
	</script>
	<style type='text/css'>
	.menu_items {
		width: 200px;
		border: 1px solid #60272F;
		
	}
	.menu_items li {
		cursor: move;
		margin: 0px;
		padding: 5px;
		background-color: #ffc;
		border-left: 1px dashed #60272f;
	}
	.menu_items li span {
		color: #990000;
			
	}
	.menu_items li span:hover {
		color: #CC9999;
		text-decoration: underline;
	}	
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class='item'>Admin - Reorder Menu Elements </h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin/menu_edit/' . $element_data['element_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Menu Element'); ?></li>
			<li>|</li>
		</ul>
		<p>Drop and Drag to Reorder The Menu Elements</p>
		<div class='menu_area'>
			<h3 class='menu_h3'><?php echo $element_data['element_name']; ?></h3>
			<ol id='1' class='menu_items'>
				<?php foreach($sub_elements as $element):?>
					<li id='<?php echo $element['sub_element_id']; ?>'><span><?php echo $element['sub_element_name']; ?></span></li>
				<?php endforeach;?>
			</ol>
		</div>
		
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>