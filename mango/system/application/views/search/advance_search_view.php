<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<?php echo snappy_script('calendar_us.js'); //autoloaded ?>
	<?php echo snappy_style('calendar.css'); //autoloaded ?>
	<title><?php echo $this->config->item('project_name'); ?> - Advance Search</title>
	<style type="text/css">
		#search_table {
			padding: 5px;
			margin: 5px;
			border: solid 1px #999;
			background-color: #e9e9e9;
		}
		#search_table .section {
			font-size: 14px;
			font-weight: bold;
			background-color: #c9c9c9;
		}
		#search_table td.title {
			font-weight: bold;
			text-align: right;
			vertical-align: top;
		}
		ul.list_items {
			margin-top: 0px;
			padding-top: 0px;
			padding-left: 0px;
		}
		ul.list_items li {
			padding: 1px;
			list-style-type: none;
			cursor: pointer;
		}
		ul.list_items li:hover {
			text-decoration: underline;
			color: #aaf;
		}
		ul.list_items li.ready {
			color: #44f;
		}

		ul.list_items li.used {
			color: #999;
			cursor: default;
		}
		ul.list_items li.used:hover {
			text-decoration: none;
		}

	</style>
	<script type='text/javascript'>

		window.onload = function() {
			checkGemstone(document.getElementById('gemstone'));
		}
		function appendMaterial(material) {
			var id = material.id.substr(6);
			var mat = document.getElementById(material.id);
			var name = material.innerHTML;
			var input = document.getElementById('material_ids');
			//alert(id + name);

			var list = document.getElementById('material_list');
			mat.setAttribute('onclick', null);
			mat.className = "used";
			var item = document.createElement('li');
			if(item) {
				item.onclick = function() {
					removeMaterial(this);
				}
				item.id = 'lmatid_' + id;
				item.className = 'ready';
				item.innerHTML = name;
			}
			list.appendChild(item);

			//add to the hidden field to send to search querys
			if(input.value == '') {
				input.value = id;
			}
			else {
				input.value = input.value + ',' + id;
			}
		}

		function removeMaterial(material) {
			var item = document.getElementById(material.id);
			var id = material.id.substr(7);
			var list = document.getElementById('material_list');
			var input = document.getElementById('material_ids');

			list.removeChild(item);
			var orig_mat = document.getElementById('matid_' + id);
			orig_mat.className = "ready";
			orig_mat.setAttribute('onclick', 'appendMaterial(this)');

			var s = new String(input.value);
			var ids = s.split(',');
			var new_ids = '';
			for(var i in ids) {
				if(ids[i] != '' && ids[i] != id) {
					if (new_ids == '') {
						new_ids = ids[i];
					}
					else {
						new_ids = new_ids + ',' + ids[i];
					}

				}
			}
			input.value = new_ids;

		}
		function appendModifier(modifier) {
			var id = modifier.id.substr(6);
			var mod = document.getElementById(modifier.id);
			var name = modifier.innerHTML;
			var input = document.getElementById('modifier_ids');
			//alert(id + name);

			var list = document.getElementById('modifier_list');
			mod.setAttribute('onclick', null);
			mod.className = "used";
			var item = document.createElement('li');
			if(item) {
				item.onclick = function() {
					removeModifier(this);
				}
				item.id = 'lmodid_' + id;
				item.className = 'ready';
				item.innerHTML = name;
			}
			list.appendChild(item);

			//add to the hidden field to send to search querys
			if(input.value == '') {
				input.value = id;
			}
			else {
				input.value = input.value + ',' + id;
			}
		}

		function removeModifier(modifier) {
			var item = document.getElementById(modifier.id);
			var id = modifier.id.substr(7);
			var list = document.getElementById('modifier_list');
			var input = document.getElementById('modifier_ids');

			list.removeChild(item);
			var orig_mod = document.getElementById('modid_' + id);
			orig_mod.className = "ready";
			orig_mod.setAttribute('onclick', 'appendModifier(this)');

			var s = new String(input.value);
			var ids = s.split(',');
			var new_ids = '';
			for(var i in ids) {
				if(ids[i] != '' && ids[i] != id) {
					if (new_ids == '') {
						new_ids = ids[i];
					}
					else {
						new_ids = new_ids + ',' + ids[i];
					}

				}
			}
			input.value = new_ids;
		}

		function updateStatus(checkbox) {
			var input = document.getElementById('statuses');

			if(checkbox.checked) {

				if(input.value == '') {
					input.value = checkbox.value;
				}
				else {
					input.value = input.value + ',' + checkbox.value;
				}
			}
			else {
				//remove the value
				var s = new String(input.value);
				var ids = s.split(',');
				var new_ids = '';
				for(var i in ids) {
					if(ids[i] != '' && ids[i] != checkbox.value) {
						if (new_ids == '') {
							new_ids = ids[i];
						}
						else {
							new_ids = new_ids + ',' + ids[i];
						}

					}
				}
				input.value = new_ids;
			}
		}
	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Advance Search</h2>
		<?php echo form_open('search/advance_search/', 'name="advance_search_form"'); ?>
		<table id="search_table">
			<tr>
				<td><input type="submit" name="submit_search" value="Search!"/></td>
			</tr>
			<tr>
				<td class='section' colspan='4'>Classification</td>
			</tr>
				<tr>
					<td class='title'>Major Class:</td>
					<td>
						<select name="major_class">
							<option value='any'>Any</option>
							<?php foreach($major_classes as $major): ?>
								<?php if($major['mjr_class_id'] == set_value('major_class')):?>
									<option selected value='<?php echo $major['mjr_class_id']; ?>' ><?php echo $major['mjr_class_id'] . '-' . $major['mjr_class_name']; ?></option>
								<?php else:?>
									<option value='<?php echo $major['mjr_class_id']; ?>'><?php echo $major['mjr_class_id'] . '-' . $major['mjr_class_name']; ?></option>
								<?php endif;?>
							<?php endforeach; ?>
						</select>
					</td>
					<td class='title'>Minor Class:</td>
					<td>
						<select name="minor_class">
							<option value='any'>Any</option>
							<?php foreach($minor_classes as $minor): ?>
								<?php if($minor['min_class_id'] == set_value('minor_class')):?>
									<option selected value='<?php echo $minor['min_class_id']; ?>'><?php echo $minor['min_class_id'] . '-' . $minor['min_class_name']; ?></option>
								<?php else: ?>
									<option value='<?php echo $minor['min_class_id']; ?>'><?php echo $minor['min_class_id'] . '-' . $minor['min_class_name']; ?></option>
								<?php endif;?>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
			<tr>
				<td class='section' colspan='4'>Item Information</td>
			</tr>
				<tr>
					<td class='title'>Words in Title:</td>
					<td><input name='name' type="text" value='<?php echo set_value('name'); ?>' /></td>
					<td class='title'>Words in Description:</td>
					<td><input name='description' type="text" value='<?php echo set_value('description'); ?>' /></td>
				</tr>
				<tr>
					<td class='title'>Style Number:</td>
					<td><input name='style_number' type="text" value='<?php echo set_value('style_number'); ?>' /></td>
					<td class='title'>Vendor:</td>
					<td>
						<select name='vendor_id' >
							<option value='any'>Any</option>
							<?php foreach($vendors as $vendor):?>
								<?php if($vendor['vendor_id'] == set_value('vendor_id')):?>
									<option selected value='<?php echo $vendor['vendor_id'];?>'><?php echo mysql_real_escape_string($vendor['name']); ?></option>
								<?php else:?>
									<option value='<?php echo $vendor['vendor_id'];?>'><?php echo mysql_real_escape_string($vendor['name']); ?></option>
								<?php endif;?>
							<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class='title'>Quantity:</td>
					<td><input type='text' name='item_quantity' /> </td>
				</tr>
				<tr>
					<td class='title'>Modifiers:</td>
					<td>
						<div id='modifier_div' style='border: 1px solid #999; background-color: #fff; height: 80px; overflow: auto;'>
							<ul class='list_items'>
							<?php foreach($modifiers as $mod):?>
								<li id='modid_<?php echo $mod['modifier_id']; ?>' class='ready' onclick='appendModifier(this)'><?php echo $mod['modifier_name']; ?></li>
							<?php endforeach;?>
							</ul>
						</div>
					</td>
					<td class='title'>Selected Modifiers:</td>
					<td>
						<div id='modifier_store' style='background-color: #fff; border: 1px solid #999; width: 200px; height: 80px; overflow: auto;'>
							<ul id='modifier_list' class='list_items'>
							</ul>
						</div>
						<input type="hidden" name='modifier_ids' id='modifier_ids'/>
					</td>
				</tr>
				<tr>
					<td class='title'>No Modifiers?</td>
					<td><input id='no_modifiers' name='no_modifiers' type='checkbox' /></td>
				</tr>
				<tr>
					<td class='title'>Materials:</td>
					<td>
						<div id='material_div' style='border: 1px solid #999; background-color: #fff; height: 80px; overflow: auto;'>
							<ul class='list_items'>
							<?php foreach($materials as $mat):?>
								<li id='matid_<?php echo $mat['material_id'];?>' class='ready' onclick='appendMaterial(this)'><?php echo $mat['material_name']; ?></li>
							<?php endforeach;?>
							</ul>
						</div>
					</td>
					<td class='title'>Selected Materials:</td>
					<td>
						<div id='material_store' style='background-color: #fff; border: 1px solid #999; width: 200px; height: 80px; overflow: auto;'>
							<ul id='material_list' class='list_items'>
							</ul>
						</div>
						<input type='hidden' id='material_ids' name='material_ids' />
					</td>
				</tr>
				<tr>
					<td class='title'>Purchase Date:</td>
					<td colspan='3'>
						<input name="purchase_date_from" type="text" />
						<script language="JavaScript">
						A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
						new tcal ({
							// form name
							'formname': 'advance_search_form',
							// input name
							'controlname': 'purchase_date_from'
						});
						</script>
						to:
						<input name="purchase_date_to" type="text" />
						<script language="JavaScript">
						A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
						new tcal ({
							// form name
							'formname': 'advance_search_form',
							// input name
							'controlname': 'purchase_date_to'
						});
						</script>
					</td>
				</tr>
				<tr>
					<td class='title'>Entry Date:</td>
					<td colspan='3'>
						<input name="entry_date_from" type="text" />
						<script language="JavaScript">
						A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
						new tcal ({
							// form name
							'formname': 'advance_search_form',
							// input name
							'controlname': 'entry_date_from'
						});
						</script>
						to:
						<input name="entry_date_to" type="text" />
						<script language="JavaScript">
						A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
						new tcal ({
							// form name
							'formname': 'advance_search_form',
							// input name
							'controlname': 'entry_date_to'
						});
						</script>
					</td>
				</tr>
				<tr>
					<td class='title'>Purchase Price:</td>
					<td><input name="purchase_price_start" type="text" size='10' /> to: <input name="purchase_price_end" type="text" size='10' /></td>
					<td class='title'>Retail Price:</td>
					<td><input name="retail_price_start" type="text" size='10' /> to: <input name="retail_price_end" type="text" size='10'/></td>
				</tr>
			<tr>
				<td class='section' colspan='4'>Status</td>
			</tr>
			<tr>
				<td style='vertical-align: top; text-align: right;'><strong>Status:</strong></td>
				<td colspan='3'>
					<script type='text/javascript'>

					</script>
					<b>Available</b>
					<div>
						<input type='checkbox' value='1' onclick='javascript:updateStatus(this);' /> Available
						<input type='checkbox' value='2' onclick='javascript:updateStatus(this);' /> Workshop
						<input type='checkbox' value='3' onclick='javascript:updateStatus(this);' /> Pending Sale
					</div>
					<b>Pending Queue</b>
					<div>
						<input name='pending_queue' type='checkbox' /> In Pending Repair <br />
					</div>
					<b>Sold/Memod</b><br />
					<div>
						<input type='checkbox' value='0' onclick='javascript:updateStatus(this);' /> Sold
						<input type='checkbox' value='4' onclick='javascript:updateStatus(this);' /> Memo <br />
					</div>
					<b>Gone</b><br />
					<div>
						<input type='checkbox' value='5' onclick='javascript:updateStatus(this);' /> Robbed
						<input type='checkbox' value='6' onclick='javascript:updateStatus(this);' /> Assemibled
						<input type='checkbox' value='7' onclick='javascript:updateStatus(this);' /> Return Consignment
					</div>
					<b>Other</b><br />
					<div>
						<input type='checkbox' value='91' onclick='javascript:updateStatus(this);' /> FK Import
						<input type='checkbox' value='99' onclick='javascript:updateStatus(this);' /> Unavailable (gone!)
					</div>
					<input type='hidden' id='statuses' name='statuses' />
				</td>
			</tr>
			<tr>
				<td class='title'>Imported:</td>
				<td colspan='3'>
					<input type="radio" name="import" value='imported'/> Imported
					<input type="radio" name="import" value='notimported'/> Not Imported
					<input type="radio" name="import" value='any' checked /> Don't Care
				</td>
			</tr>
			<tr>
				<td class='title'>Website:</td>
				<td colspan='3'>
					<input type="radio" name="website" value='online'/> Is Online
					<input type="radio" name="website" value='offline'/> Not Online
					<input type="radio" name="website" value='any' checked /> Don't Care
				</td>
			</tr>
			<tr>
				<td class='title'>No Web Images?:</td>
				<td><input name='no_web_images' type='checkbox' /></td>
			</tr>
			<tr>
				<td class='title'>No Scan Images?:</td>
				<td><input name='no_scan_images' type='checkbox' /></td>
			</tr>
						<script type='text/javascript'>
						function checkGemstone(gem) {
							var gem_string = gem.options[gem.options.selectedIndex].value;
							var template = gem_string.split(',');
							var cc_row = document.getElementById('cc_row');
							var ws_row = document.getElementById('ws_row');
							var select = document.getElementById('gemstone_cut');
							if (template[1] == 3) {
								//Fix for IEs lack of 'table-row' property...
								if (navigator.appName == "Microsoft Internet Explorer") {
									cc_row.style.display = "block";
									ws_row.style.display = "block";
								}
								else {
									cc_row.style.display = "table-row";
									ws_row.style.display = "table-row";
								}
								select.style.display = 'block';
								//end fix
							}
							else if (template[1] == 1 || template[1] == 2 || template[1] == 4 || template[1] == 5) {
								if (template[1] == 1 || template[1] == 5 ) {
									select.style.display = 'block';
								}
								else {
									select.style.display = 'none';
								}
								cc_row.style.display = "none";
								ws_row.style.display = "table-row";
								clearFields();
							}
							else {
								cc_row.style.display = "none";
								ws_row.style.display = "none";
								clearFields();
							}

						}

						function clearFields() {
							//reselect the default selection (first)
							var select = document.getElementById('gemstone_cut');
							select.selectedIndex = 0;

							var fields = new Array("carat_1","carat_2");
							//alert(fields);
							for (var f in fields) {
								//alert(f);
								document.getElementById(fields[f]).value = '';
							}
						}
						</script>

			<tr>
				<td class='section' colspan='4'>Gemstone Information</td>
			</tr>
				<tr>
					<td class='title'>Gemstone:</td>
					<td>
						<select id="gemstone" name="gemstone" onchange="checkGemstone(this)">
							<option value='any'>Any</option>
							<?php foreach($gemstones as $gem): ?>
								<?php if($gem['stone_id'] . ',' . $gem['template_type'] == set_value('gemstone')):?>
									<option selected value='<?php echo $gem['stone_id']; ?>,<?php echo $gem['template_type']?>'><?php echo $gem['stone_name']; ?></option>
								<?php else:?>
									<option value='<?php echo $gem['stone_id']; ?>,<?php echo $gem['template_type']?>'><?php echo $gem['stone_name']; ?></option>
								<?php endif;?>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr id='cc_row' style='display: none;'>
					<td class='title'>Color:</td>
					<td>
						<select id='color_1' name='color_1'>
							<option value="any">Any</option>
							<?php foreach($diamond_colors as $color):?>
								<option value=' <?php  echo $color['color_id']; ?>' > <?php echo $color['color_abrv']; ?></option>
							<?php endforeach;?>
						</select>
						to
						<select id='color_2' name='color_2'>
							<option value="any">Any</option>
							<?php foreach($diamond_colors as $color):?>
								<option value=' <?php  echo $color['color_id']; ?>' > <?php echo $color['color_abrv']; ?></option>
							<?php endforeach;?>
						</select>
					</td>
					<td class='title'>Clarity:</td>
					<td>
						<select id='clarity_1' name='clarity_1'>
							<option value="any">Any</option>
							<?php foreach($diamond_clarities as $clarity):?>
								<option value=' <?php  echo $clarity['clarity_id']; ?>' > <?php echo $clarity['clarity_abrv']; ?></option>
							<?php endforeach;?>
						</select>
						to
						<select id='clarity_2' name='clarity_2'>
							<option value="any">Any</option>
							<?php foreach($diamond_clarities as $clarity):?>
								<option value=' <?php  echo $clarity['clarity_id']; ?>' > <?php echo $clarity['clarity_abrv']; ?></option>
							<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr id='ws_row' style='display: none;'>
					<td class='title'>Carats/Weight:</td>
					<td>
						<input id='carat_1' name='carat_1' type="text" size='5' value='<?php set_value('carat_1'); ?>' />
						to
						<input id='carat_2' name='carat_2' type="text" size='5' value='<?php set_value('carat_2'); ?>' />
					</td>
					<td class='title'>Cut/Shape:</td>
					<td>
						<select id="gemstone_cut" name="gemstone_cut" >
							<option value="any" selected >Any Shape/Cut</option>
							<?php unset($gemstone_cuts[0]); ?>
							<?php foreach($gemstone_cuts as $cut): ?>
								<?php if($cut['cut_id'] == set_value('gemstone_cut')):?>
									<option selected value='<?php echo $cut['cut_id']; ?>' ><?php echo $cut['cut_name']; ?> </option>
								<?php else:?>
									<option value='<?php echo $cut['cut_id']; ?>' ><?php echo $cut['cut_name']; ?> </option>
								<?php endif;?>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
			<tr>
				<td><input type="submit" name="submit_search" value="Search!"/></td>
			</tr>
		</table>
		<?php echo form_close(); ?>
		<p>Search Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>