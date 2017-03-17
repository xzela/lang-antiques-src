<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Add Sub Menu Element to <?php echo $element_data['element_name']; ?></title>

	<script type="text/javascript">
	var url = <?php echo '"' . base_url() . '"'?>;
	
	$(document).ready(function () {
		$('#element_type').bind('change', function() {
			if($(this).val() == 2) {
				$('#modifiers').show('slow');
				$('#element_url_input1').val('products/type/');
				$('#url_segment_01').html($('#element_url_input1').val());
				var s = new String($('#top_modifier').find('option:selected').text());
				var re = s.replace(/[^a-zA-Z0-9]+/ig, '-').toLowerCase();
				$('#url_segment_02').html(re);
				$('#element_url_input2').val(re);
			}
			else {
				$('#modifiers').hide('slow');
			}

			if($(this).val() == 3) {
				$('#specials').show('slow');
				$('#element_url_input1').val('');
				$('#url_segment_01').html('');
				$('#url_segment_02').html('');
				$('#element_url_input2').val('');
				$('#special_url').show('slow');
			}
			else {
				$('#specials').hide('slow');
				$('#special_url').hide('slow');
				$('#url_path').val('');
				$('#url_argument').val('');
				
			}

			if($(this).val() == 0) {
				$('#element_url_input1').val('');
				$('#url_segment_01').html('');
				$('#url_segment_02').html('');
				$('#element_url_input2').val('');
			}
		});

		$('#top_modifier').bind('change', function() {
			$('#element_url_input2').val($(this).find('option:selected').text());
			//get modifer url
			$.post(url + 'website/AJAX_getModifierURLName',
				{
					modifier_id: $(this).val()
				},
				function(data) {
					$('#url_segment_02').html(data);
					$('#element_url_input2').val(data);
				}
			);
		}); 

		$('#url_path').bind('keyup', function() {
			$('#element_url_input1').val($(this).val()); 
		});
		$('#url_argument').bind('keyup', function() {
			$('#element_url_input2').val($(this).val()); 
		});
		
		
		$('#element_type').trigger('change');
	});

	</script>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class='item'>Admin - Add Sub Menu Element to <?php echo $element_data['element_name']; ?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin/menu_edit/' . $element_data['element_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Menu Element'); ?></li>
			<li>|</li>
		</ul>
		<p>You can Add a new Sub Menu Element here:</p>
		<?php echo form_open('admin/menu_sub_add/' . $element_data['element_id']);?>
		<table class='form_table'>
			<tr>
				<td class='title'>Sub Element Type: </td>
				<td>
					<?php $element_types = array(0=>'', 2=>'Modifier', 3=>'Special Element'); ?>
					<?php echo form_dropdown('sub_element_type', $element_types, set_value('sub_element_type'), 'id="element_type"'); ?>
				</td>
			</tr>
			<tr>
				<td class='title'>Sub Element Name: </td>
				<td>
					<div id='modifiers' style='display: none;'>
						<select id='top_modifier' name='top_modifier'>
							<option></option>
							<?php foreach($modifiers as $mod): ?>
								<?php if($mod['modifier_id'] == $this->input->post('top_modifier')): ?>
									<option value='<?php echo $mod['modifier_id']?>' selected><?php echo $mod['modifier_name']; ?></option>
								<?php else: ?>
									<option value='<?php echo $mod['modifier_id']?>'><?php echo $mod['modifier_name']; ?></option>
								<?php endif;?>
									
							<?php endforeach;?>
						</select>
						<div>Looking for a specific Modifier? Make sure it has a modifier 'element url name' </div>
					</div>
					<div id='specials' style='display: none;'>
						<input name='sub_special_name' type='text' value='<?php echo set_value('sub_special_name')?>' />
						<strong>Sub Element Title:</strong> <input name='sub_special_title' type='text' value='<?php echo set_value('sub_special_title');?>' />
						<input name='element_type_id' type='hidden' value='0' />
					</div>
				</td>
			</tr>
			<tr>
				<td class='title'>Sub Sequence:</td>
				<td>
					<input name='sub_element_seq' type='text' value='<?php echo set_value('sub_element_seq', 99); ?>' />
				</td>
			</tr>
			<tr>
				<td class='title'>Sub Element URL:</td>
				<td>
					<div id='special_url' style='display: none;'>
						<strong>Path: </strong><input id='url_path' name='url_path' type='text' value='<?php echo set_value('url_path'); ?>' /> 
						<strong>Argument: </strong><input id='url_argument' name='url_argument' type='text' value='<?php echo set_value('url_argument'); ?>' /> <br />
						<div class='warning'>!!!Are you sure you know what you are doing?</div>
						<div style='vertical-align: top;'>
							<strong>Meta Description:</strong>
							<br />
							<textarea name='sub_meta_description' rows='2' cols='40'><?php echo set_value('sub_meta_description'); ?></textarea>
						</div>
						<div style='vertical-align: top;'>
							<strong>Page Paragraph:</strong>
							<br />
							<textarea name='sub_page_paragraph' rows='4' cols='40'><?php echo set_value('sub_page_paragraph'); ?></textarea>
						</div>						
					</div>
					<span id='url_segment_01'></span><span id='url_segment_02'></span>
					<input id='element_url_input1'  name='element_url_input1' type='hidden' value='<?php echo set_value('element_url_input1'); ?>' />
					<input id='element_url_input2'  name='element_url_input2' type='hidden' value='<?php echo set_value('element_url_input2'); ?>' />
				</td>
			</tr>
			<tr>
				<td colspan='2'><?php echo validation_errors(); ?></td>
			</tr>
			<tr>
				<td class='title'></td>
				<td><input type='submit' value='Add Sub Menu Element' /></td>
			</tr>			
		</table>
		<?php echo form_close(); ?>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>