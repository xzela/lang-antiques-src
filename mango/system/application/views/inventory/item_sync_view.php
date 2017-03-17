<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Sync Item with Lang Antiques <?php echo $item_data['item_number']?></title>
	<style type='text/css'>
		table.sync_table {
			width: 90%;
			border: 1px solid #999;
			border-collapse: collapse;
		}
		
		table.sync_table th {
			background-color: #ddd;
			border-bottom: 1px dashed #999; 
		}
		
		table.sync_table td {
			border-bottom: 1px solid #999;
			border-right: 1px solid #999;
		}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Sync Item with Lang Antiques: <?php echo $item_data['item_number']?></h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory/edit/' . $item_data['item_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Item'); ?></li>
		</ul>
		<h3>Inventory Information:</h3>
		<?php //print_r($sync_data['inventory_sync_data']); ?>
		<table class='sync_table'>
			<tr>
				<th>Field</th>
				<th>Frandango</th>
				<th>Mango</th>
				<th>Option</th>
			</tr>
			<?php if(sizeof($sync_data['inventory_sync_data']['diff']) > 0): ?>
				<?php foreach($sync_data['inventory_sync_data']['diff'] as $key => $value): ?>
					<tr>
						<td><strong><?php echo $key?></strong></td>
						<td><?php echo $value[0] ?></td>
						<td><?php echo $value[1] ?></td>
						<td>
							<?php echo form_open('inventory/sync_with_lang/' . $item_data['item_id']); ?>
								<input type='hidden' name='field_name' value='<?php echo $key ?>' />
								<input type='hidden' name='fran_id' value='<?php echo $item_data['item_id'] ?>' />
								<input type='hidden' name='lang_id' value='<?php echo $item_data['lang_id'] ?>' />
								<input type='submit' name='sync_selected_inventory_field' value='Sync This Item Field' />
							<?php echo form_close();?>
						</td>
					</tr>
				<?php endforeach;?>
				<tr>
					<td colspan='4'><span style='font-size: 14px; font-weight: bold;'>Or, Sync All Inventory Fields:</span></td>
				</tr>
				<tr>
					<td colspan='3' class='warning'>
						This will sync all the fields in the inventory table. Including <strong>item_status</strong>!<br />
						This will only sync inventory differences. 
						This means differences regarding the gemstone information, modifiers, materials, etc... will not be synced.
					</td>
					<td>
						<?php echo form_open('inventory/sync_with_lang/' . $item_data['item_id']); ?>
							<input type='hidden' name='lang_id' value='<?php echo $item_data['lang_id']; ?>' />
							<input class='warning' type='submit' name='sync_all_invenyory_fields' value='Sync All Item Fields' />
						<?php echo form_close();?>
					</td>
				</tr>
			<?php else: ?>
				<tr>
					<td colspan='4'>
						It appears that there are no differences between Mango and Frandango... <br /> 
						I think that's good...
					</td>
				</tr>
			<?php endif;?>
		</table>
		<!--  
		<h3>Gemstone Information:</h3>
		<?php //print_r($sync_data['gemstone_sync_data']); ?>
		<table class='sync_table' >
			<tr>
				<th>Field</th>
				<th>Frandango</th>
				<th>Mango</th>
				<th>Option</th>
			</tr>
			<?php if(sizeof($sync_data['gemstone_sync_data']['diff']) > 0): ?>
				<?php foreach($sync_data['gemstone_sync_data']['diff'] as $gemstone): ?>
					<tr>
						<td colspan='4' style='background-color: #e9e9e9;'><span style='font-size: 14px; font-weight: bold; '>Gemstone:</span></td>
					</tr>
					<?php //structer of $value: array(fran_id, fran_value, lang_id, lang_value);?>
					<?php foreach($gemstone as $key => $value): ?>
						<tr>
							<td><strong><?php echo $key; ?></strong></td>
							<td><?php echo $value['fran_sub_value']; ?></td>
							<td><?php echo $value['lang_sub_value']; ?></td>
							<td>
								<?php echo form_open('inventory/sync_with_lang/' . $item_data['item_id']); ?>
									<input type='hidden' name='fran_id' value='<?php echo $item_data['item_id']?>' />
									<input type='hidden' name='lang_id' value='<?php echo $item_data['lang_id']?>' />
									<input type='hidden' name='fran_gem_id' value='<?php echo $value['fran_sub_id']?>' />
									<input type='hidden' name='lang_gem_id' value='<?php echo $value['lang_sub_id']?>' />
									<input type='submit' name='sync_selected_gemstone_field' value='Sync This Gemstone Field' />
								<?php echo form_close();?>
							</td>
						</tr>
					<?php endforeach;?>
					<tr>
						<td colspan='4' style='background-color: #d1d1d1;'><span style='font-size: 14px; font-weight: bold;'>Or, Sync All Gemstone Fields:</span></td>
					</tr>
					<tr>
						<td colspan='3' class='warning'>This will only sync gemstone differences. This means differences regarding the inventory information, modifiers, materials, other diamonds, etc... will not be synced.</td>
						<td>
							<?php echo form_open('inventory/sync_with_lang/' . $item_data['item_id']); ?>
								<input type='hidden' name='lang_id' value='<?php echo $item_data['lang_id']; ?>' />
								<input class='warning' type='submit' name='sync_all_gemstone_fields' value='Sync All Gemstone Fields' />
							<?php echo form_close();?>
						</td>
					</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan='4'>
						It appears that there are no differences between Mango and Frandango... <br /> 
						I think that's good...
					</td>
				</tr>			
			<?php endif;?>
		</table>
		-->
		
		<p>Inventory Section of <?php echo $this->config->item('project_name'); ?> </p>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>