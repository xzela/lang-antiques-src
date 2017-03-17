<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Inventory</title>
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
	}

	</style>
	<script type="text/javascript">
	window.setTimeout(init,100);
	function init() {
	   if (document.body!=null) {
	      // <YOUR CODE HERE>
	      updateID();
	   } else {
	      window.setTimeout(init,100);
	   }
	}


	function updateID() {
		var span = document.getElementById('id_area');
		var mjr = document.getElementById('major_class');
		var min = document.getElementById('minor_class');
		span.innerHTML = mjr.value + ' - ' + min.value;
	}

	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Inventory - Add</h2>
		<ul id='submenu'>
			<li><?php echo anchor('inventory', ' Back to Inventory'); ?></li>
		</ul>
		<div>
			<h3>Create a New Item...</h3>
			<?php echo form_open('inventory/add'); ?>
			<table class='form_table'>
				<tr>
					<td class='title'>ID:</td>
					<td><span id="id_area" ></span></td>
				</tr>
				<tr>
					<td class='title'>Major Class:</td>
					<td>
						<select id="major_class" name="major_class" onChange="javascript:updateID();">
							<option></option>
							<?php foreach($major_classes as $row): ?>
								<?php if(set_value('major_class') == $row['mjr_class_id']): ?>
									<option selected value="<?php echo $row['mjr_class_id']; ?>"><?php echo $row['mjr_class_name']; ?></option>
								<?php else: ?>
									<option value="<?php echo $row['mjr_class_id']; ?>" ><?php echo $row['mjr_class_name']; ?></option>
								<?php endif; ?>

							<?php endforeach; ?>
						</select>
					</td>
					<td class='title' >Minor Class:</td>
					<td>
						<select id="minor_class" name="minor_class" onChange="javascript:updateID();">
							<option></option>
							<?php foreach($minor_classes as $row): ?>
								<?php if(set_value('minor_class') == $row['min_class_id']): ?>
									<option selected value="<?php echo $row['min_class_id']; ?>" ><?php echo $row['min_class_name']; ?></option>
								<?php else: ?>
									<option value="<?php echo $row['min_class_id']; ?>" ><?php echo $row['min_class_name']; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class='title' >Title:</td>
					<td colspan="3"><input type="text" name="item_name" maxlength="128" size="60" value='<?php echo set_value('item_name');?>' /></td>
				</tr>
				<tr>
					<td class='form_errors' colspan="4"><?php echo validation_errors();  ?></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="submit" value="Create New Item" />
					</td>
					<?php if($this->session->userdata('seller')): ?>
					<td>
						OR
					</td>
					<td>
						<input type="submit" name="add_seller" value="Create with following Seller:" />
						<br />
						<?php echo anchor($seller_data['link'], $seller_data['name']); ?>
						<br />
						<?php echo $seller_data['phone']; ?>
						<br />
						<?php echo $seller_data['address']; ?>
						<br />
						<?php echo $seller_data['city']; ?> <?php echo $seller_data['state']; ?> <?php echo $seller_data['zip']; ?>
						<br />
						Date: <?php echo @$seller_data['seller_date']; ?>
						<div>
							<input type="submit" name="clear_seller" value="Clear Seller" />
						</div>
					</td>
					<?php endif; ?>
				</tr>
			</table>
			<?php echo form_close(); ?>
		</div>
		<h3>...Or, Create an Assembled Item</h3>
		<div>
			<?php echo form_open('inventory/add_assembled'); ?>
			<table class='form_table'>
				<tr>
					<td colspan='4'>new item with assembly data. don't forget to give it a title!</td>
				</tr>
				<tr>
					<td class='field_label'>Major Class:</td>
					<td>
						<select name="major_class" >
							<option></option>
							<?php foreach($major_classes as $row): ?>
								<?php if(set_value('major_class') == $row['mjr_class_id']): ?>
									<option selected value="<?php echo $row['mjr_class_id']; ?>"><?php echo $row['mjr_class_name']; ?></option>
								<?php else: ?>
									<option value="<?php echo $row['mjr_class_id']; ?>" ><?php echo $row['mjr_class_name']; ?></option>
								<?php endif; ?>

							<?php endforeach; ?>
						</select>
					</td>
					<td class='field_label' >Minor Class:</td>
					<td>
						<select name="minor_class">
							<option></option>
							<?php foreach($minor_classes as $row): ?>
								<?php if(set_value('minor_class') == $row['min_class_id']): ?>
									<option selected value="<?php echo $row['min_class_id']; ?>" ><?php echo $row['min_class_name']; ?></option>
								<?php else: ?>
									<option value="<?php echo $row['min_class_id']; ?>" ><?php echo $row['min_class_name']; ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class='field_label' >Name:</td>
					<td colspan="3"><input type="text" name="item_name" maxlength="128" size="60"/></td>
				</tr>
				<tr>
					<td></td>
					<td class='form_errors' colspan="4"><?php echo validation_errors();  ?></td>
				</tr>
				<tr>
					<td></td>
					<td colspan="2"><input type="submit" value="Create Assembled Item" /></td>
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