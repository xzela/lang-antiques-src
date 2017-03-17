<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Edit Opal</title>
	<style type="text/css">
	</style>
	<script type='text/javascript'>
		var base_url = <?php echo '"' . base_url() . '"'; ?>;
		var item_id = <?php echo $item_data['item_id']; ?>;
		var jadeite_id = <?php echo $opal_data['o_id']; ?>;
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
					//	div.slideDown('slow');
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
		<h2>Inventory - Edit Opal</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/opal/' . $item_data['item_id']. '/add', snappy_image('icons/opal.png') . ' Add Another Opal'); ?></li>
		</ul>
		<div>
			<h3>Update this Opal:</h3>
			<?php echo form_open('inventory/opal/' . $item_data['item_id'] .'/edit/' . $opal_data['o_id'], "id='gemstone_add_form'"); ?>
			<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
			<?php echo validation_errors(); ?>
			
			<table class='form_table'>
				<tr>
					<td class='title large'><span class='warning'>*</span>Opal Type:</td>
					<td colspan='3' class='large'>
						<select class='input_field' name='o_type_id'>
							<?php foreach($gemstone_names as $gem):?>
								<?php if(set_value('o_type_id') == $gem['stone_id']):?>
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
						<input type='text' class='input_field' name='o_quantity' value='<?php echo set_value('o_quantity', $opal_data['o_quantity']); ?>' />
					</td>
					<td class='title'><span class='warning'>*</span>Carat:</td>
					<td>
						<input type='text' class='input_field' name='o_carat' value='<?php echo set_value('o_carat', $opal_data['o_carat']); ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Cut/Shape:</td>
					<td>
						<select name='o_cut_id' class='input_field'>
							<option value='0'></option>
							<?php foreach($gemstone_cuts as $cut): ?>
								<?php if(set_value('o_cut_id', $opal_data['o_cut_id']) == $cut['cut_id']): ?>
									<option value='<?php echo $cut['cut_id']; ?>' selected><?php echo $cut['cut_name']; ?></option>
								<?php else: ?>
									<option value='<?php echo $cut['cut_id']; ?>'><?php echo $cut['cut_name']; ?></option>
								<?php endif;?>
							<?php endforeach;?>
						</select>					
					</td>
					<td class='title'>Body Color:</td>
					<td>
						<input type='text' class='input_field' name='o_color' value='<?php echo set_value('o_color', $opal_data['o_color']); ?>' />					
					</td>
				</tr>
				<tr>
					<td class='title'>Center Stone?:</td>
					<td>
						<select id='is_center' name='is_center' class='input_field'>
							<?php if($opal_data['is_center'] == 0): ?>
								<option selected value='0'>No</option>
								<option value='1'>Yes</option>
							<?php else: ?>
								<option value='0'>No</option>
								<option selected value='1'>Yes</option>								
							<?php endif;?>
						</select>
					</td>
				</tr>				
				<tr>
					<td class='title'>Dimensions:</td>
					<td colspan='3'>
						<div style="padding-bottom: 5px;">
							<b>Ranged?:</b>
							<select id='is_ranged' name='is_ranged' class='input_field'>
								<?php if(set_value('is_ranged', $opal_data['is_ranged'] == 1)): ?>
									<option value='1' selected>Yes</option>
									<option value='0'>No</option>
								<?php else: ?>
									<option value='1'>Yes</option>
									<option value='0' selected>No</option>
								<?php endif;?>
							</select>
						</div>
						<div id='span_square'>
							<input class='input_field' name='o_x1' type='text' size='10' value="<?php echo set_value('o_x1', $opal_data['o_x1']); ?>" />
							x
							<input class='input_field' name='o_x2' type='text' size='10' value="<?php echo set_value('o_x2', $opal_data['o_x2']); ?>" />
							x
							<input class='input_field' name='o_x3' type='text' size='10' value="<?php echo set_value('o_x3', $opal_data['o_x3']); ?>" />
							mm
						</div>
						<div id='span_range' style='display: none;'>
							<input class='input_field' name='o_ranged_x1' type='text' size='10' value="<?php echo set_value('o_x1', $opal_data['o_x1']); ?>" />
							-
							<input class='input_field' name='o_ranged_x2' type='text' size='10' value="<?php echo set_value('o_x2', $opal_data['o_x2']); ?>" />
							mm
						</div>					
					</td>
				</tr>
				<tr>
					<td class='title'>Transparency:</td>
					<td>
						<input type='text' class='input_field' name='o_trans' value='<?php echo set_value('o_trans', $opal_data['o_trans']); ?>' />					
					</td>
					<td class='title'>Hue Intensity:</td>
					<td>
						<input type='text' class='input_field' name='o_hue_inten' value='<?php echo set_value('o_hue_inten', $opal_data['o_hue_inten']); ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Primary Hue:</td>
					<td>
						<input type='text' class='input_field' name='o_prim_hue' value='<?php echo set_value('o_prim_hue', $opal_data['o_prim_hue']); ?>' />
					</td>
					<td class='title'>Secondary Hue:</td>
					<td>
						<input type='text' class='input_field' name='o_secon_hue' value='<?php echo set_value('o_secon_hue', $opal_data['o_secon_hue']); ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Saturation:</td>
					<td>
						<input type='text' class='input_field' name='o_satur' value='<?php echo set_value('o_satur', $opal_data['o_satur']); ?>' />
					</td>
					<td class='title'>Hue Pattern:</td>
					<td>
						<input type='text' class='input_field' name='o_pattern' value='<?php echo set_value('o_pattern', $opal_data['o_pattern']); ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Notes:</td>
					<td colspan="3">
						<textarea class='input_field' name='o_notes' cols='60' rows='4' ><?php echo set_value('o_notes', $opal_data['o_notes']); ?></textarea>
					</td>
				</tr>
				<tr>
					<td class='form_errors' colspan="4" align='center'><?php echo validation_errors();  ?></td>
				</tr>
				<tr >
					<td colspan="4" align="center"><input type="submit" value="Save This Opal"  /></td>
				</tr>
			</table>
			<?php echo form_close(); ?>
		</div>
		<h3>Or... Remove This Opal</h3>
		<div style='padding: 10px; margin: 10px; border: 1px solid #999; width: auto; background-color: #e9e9e9'>
			<?php echo form_open('inventory/opal/' . $item_data['item_id'] . '/remove/' . $opal_data['o_id'])?>
				<input type="submit" class='warning' value="Remove This Opal" /> <span class='warning'>This will remove the gemstone from the item forever. If you make a mistake, you'll have to reenter the information. Click at ones own risk.</span>
			<?php echo form_close();?>
		</div>
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>