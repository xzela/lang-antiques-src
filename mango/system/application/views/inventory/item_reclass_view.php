<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Reclassify Inventory Item</title>
	<style type="text/css">
	.form_table {
		padding: 10px;
		margin: 5px;
		border: 1px solid #999;
	}
	
	.form_table td.field_label {
		font-weight: bold;
	}
	
	.form_table td.form_errors {
		text-align: center;
		color: red;
	}
	
	</style>
	<script type="text/javascript">
		var base_url = <?php echo "'" . base_url() . "'"; ?>;
		var url = base_url;
		
		$(document).ready(function() {
			$('#major_class').bind('change', function() {
				$('#major_class_span').html($(this).val());
			});
			$('#minor_class').bind('change', function() {
				$('#minor_class_span').html($(this).val());
			});
			$('#suffix').bind('change', function() {
				$('#suffix_span').html($(this).val());
			});

			$('#major_class').trigger('change');
			$('#minor_class').trigger('change');
			$('#suffix').trigger('change');

			//test number
			$('#test_number').click(function() {
				$.post(url + 'inventory/AJAX_testInventoryNumber', {
					'number': $('#major_class').val() + "-" + $('#minor_class').val() + "-" + $("#suffix").val()
				}, 
				function(data) {
					if (data != 1) {
						var div = $('.delete_admin_item');
						if(div.is(':hidden')) {
							div.slideDown();
						}
						div.attr('style', 'color: red');
						div.html('<b>Item Number already exists!</b>');	
					}
					else {
						var div = $('.delete_admin_item');
						if(div.is(':hidden')) {
							div.slideDown();
						}
						div.attr('style', 'color: green');
						div.html('Item Number is Good to Go!');							
					}
				});
			});

			$('#getNextSuffix').bind('click', function() {
				$.post(url + 'inventory/AJAX_getNextSuffixSequence', {
					major_class_id : $('#major_class').val()
				},
				function(data) {
					$('#suffix').val(data);
					$('#suffix').trigger('change');
				});
			});
				//
					//major_class_id: $('#major_class').val()
					//},
					//function(data) {
						//alert(data);
					//}
				//});
		});
	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Inventory - Reclassify Inventory Item</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
		</ul>
		<div>
			<p>This allows you to change the item number of the item. </p>
			<p>Be warned that you can cause duplicate numbers. If you want to use a number that already exists, it won't stop you.</p>
			<p>Use the Check Number link to verify that the number is good to use. </p>
			<?php echo form_open('inventory/reclass/' . $item_data['item_id']); ?>
			<table class='form_table'>
				<tr>
					<td class='title'>Current ID:</td>
					<td>
						<?php echo $item_data['item_number']; ?>
						<?php echo form_hidden('item_number', $item_data['item_number']); ?>
					</td>
				</tr>
				<tr>
					<td class='title'>New ID:</td>
					<td>
						<span id='major_class_span'></span>-<span id='minor_class_span'></span>-<span id='suffix_span'></span>
						[<a id='test_number' href='#' >Check Number</a>]
					</td>
				</tr>
				
				<tr>
					<td class='title'>Major Class:</td>
					<td>
						<select id="major_class" name="major_class">
							<?php foreach($major_classes as $row): ?>
								<?php if(set_value('major_class', $item_data['mjr_class_id']) ==  $row['mjr_class_id']): ?>
									<option selected value="<?php echo $row['mjr_class_id']; ?>"><?php echo $row['mjr_class_name']; ?> [<?php echo $row['mjr_class_id']; ?>]</option>
								<?php else: ?>
									<option value="<?php echo $row['mjr_class_id']; ?>" ><?php echo $row['mjr_class_name']; ?> [<?php echo $row['mjr_class_id']; ?>]</option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class='title' >Minor Class:</td>
					<td>
						<select id="minor_class" name="minor_class">
							<?php foreach($minor_classes as $row): ?>
								<?php if(set_value('minor_class', $item_data['min_class_id']) == $row['min_class_id']): ?>
									<option selected value="<?php echo $row['min_class_id']; ?>" ><?php echo $row['min_class_name']; ?> [<?php echo $row['min_class_id']; ?>]</option>
								<?php else: ?>
									<option value="<?php echo $row['min_class_id']; ?>" ><?php echo $row['min_class_name']; ?> [<?php echo $row['min_class_id']; ?>]</option>
								<?php endif; ?>							
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class='title'>Suffix:</td>
					<td>
						<input id='suffix' name='suffix' type='text' value='<?php echo set_value('suffix', $item_data['suffix']); ?>' />
						<?php if($item_data['suffix'] == ''): ?>
							[<a id='makeSuffix' href='#'>Make a Suffix</a>]
						<?php else: ?>
							[<a id='getNextSuffix' href='#'>Get Next Suffix</a>]
						<?php endif;?>
						
						
					</td>
				</tr>
				<tr>
					<td class='title' >Name:</td>
					<td colspan="3"><?php echo $item_data['item_name']; ?></td>
				</tr>
				<tr>
					<td class='form_errors' colspan="4"><div class='delete_admin_item' style='display: none;'><?php echo validation_errors();  ?></div></td>
				</tr>
				<tr>
					<td colspan="4" align='center'>
						<input type="submit" value="Submit Changes" />
						
					</td>
				</tr>
			</table>
			
			<?php echo form_close(); ?>
		</div>
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>