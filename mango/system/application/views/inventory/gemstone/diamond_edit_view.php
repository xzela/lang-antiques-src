<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>	
	
		
	<title><?php echo $this->config->item('project_name'); ?> - Edit Diamond</title>

	<script type='text/javascript'>
		var base_url = <?php echo '"' . base_url() . '"'; ?>;
		var item_id = <?php echo $item_data['item_id']; ?>;
		var diamond_id = <?php echo $diamond_data['d_id']; ?>;
		var json = {"fields" : {}};

		$(document).ready(function() {
			var content = $('.input_field').val();
			$.each($('.input_field'), function(i, val) {
				json.fields[i] = {"name" : this.name, "value" : this.value};
			}); 
			
			$('.input_field').bind('keyup change', function(event) {
				var index = $('.input_field').index(this);
				var content = json.fields[index].value;
				
				//alert(content + '=' + $(this).val());
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

			$('.color_input').bind('click', function() {
				if($(this).is(':checked')) {
					$('#ctext_' + $(this).attr('id')).addClass('weighted');
					//send add color ajax_request
					$.post(base_url + 'gemstone/AJAX_diamondColor/', {
							id: item_id,
							diamond: diamond_id,
							color: $(this).attr('id'),
							action: 1
						});
				}
				else {
					$('#ctext_' + $(this).attr('id')).removeClass('weighted');
					//send remove color ajax_request
					$.post(base_url + 'gemstone/AJAX_diamondColor/', {
						id: item_id,
						diamond: diamond_id,
						color: $(this).attr('id'),
						action: 0
					});
				}

				if($(this).attr('id') == '24') {
					if($('#other_color_main_div1').is(':hidden')) {
						$('#other_color_main_div1').slideDown('slow');
						$('#other_color_main_div2').slideDown('slow');
					}
					else {
						$('#other_color_main_div1').slideUp('slow');
						$('#other_color_main_div2').slideUp('slow');
					}
				}
			});
			if($('#24').is(':checked')) {
				$('#other_color_main_div1').slideDown('slow');
				$('#other_color_main_div2').slideDown('slow');
			}

			$('.clarity_input').bind('click', function(){
				if($(this).is(':checked')) {
					$('#ltext_' + $(this).attr('id')).addClass('weighted');
					//send add color ajax_request
					$.post(base_url + 'gemstone/AJAX_diamondClarity/', {
							id: item_id,
							diamond: diamond_id,
							clarity: $(this).attr('id'),
							action: 1
						});					
				}
				else {
					$('#ltext_' + $(this).attr('id')).removeClass('weighted');
					//send add color ajax_request
					$.post(base_url + 'gemstone/AJAX_diamondClarity/', {
							id: item_id,
							diamond: diamond_id,
							clarity: $(this).attr('id'),
							action: 0
						});							
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
	
	<style type='text/css'>
		.color_text {
			width: 30px; 
			text-align: left;
		}
		.clarity_input {
			width: 35px; 
			text-align: left; 
		}
		
		.weighted {
			font-weight: bold;
		}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
	
?>
	<div id="content">
		<h2>Inventory - Edit Diamond</h2>
		<ul id='submenu'>
			<?php $u = referrer_segment_array(); ?>
			<?php if(!empty($u) && $u[1] == 'appraisal'): ?>
				<li><?php echo anchor(referrer_uri_string(), snappy_image('icons/resultset_previous.png', '', 'pagination_image'). 'Back to Appraisal'); ?></li>
				<li>|</li>
			<?php endif;?>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/diamond/' . $item_data['item_id'] . '/add', snappy_image('icons/diamond.png') . 'Add Another Diamonds', 'class="green"');?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/gemstone/' . $item_data['item_id'] . '/add', snappy_image('icons/ruby.png') . 'Add Another Gemstone', 'class="green"');?></li>
		</ul>
		<div>
			<h3>Select a Diamond:</h3>
			<?php echo form_open('inventory/diamond/' . $item_data['item_id'] . '/edit/' . $diamond_data['d_id'], "id='gemstone_add_form'"); ?>
			<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
			<table class='form_table'>
				<tr>
					<td class='title large'>Diamond:</td>
					<td colspan='3'>
						<select class='large' class='input_field' name='gemstone_id'>
							<?php foreach($gemstone_names as $gem):?>
								<?php if(set_value('d_type_id') == $gem['stone_id']):?>
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
					<td class='title'>Quantity:</td>
					<td>
						<input type='text' name='d_quantity' class='input_field' value='<?php echo set_value('d_quantity', $diamond_data['d_quantity']); ?>' />
					</td>
					<td class='title'>Carats:</td>
					<td>
						<input type='text' name='d_carats' class='input_field' value='<?php echo set_value('d_carats', $diamond_data['d_carats']); ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Color:</td>
					<td colspan='3'>
						<?php foreach($diamond_colors as $color):?>
							<div style='float: left;'>
								<div style='width: 30px; text-align: left'>
									<?php if(in_array($color['color_id'], $selected_colors)): ?>
										<div id='ctext_<?php echo $color['color_id']; ?>' class='color_text weighted' ><?php echo $color['color_abrv']; ?></div> 									
										<input id='<?php echo $color['color_id']; ?>' class='color_input' type='checkbox' value='<?php echo $color['color_id']; ?>' checked />
									<?php else: ?>
										<div id='ctext_<?php echo $color['color_id']; ?>' class='color_text'><?php echo $color['color_abrv']; ?></div> 									
										<input id='<?php echo $color['color_id']; ?>' class='color_input' type='checkbox' value='<?php echo $color['color_id']; ?>' />									
									<?php endif;?>					
								</div>
							</div>
						<?php endforeach;?>
						<!-- <div id='other_color_main_div' style='display: none; clear: left;'>
							<strong>Other Color:</strong> <input type='text' name='other_color' class='input_field' value='<?php echo $diamond_data['other_color']; ?>' />
						</div> -->
					</td>
				</tr>
				<tr>
					<td class='title' >
						<div id='other_color_main_div1' style='display: none;'>
							<strong>Other Color:</strong>
						</div>
					</td>
					<td>
						<div id='other_color_main_div2' style='display: none;'>
							<input type='text' name='other_color' class='input_field' value='<?php echo $diamond_data['other_color']; ?>' />
						</div>
					</td>
				</tr>
				
				<tr>
					<td class='title'>Average Color:</td>
					<td>
						<input class='input_field' type='text' name='average_color' value='<?php echo $diamond_data['average_color']; ?>' />
						<span style='color: #a1a1a1;'>Leave blank for no average</span>
					</td> 
				</tr>
				<tr>
					<td class='title'>Clarity:</td>
					<td colspan='3'>
						<?php foreach($diamond_clarities as $clarity):?>
							<div style='float: left;'>
								<div>
									<?php if(in_array($clarity['clarity_id'], $selected_clarities)): ?>
										<div id='ltext_<?php echo $clarity['clarity_id']; ?>' class='color_field weighted' > <?php echo $clarity['clarity_abrv']; ?></div> 
										<input id='<?php echo $clarity['clarity_id']; ?>' class='clarity_input' type='checkbox' value='<?php echo $clarity['clarity_id']; ?>' checked/>
									<?php else :?>
										<div id='ltext_<?php echo $clarity['clarity_id']; ?>' class='color_field'> 
											<?php echo $clarity['clarity_abrv']; ?>
										</div> 
										<input id='<?php echo $clarity['clarity_id']; ?>' class='clarity_input' type='checkbox' value='<?php echo $clarity['clarity_id']; ?>' />
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach;?>
					</td>
				</tr>
				<tr>
					<td class='title'>Average Clarity:</td>
					<td>
						<input class='input_field' type='text' name='average_clarity' value='<?php echo $diamond_data['average_clarity']; ?>' />
						<span style='color: #a1a1a1;'>Leave blank for no average</span>
					</td> 
				</tr>				
				<tr>
					<td class='title'>Cut/Shape:</td>
					<td>
						<select name='diamond_cut_id' class='input_field' >
							<option value='0'></option>
							<?php $p = array(); ?>
							<?php $n = array(); ?>
							<?php foreach($gemstone_cuts as $cut): ?>
								<?php if($cut['seq'] != 99): ?>
									<?php $p[] = $cut;?>
								<?php else: ?>
									<?php $n[] = $cut; ?>
								<?php endif;?>
							<?php endforeach; ?>
							
							<optgroup label='Most used:'>
								<?php foreach($p as $cut):?>
									<?php if($cut['cut_id'] == set_value('diamond_cut',$diamond_data['d_cut_id'])): ?>
										<option value='<?php echo $cut['cut_id']; ?>' selected><?php echo $cut['cut_name']; ?></option>
									<?php else: ?>
										<option value='<?php echo $cut['cut_id']; ?>'><?php echo $cut['cut_name']; ?></option>
									<?php endif;?>									
								<?php endforeach;?>
							</optgroup>
								<?php foreach($n as $cut):?>
									<?php if($cut['cut_id'] == set_value('diamond_cut',$diamond_data['d_cut_id'])): ?>
										<option value='<?php echo $cut['cut_id']; ?>' selected><?php echo $cut['cut_name']; ?></option>
									<?php else: ?>
										<option value='<?php echo $cut['cut_id']; ?>'><?php echo $cut['cut_name']; ?></option>
									<?php endif;?>									
								<?php endforeach;?>
							
						</select>

					</td>
					<td class='title'>Cut Grade:</td>
					<td>
						<select name='diamond_cut_grade' class='input_field' >
							<option value='0'></option>
							<?php foreach($gemstone_scale as $scale):?>
								<?php if($scale['scale_id'] == set_value('diamond_cut_grade', $diamond_data['d_cut_grade_id'])): ?>
									<option value='<?php echo $scale['scale_id']; ?>' selected ><?php echo $scale['scale_name']; ?></option>
								<?php else: ?>
									<option value='<?php echo $scale['scale_id']; ?>' ><?php echo $scale['scale_name']; ?></option>
								<?php endif;?>
							<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class='title'>Center Stone?:</td>
					<td> 
						<select id='is_center' name='is_center' class='input_field'>
							<?php if(set_value('is_center', $diamond_data['is_center'] == 1)): ?>
								<option value='1' selected>Yes</option>
								<option value='0'>No</option>
							<?php else: ?>
								<option value='1'>Yes</option>
								<option value='0' selected>No</option>
							<?php endif;?>
						</select>
					</td>
				</tr>				
				<tr>
					<td class='title'>Dimensions:</td>
					<td colspan='3'>
						<div style="padding: 5px;">
							Ranged?:
							<select id='is_ranged' name='is_ranged' class='input_field'>
								<?php if(set_value('is_ranged', $diamond_data['is_ranged'] == 1)): ?>
									<option value='1' selected>Yes</option>
									<option value='0'>No</option>
								<?php else: ?>
									<option value='1'>Yes</option>
									<option value='0' selected>No</option>
								<?php endif;?>
							</select>
						</div>					
						<div id='span_square'>
							<input id="d_x1" name='d_x1' class='input_field' type='text' size='10' value="<?php echo set_value('d_x1', $diamond_data['d_x1']); ?>" />
							x
							<input id="d_x2" name='d_x2' class='input_field' type='text' size='10' value="<?php echo set_value('d_x2', $diamond_data['d_x2']); ?>" />
							x
							<input id="d_x3" name='d_x3' class='input_field' type='text' size='10' value="<?php echo set_value('d_x3', $diamond_data['d_x3']); ?>" />
							mm
						</div>
						<div id='span_range' style='display: none;'>
							<input id='d_x1' name='d_rx1' class='input_field' type='text' size='10' value="<?php echo set_value('d_x1', $diamond_data['d_x1']); ?>" />
							-
							<input id='d_x2' name='d_rx2' class='input_field' type='text' size='10' value="<?php echo set_value('d_x2', $diamond_data['d_x2']); ?>" />
							mm
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="4" style='background-color: #d9d9d9;' ><strong>Certificate Information</strong></td>
				</tr>
				<tr>
					<td class="title" nowrap>Certifed By:</td>
					<td>
						<input type='text' name='diamond_cert_by' class='input_field' value='<?php echo $diamond_data['d_cert_by']; ?>' />
					</td>
					<td class="title">Cert Number:</td>
					<td>
						<input type='text' name='diamond_cert_number' class='input_field' value='<?php echo $diamond_data['d_cert_num'];?>' />
					</td>
				</tr>
				<tr>
					<td class="title" nowrap>Date of Cert:</td>
					<td>
						<input type='text' name='diamond_cert_date' class='input_field' value='<?php echo $diamond_data['d_cert_date']; ?>' />
					</td>
					<td class="title">Report Number:</td>
					<td>
						<input type='text' name='diamond_report_number' class='input_field' value='<?php echo $diamond_data['d_report_num'];?>' />
					</td>
				</tr>
				<tr>
					<td colspan="4" style='background-color: #d9d9d9;' ><strong>Diamond Properties</strong></td>
				</tr>						
				<tr>
					<td class='title'>Table Percent:</td>
					<td>
						<input type='text' name='diamond_table_percent' class='input_field' value='<?php echo $diamond_data['d_table_percnt']; ?>' />
					</td>
					<td class='title'>Depth Percent:</td>
					<td>
						<input type='text' name='diamond_depth_percent' class='input_field' value='<?php echo $diamond_data['d_depth_percnt']; ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Pavilion Depth:</td>
					<td>
						<input type='text' name='diamond_pavilion_depth' class='input_field' value='<?php echo $diamond_data['d_pavilion_depth']; ?>' />
					</td>
					<td class='title'>Crown Height:</td>
					<td>
						<input type='text' name='diamond_crown_height' class='input_field' value='<?php echo $diamond_data['d_crown_height']; ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Crown Angles:</td>
					<td>
						<input type='text' name='diamond_crown_angle' class='input_field' value='<?php echo $diamond_data['d_crown_angle']; ?>' />
					</td>
					<td class='title'>Sym:</td>
					<td>
						<input type='text' name='diamond_symmetry' class='input_field' value='<?php echo $diamond_data['d_sym']; ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Girdle Thickness:</td>
					<td>
						<input type='text' name='diamond_girdle_thickness' class='input_field' value='<?php echo $diamond_data['d_girdle_thick']; ?>' />
					</td>
					<td class='title'>Fluorescense:</td>
					<td>
						<input type='text' name='diamond_fluorescence' class='input_field' value='<?php echo $diamond_data['d_fluor']; ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Culet Size:</td>
					<td>
						<input type='text' name='diamond_culet' class='input_field' value='<?php echo $diamond_data['d_culet']; ?>' />
					</td>
					<td class='title'>Polish:</td>
					<td>
						<input type='text' name='diamond_polish' class='input_field' value='<?php echo $diamond_data['d_polish']; ?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Country of Origin</td>
					<td>
						<input type='text' name='country_origin' class='input_field' value='<?php echo $diamond_data['country_origin']?>' />
					</td>
				</tr>
				<tr>
					<td class='title'>Notes:</td>
					<td colspan="3">
						<textarea name='diamond_notes' cols='81' rows='4' class='input_field'><?php echo $diamond_data['d_notes']; ?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="4"><?php echo validation_errors();  ?></td>
				</tr>
				<tr>
					<td></td>
					<td colspan='3'>
						<input class='large' type="submit" value="Save Changes" />
					</td>
				</tr>
			</table>
			
			<?php echo form_close(); ?>
		</div>
		<h3>Or... Remove This Gemstone</h3>
		<div class='delete_admin_item' >
			<?php echo form_open('inventory/diamond/' . $item_data['item_id'] . '/remove/' . $diamond_data['d_id'])?>
				<p>
					This will remove the gemstone from the item forever. 
					If you make a mistake, you'll have to reenter the information. 
					Click at ones own risk.
				</p>
				<input type="submit" class='warning' value="Remove Diamond" />
			<?php echo form_close();?>
		</div>				
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>