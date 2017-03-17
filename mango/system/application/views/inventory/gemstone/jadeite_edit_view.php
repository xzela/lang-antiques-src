<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Edit Jadeite</title>
	
	<script type='text/javascript'>
		var base_url = <?php echo '"' . base_url() . '"'; ?>;
		var item_id = <?php echo $item_data['item_id']; ?>;
		var jadeite_id = <?php echo $jadeite_data['j_id']; ?>;
		var json = {"fields" : {}};
	
		$(document).ready(function() {
			var content = $('.input_field').val();
			$.each($('.input_field'), function(i, val) {
				json.fields[i] = {"name" : this.name, "value" : this.value};
			});
	
			$('.input_field').bind('keyup change', function(event) {
				var index = $('.input_field').index(this);
				var content = json.fields[index].value;
	
				var div = $('#change_message');
				if($(this).val() != content) {
					$(this).css('color', 'red');
					$(this).css('border', '1px solid red');
					//if(div.is(':hidden')) {
						//div.slideDown('slow');
					//}
					setConfirmUnload(true);
				}
				else {
					$(this).css('color', 'black');
					$(this).css('border', '1px solid #333333');
					var b = true;
					$.each($('.input_field'), function(i, val) {
						if(this.value != json.fields[i].value) {
							b = false;
						}
					});
					if(b) {
						//div.slideUp('slow');
						setConfirmUnload(false);
					}
				}	
			});
			
			$('#is_ranged').bind('change', function() {
				if($(this).val() == '1') {
					$('#span_range').slideDown('slow');
					$('#span_square').slideUp('slow');
				}
				else {
					$('#span_range').slideUp('slow');
					$('#span_square').slideDown('slow');
				}
			});
			$('#is_ranged').trigger('change');

			$('form').submit(function() {
				setConfirmUnload(false);
			});

		});

		function setConfirmUnload(on) {
			window.onbeforeunload = (on) ? unloadMessage : null;
		}
		function unloadMessage() {
			return 'You have made changes to this record. If you navigate away from this page without first saving your data, the changes will be lost.';
		}
		
	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Inventory - Edit Jadeite</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/jadeite/' . $item_data['item_id'] . '/add', snappy_image('icons/jadeite.png') . ' Add Another Jadeite', 'class="green"')?></li>
		</ul>
		<div>
			<h3>Select a Jadeite:</h3>
			<?php echo form_open('inventory/jadeite/' . $item_data['item_id'] . '/edit/' . $jadeite_data['j_id'], "id='jadeite_add_form'"); ?>
			<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
			<?php echo validation_errors(); ?>
			<table class='form_table'>
				<tr>
					<td class='title large'><span class='warning'>*</span>Jadeite Type:</td>
					<td colspan='3'>
						<select class='input_field' name='j_type_id'>
							<?php foreach($gemstone_names as $gem):?>
								<?php if(set_value('j_type_id', $jadeite_data['j_type_id']) == $gem['stone_id']):?>
									<option value='<?php echo $gem['stone_id']; ?>' selected> <?php echo $gem['stone_name']; ?> </option>
								<?php else: ?>
									<option value='<?php echo $gem['stone_id']; ?>'> <?php echo $gem['stone_name']; ?> </option>
								<?php endif;?>
							<?php endforeach;?> 
						</select>
						<input class='large' type="submit" value="Save Changes" />
					</td>
				</tr>
				<tr>
					<td class='title'><span class='warning'>*</span>Quantity:</td>
					<td>
						<input type='text' class='input_field' name='j_quantity' value='<?php echo set_value('j_quantity', $jadeite_data['j_quantity'])?>' />
					</td>
					<td class='title'><span class='warning'>*</span>Carat:</td>
					<td>
						<input type='text' class='input_field' name='j_carat' value='<?php echo set_value('j_carat', $jadeite_data['j_carat'])?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Cut:</td>
					<td>
						<input type='text' class='input_field' name='j_cut' value='<?php echo set_value('j_cut', $jadeite_data['j_cut'])?>' />
					</td>
					<td class='title'>Cut Grade:</td>
					<td>
						<input type='text' class='input_field' name='j_cut_grade' value='<?php echo set_value('j_cut_grade', $jadeite_data['j_cut_grade'])?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Center Stone?:</td>
					<td>
						<select id='is_center' name='is_center' class='input_field'>
							<?php if(set_value('is_center', $jadeite_data['is_center']) == 0): ?>
								<option selected value='0'>No</option>
								<option value='1'>Yes</option>
							<?php else: ?>
								<option value='0'>No</option>
								<option selected value='1'>Yes</option>								
							<?php endif;?>
						</select>
					</td>
					<td class='title'>Style:</td>
					<td>
						<input type='text' class='input_field' name='j_style_id' value='<?php echo set_value('j_style_id', $jadeite_data['j_style_id'])?>' />
					</td>
				</tr>
				<tr>		
					<td class='title'>Dimensions:</td>
					<td colspan='3'>
						Ranged?:
						<div style="padding: 5px;">
							<select id='is_ranged' name='is_ranged' class='input_field'>
								<?php if(set_value('is_ranged', $gemstone_data['is_ranged']) == 1): ?>
									<option value='1' selected>Yes</option>
									<option value='0'>No</option>
								<?php else: ?>
									<option value='1'>Yes</option>
									<option value='0' selected>No</option>
								<?php endif;?>
							</select>
						</div>	
						<div id='span_square'>
							<input id="j_x1" name='j_x1' class='input_field' type='text' size='10' value="<?php echo set_value('j_x1' ,$jadeite_data['j_x1']); ?>" />
							x
							<input id="j_x2" name='j_x2' class='input_field' type='text' size='10' value="<?php echo set_value('j_x2' ,$jadeite_data['j_x2']); ?>" />
							x
							<input id="j_x3" name='j_x3' class='input_field' type='text' size='10' value="<?php echo set_value('j_x3' ,$jadeite_data['j_x3']); ?>" />
							mm
						</div>
						<div id='span_range' style='display: none;'>
							<input id='j_range_x1' name='j_range_x1' class='input_field' type='text' size='10' value="<?php echo set_value('j_x1' ,$jadeite_data['j_x1']); ?>" />
							-
							<input id='j_range_x2' name='j_range_x2' class='input_field' type='text' size='10' value="<?php echo set_value('j_x2', $jadeite_data['j_x2']); ?>" />
							mm
						</div>
					</td>
				</tr>
				<tr>
					<td colspan='4' style='border-top: 1px solid #c9c9c9;'>Incandescent Light Source:</td>
				</tr>
				<tr>
					<td class='title'>Hue:</td>
					<td>
						<input type='text' class='input_field' name='ils_hue' value='<?php echo set_value('ils_hue', $jadeite_data['ils_hue'])?>' />
					</td>
					<td class='title'>Tone:</td>
					<td>
						<input type='text' class='input_field' name='ils_tone' value='<?php echo set_value('ils_tone', $jadeite_data['ils_tone'])?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Intensity:</td>
					<td>
						<input type='text' class='input_field' name='ils_inten' value='<?php echo set_value('ils_inten', $jadeite_data['ils_inten'])?>' />
					</td>
				</tr>
				<tr>
					<td colspan='4' style='border-top: 1px solid #c9c9c9;'>Fluorescent Light Source:</td>
				</tr>				
				<tr>
					<td class='title'>Hue:</td>
					<td>
						<input type='text' class='input_field' name='fls_hue' value='<?php echo set_value('fls_hue', $jadeite_data['fls_hue'])?>' />
					</td>
					<td class='title'>Tone:</td>
					<td>
						<input type='text' class='input_field' name='fls_tone' value='<?php echo set_value('fls_tone', $jadeite_data['fls_tone'])?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Itensity:</td>
					<td>
						<input type='text' class='input_field' name='fls_inten' value='<?php echo set_value('fls_inten', $jadeite_data['fls_inten'])?>' />
					</td>
				</tr>
				<tr>
					<td colspan='4' style='border-top: 1px solid #c9c9c9;'>Additional Jadeite Information</td>
				</tr>				
				<tr>
					<td class='title'>Clarity:</td>
					<td>
						<input type='text' class='input_field' name='j_clarity' value='<?php echo set_value('j_clarity', $jadeite_data['j_clarity'])?>' />
					</td>
					<td class='title'>Brilliancy:</td>
					<td>
						<input type='text' class='input_field' name='j_brill' value='<?php echo set_value('j_brill', $jadeite_data['j_brill'])?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Proportions:</td>
					<td>
						<input type='text' class='input_field' name='j_prop' value='<?php echo set_value('j_prop', $jadeite_data['j_prop'])?>' />
					</td>
					<td class='title'>Finish:</td>
					<td>
						<input type='text' class='input_field' name='j_finish' value='<?php echo set_value('j_finish', $jadeite_data['j_finish'])?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Jade Dialogue Number:</td>
					<td>
						<input type='text' class='input_field' name='j_dialogue_number' value='<?php echo set_value('j_dialogue_number', $jadeite_data['j_dialogue_number'])?>' />
					</td>
				</tr>				
				<tr>
					<td class='title'>Notes:</td>
					<td colspan="3">
						<textarea class='input_field' name='j_notes' cols='40' rows='4' ><?php echo set_value('j_notes', $jadeite_data['j_notes'])?></textarea>
					</td>
				</tr>
				<tr>
					<td class='form_errors' colspan="4" align='center'><?php echo validation_errors();  ?></td>
				</tr>
				<tr >
					<td colspan="4" align="center"><input type="submit" value="Save This Jadeite"  /></td>
				</tr>
			</table>
			<?php echo form_close(); ?>
		</div>
		<h3>Or... Remove This Gemstone</h3>
		<div style='padding: 10px; margin: 10px; border: 1px solid #999; width: auto; background-color: #e9e9e9'>
			<?php echo form_open('inventory/jadeite/' . $item_data['item_id'] . '/remove/' . $jadeite_data['j_id'])?>
				<input type="submit" class='warning' value="Remove This Jadeite" /> <span class='warning'>This will remove the gemstone from the item forever. If you make a mistake, you'll have to reenter the information. Click at ones own risk.</span>
			<?php echo form_close();?>
		</div>		
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>