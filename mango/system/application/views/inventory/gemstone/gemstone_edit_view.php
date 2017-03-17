<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
		
	<title><?php echo $this->config->item('project_name'); ?> - Edit Gemstone</title>
	
	<script type='text/javascript'>
		var base_url = <?php echo '"' . base_url() . '"'; ?>;
		var item_id = <?php echo $item_data['item_id']; ?>;
		var gemstone_id = <?php echo $gemstone_data['gem_id']; ?>;
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
		<h2>Inventory - Add Gemstone</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/gemstone/' . $item_data['item_id'] . '/add', snappy_image('icons/ruby.png') . ' Add Another Gemstone', 'class="green"')?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/diamond/' . $item_data['item_id'] . '/add', snappy_image('icons/diamond.png') . 'Add Another Diamonds', 'class="green"');?></li>
		</ul>
		<div>
			<h3>Edit This Gemstone:</h3>
			<?php echo form_open('inventory/gemstone/' . $item_data['item_id'] . '/edit/' . $gemstone_data['gem_id'], "id='gemstone_add_form'"); ?>
			<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
			<?php echo validation_errors(); ?>
			<table class='form_table'>
				<tr>
					<td class='title large'><span class='warning'>*</span>Gemstone:</td>
					<td class='large' colspan='3'>
						<select class='input_field' name='gem_type_id'>
							<?php foreach($gemstone_names as $gem):?>
								<?php if(set_value('gem_type_id', $gemstone_data['gem_type_id']) == $gem['stone_id']):?>
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
						<input class='input_field' type='text' name='gem_quantity' value='<?php echo set_value('gem_quantity', $gemstone_data['gem_quantity']); ?>' /> 
					</td>
					<td class='title'>Weight:</td>
					<td>
						<input class='input_field' type='text' name='gem_carat' value='<?php echo set_value('gem_carat', $gemstone_data['gem_carat']); ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Cut/Shape:</td>
					<td>
						<select id='gem_cut_id' class='input_field' name='gem_cut_id'>
							<option value='0'></option>
							<?php foreach($gemstone_cuts as $cut):?>
								<?php if($gemstone_data['gem_cut_id'] == $cut['cut_id']):?>
									<option selected value='<?php echo $cut['cut_id']?>'> <?php echo $cut['cut_name']; ?></option>
								<?php else: ?>
									<option value='<?php echo $cut['cut_id']?>' > <?php echo $cut['cut_name']; ?></option>
								<?php endif;?>
							<?php endforeach;?>
						</select>					
					</td>
					<td class='title'>Cut Grade:</td>
					<td>
						<input class='input_field' type='text' name='gem_cut_grade' value='<?php echo set_value('gem_cut_grade', $gemstone_data['gem_cut_grade']); ?>' />
					</td>			
				</tr>
				<tr>
					<td class='title'>Center Stone?:</td>
					<td>
						<select id='is_center' name='is_center' class='input_field'>
							<?php if($gemstone_data['is_center'] == 0): ?>
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
						Ranged?:
						<div style="padding: 5px;">
							<select id='is_ranged' name='is_ranged' class='input_field'>
								<?php if(set_value('is_ranged', $gemstone_data['is_ranged'] == 1)): ?>
									<option value='1' selected>Yes</option>
									<option value='0'>No</option>
								<?php else: ?>
									<option value='1'>Yes</option>
									<option value='0' selected>No</option>
								<?php endif;?>
							</select>
						</div>	
						<div id='span_square'>
							<input id='gem_x1' name='gem_x1' class='input_field' type='text' size='10' value="<?php echo $gemstone_data['gem_x1']; ?>" />
							x
							<input id='gem_x2' name='gem_x2' class='input_field' type='text' size='10' value="<?php echo $gemstone_data['gem_x2']; ?>" />
							x
							<input id='gem_x3' name='gem_x3' class='input_field' type='text' size='10' value="<?php echo $gemstone_data['gem_x3']; ?>" />
							mm
						</div>
						<div id='span_range' style='display: none;'>
							<input id='gem_range_x1' name='gem_range_x1' class='input_field' type='text' size='10' value="<?php echo $gemstone_data['gem_x1']; ?>" />
							-
							<input id='gem_range_x2' name='gem_range_x2' class='input_field' type='text' size='10' value="<?php echo $gemstone_data['gem_x2']; ?>" />
							mm
						</div>
						
					</td>
				</tr>
				<tr>
					<td class='title'>Hue:</td>
					<td>
						<input class='input_field' type='text' name='gem_hue' value='<?php echo set_value('gem_hue', $gemstone_data['gem_hue']); ?>' />
					</td>
					<td class='title'>Tone:</td>
					<td>
						<input class='input_field' type='text' name='gem_tone' value='<?php echo set_value('gem_tone', $gemstone_data['gem_tone']); ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Clarity:</td>
					<td>
						<input class='input_field' type='text' name='gem_clarity' value='<?php echo set_value('gem_clarity', $gemstone_data['gem_clarity']); ?>' />
					</td>
					<td class='title'>Brilliancy:</td>
					<td>
						<input class='input_field' type='text' name='gem_brill' value='<?php echo set_value('gem_brill', $gemstone_data['gem_brill']); ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Intensity:</td>
					<td>
						<input class='input_field' type='text' name='gem_intent' value='<?php echo set_value('gem_intent', $gemstone_data['gem_intent']); ?>' />
					</td>
					<td class='title'>Proportions:</td>
					<td>
						<input class='input_field' type='text' name='gem_prop' value='<?php echo set_value('gem_prop', $gemstone_data['gem_prop']); ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Finish:</td>
					<td>
						<input class='input_field' type='text' name='gem_finish' value='<?php echo set_value('gem_finish', $gemstone_data['gem_finish']); ?>' />
					</td>
					<td class='title'>Stone Dialogue #:</td>
					<td>
						<input class='input_field' type='text' name='gem_dialogue_number' value='<?php echo set_value('gem_dialogue_number', $gemstone_data['gem_dialogue_number']); ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Country of Origin</td>
					<td>
						<input type='text' class='input_field' name='country_origin' value='<?php echo set_value('country_origin', $gemstone_data['country_origin']); ?>' />
					</td>
					<td class='title'>Phenomenon:</td>
					<td>
						<input class='input_field' type='text' name='gem_phenomenon' value='<?php echo set_value('gem_phenomenon', $gemstone_data['gem_phenomenon']); ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Certified By:</td>
					<td>
						<input class='input_field' type='text' name='gem_cert_by' value='<?php echo set_value('gem_cert_by', $gemstone_data['gem_cert_by']); ?>' />
					</td>
					<td class='title'>Certification Date:</td>
					<td>
						<input class='input_field' type='text' name='gem_cert_date' value='<?php echo set_value('gem_cert_date', $gemstone_data['gem_cert_date']); ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Certification #:</td>
					<td>
						<input class='input_field' type='text' name='gem_cert_number' size='40' value='<?php echo set_value('gem_cert_number', $gemstone_data['gem_cert_number']); ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Certification Notes:</td>
					<td colspan='3'>
						<input class='input_field' type='text' name='gem_cert_notes' size='79' value='<?php echo set_value('gem_cert_notes', $gemstone_data['gem_cert_notes']); ?>' />
					</td>
				</tr>
				<tr>
					<td class='title' >Notes:</td>
					<td colspan='3'>
						<textarea class='input_field'name='gem_notes' rows='4' cols='60' ><?php echo set_value('gem_notes', $gemstone_data['gem_notes']); ?></textarea>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input class='large' type="submit" value="Save Changes" />
					</td>
				</tr>
			</table>
			<?php echo form_close();?>
		</div>
		
		<h3>Or... Remove This Gemstone</h3>
		<div style='padding: 10px; margin: 10px; border: 1px solid #999; width: auto; background-color: #e9e9e9'>
			<?php echo form_open('inventory/gemstone/' . $item_data['item_id'] . '/remove/' . $gemstone_data['gem_id'])?>
				<input type="submit" class='warning' value="Remove This Gemstone" /> <span class='warning'>This will remove the gemstone from the item forever. If you make a mistake, you'll have to reenter the information. Click at ones own risk.</span>
			<?php echo form_close();?>
		</div>
		
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>