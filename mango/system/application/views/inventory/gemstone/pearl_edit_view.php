<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Add Pearl</title>
	
	<script type='text/javascript' >
		var base_url = <?php echo '"' . base_url() . '"'; ?>;
		var item_id = <?php echo $item_data['item_id']; ?>;
		var pearl_id = <?php echo $pearl_data['p_id']; ?>;
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
					$('#span_field').html('-');
				}
				else {
					$('#span_range').slideUp('slow');
					$('#span_square').slideDown('slow');
					$('#span_field').html('x');
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
		<h2>Inventory - Edit Pearl</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
			<li>|</li>
			<li><?php echo anchor('inventory/pearl/' . $item_data['item_id'] . '/add', snappy_image('icons/pearl.png') . 'Add Another Pearl', 'class="green"');?></li>
		</ul>
		<div>
			<h3>Update Peral Information:</h3>
			<?php echo form_open('inventory/pearl/' . $item_data['item_id'] . '/edit/' . $pearl_data['p_id'], "id='pearl_add_form'"); ?>
			<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
			<?php echo validation_errors(); ?>
			<table class='form_table'>
				<tr>
					<td class='title large'><span class='warning'>*</span>Pearl Type:</td>
					<td colspan='3' class='large'>
						<select class='input_field' name='p_type_id'>
							<?php foreach($gemstone_names as $gem):?>
								<?php if(set_value('p_type_id', $pearl_data['p_type_id']) == $gem['stone_id']):?>
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
						<input type='text' class='input_field' name='p_quantity' value='<?php echo set_value('p_quantity', $pearl_data['p_quantity']); ?>'/>
					</td>
					<td class='title'>Weight:</td>
					<td>
						<input type='text' class='input_field' name='p_weight' value='<?php echo set_value('p_weight', $pearl_data['p_weight']); ?>'/>
					</td>
				</tr>
				<tr>
					<td class='title'>Shape:</td>
					<td>
						<input type='text' class='input_field' name='p_shape' value='<?php echo set_value('p_shape', $pearl_data['p_shape']); ?>'/>
					</td>
					<td class='title'>Body Color:</td>
					<td>
						<input type='text' class='input_field' name='p_color' value='<?php echo set_value('p_color', $pearl_data['p_color']); ?>'/>
					</td>
				</tr>
				<tr>
					<td class='title'>Center Stone?:</td>
					<td>
						<select id='is_center' name='is_center' class='input_field'>
							<?php if($pearl_data['is_center'] == 0): ?>
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
					<td>
						<div style="padding: 5px;">
							Ranged?:
							<select id='is_ranged' name='is_ranged' class='input_field'>
								<?php if(set_value('is_ranged', $pearl_data['is_ranged'] == 1)): ?>
									<option value='1' selected>Yes</option>
									<option value='0'>No</option>
								<?php else: ?>
									<option value='1'>Yes</option>
									<option value='0' selected>No</option>
								<?php endif;?>
							</select>
						</div>	
					</td>
				</tr>
				<tr>
					<td class='title'></td>
					<td colspan='2'>
						<input type='text' class='input_field' name='p_x1' value='<?php echo set_value('p_x1', $pearl_data['p_x1']); ?>'/>
						<span id='span_field'>x</span>
						<input type='text' class='input_field' name='p_x2' value='<?php echo set_value('p_x2', $pearl_data['p_x2']); ?>'/>
					</td>
				</tr>
				<tr>
					<td class='title'>Continuity:</td>
					<td>
						<input type='text' class='input_field' name='p_cont' value='<?php echo set_value('p_cont', $pearl_data['p_cont']); ?>'/>
					</td>
					<td class='title'>Nacre Thinkness:</td>
					<td>
						<input type='text' class='input_field' name='p_thick' value='<?php echo set_value('p_thick', $pearl_data['p_thick']); ?>'/>						
					</td>
				</tr>
				<tr>
					<td class='title'>Luster:</td>
					<td>
						<input type='text' class='input_field' name='p_luster' value='<?php echo set_value('p_luster', $pearl_data['p_luster']); ?>'/>					
					</td>
					<td class='title'>Blemishes:</td>
					<td>
						<input type='text' class='input_field' name='p_blemish' value='<?php echo set_value('p_blemish', $pearl_data['p_blemish']); ?>'/>					
					</td>
				</tr>
				<tr>
					<td class='title'>Country of Origin</td>
					<td>
						<input type='text' class='input_field' name='country_origin' value='<?php echo set_value('country_origin', $pearl_data['country_origin']); ?>' />
					</td>
					<td class='title'>Sphericity:</td>
					<td>
						<input type='text' class='input_field' name='p_sphere' value='<?php echo set_value('p_sphere', $pearl_data['p_sphere']); ?>'/>
					</td>
					
				</tr>				
				<tr>
					<td class='title'>Notes:</td>
					<td colspan="3">
						<textarea class='input_field' name='p_notes' rows='4' cols='50' ><?php echo set_value('p_notes', $pearl_data['p_notes']); ?></textarea>						
					</td>
				</tr>
				<tr>
					<td colspan="4" align='center'><input type="submit" value="Save Changes" /></td>
				</tr>
			</table>
			<?php echo form_close();?>

			<h3>Or... Remove This Pearl</h3>
			<div style='padding: 10px; margin: 10px; border: 1px solid #999; width: auto; background-color: #e9e9e9'>
				<?php echo form_open('inventory/pearl/' . $item_data['item_id'] . '/remove/' . $pearl_data['p_id'])?>
					<input type="submit" class='warning' value="Remove This Pearl" /> <span class='warning'>This will remove the gemstone from the item forever. If you make a mistake, you'll have to reenter the information. Click at ones own risk.</span>
				<?php echo form_close();?>
			</div>
		</div>
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>