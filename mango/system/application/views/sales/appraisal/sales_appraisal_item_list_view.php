<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Invoices - Create Appraisal for Invoice Items</title>

	<script type="text/javascript">
	</script>	
	<style type='text/css'>
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');	
?>
	<div id="content">
		<h2>
			Invoices - Create Appraisals for Invoice Items: #<?php echo $invoice_data['invoice_id']; ?> - 
			<?php if($invoice_data['buyer_type'] == 1):?>
				Customer - <?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?>
			<?php elseif($invoice_data['buyer_type'] == 2):?>
				Vendor - <?php echo $buyer_data['name']; ?>
			<?php endif; ?>
		</h2>
		<ul id="submenu">
			<li><?php echo anchor('sales/invoice/' . $invoice_data['invoice_id'], '<< Back to Invoice'); ?></li>
			<li>|</li>
		</ul>
		<h3>Appraised Items:</h3>
		<?php if(sizeof($appraised_items) > 0):?>
			<?php foreach($appraised_items as $item):?>
			<?php echo form_open('sales/update_appraisal/' . $item['appraisel_id']); //@TODO fix database misspelling?>
			<table class='form_table'>
				<tr>
					<td nowrap class='title'>Item Number:</td>
					<td>
						<?php echo $item['item_number']; ?> <br />
						<?php if(sizeof($item['image_array']['external_images']) > 0): ?>
							<?php
								echo anchor('inventory/edit/' . $item['item_id'] , "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['external_images'][0]['image_location'] . '&image_type=' . $item['image_array']['external_images'][0]['image_class'] . '&image_size=' . $item['image_array']['external_images'][0]['image_size'] . "' />");
							?>
						<?php elseif(sizeof($item['image_array']['internal_images']) > 0):?>
							<?php 
							echo anchor('inventory/edit/' . $item['item_id'], "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $item['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $item['image_array']['internal_images'][0]['image_size'] . "' />");
							?>
						<?php else: ?>
							No Image Provided						
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td nowrap class='title'>Invoice ID:</td>
					<td><?php echo $invoice_data['invoice_id']; ?></td>
				</tr>
				<tr>
					<td class='title'>Appraiser:</td>
					<td>
						<select name="appraiser_id">
							<option value=''></option>
						<?php foreach($sales_people as $person):?>
							<?php if($person['user_id'] == $item['user_id']):?>
								<option value='<?php echo $person['user_id']; ?>' selected><?php echo $person['first_name'] . ' ' . $person['last_name']?></option>
							<?php else:?>
								<option value='<?php echo $person['user_id']; ?>'><?php echo $person['first_name'] . ' ' . $person['last_name']?></option>
							<?php endif;?>
						<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class='title'>Description:</td>
					<td><?php echo $item['item_description']; ?></td>
				</tr>
				<tr>
					<td class='title'>Price:</td>
					<td>$<?php echo number_format($item['sale_price'], 2); ?></td>
				</tr>
				<tr>
					<td class='title'>Tax:</td>
					<td>$<?php echo number_format($item['sale_tax'], 2); ?></td>
				</tr>
				<tr>
					<td nowrap class='title'>Email Note:</td>
					<td><textarea cols='50' name='email_note'><?php echo $item['email_note']; ?></textarea></td>
				</tr>
				<tr>
					<td nowrap class='title'></td>
					<td><?php echo validation_errors(); ?></td>
				</tr>				
				<tr>
					<td nowrap class='title'></td>
					<td>
						<input name='item_id' type='hidden' value='<?php echo $item['item_id']; ?>' />
						<input type='submit' value='Update Appraisal' />
					</td>
				</tr>
				
			</table>
			<?php echo form_close(); ?>
			<?php endforeach;?>
		<?php else: ?>
			<h4>No items to already appraised.</h4>	
		<?php endif; ?>
		
		<h3>Available Items:</h3>
		<?php if(sizeof($invoice_items) > 0):?>
			<?php foreach($invoice_items as $item):?>
			<?php echo form_open('sales/create_appraisal/invoice/' . $invoice_data['invoice_id']);?>
			<table class='form_table'>
				<tr>
					<td nowrap class='title'>Item Number:</td>
					<td>
						<?php echo $item['item_number']; ?> <br />
							<?php if(sizeof($item['image_array']['external_images']) > 0): ?>
								<?php
									echo anchor('inventory/edit/' . $item['item_id'] , "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['external_images'][0]['image_location'] . '&image_type=' . $item['image_array']['external_images'][0]['image_class'] . '&image_size=' . $item['image_array']['external_images'][0]['image_size'] . "' />");
								?>
							<?php elseif(sizeof($item['image_array']['internal_images']) > 0):?>
								<?php 
								echo anchor('inventory/edit/' . $item['item_id'], "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $item['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $item['image_array']['internal_images'][0]['image_size'] . "' />");
								?>
							<?php else: ?>
								No Image Provided						
							<?php endif; ?>
						
					</td>
				</tr>
				<tr>
					<td nowrap class='title'>Invoice ID:</td>
					<td><?php echo $invoice_data['invoice_id']; ?></td>
				</tr>
				<tr>
					<td class='title'>Appraiser:</td>
					<td>
						<select name="appraiser_id">
							<option value=''></option>
						<?php foreach($sales_people as $person):?>
							<?php if($person['user_id'] == $user_data['user_id']):?>
								<option value='<?php echo $person['user_id']; ?>' selected><?php echo $person['first_name'] . ' ' . $person['last_name']?></option>
							<?php else:?>
								<option value='<?php echo $person['user_id']; ?>'><?php echo $person['first_name'] . ' ' . $person['last_name']?></option>
							<?php endif;?>
						<?php endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class='title'>Description:</td>
					<td><?php echo $item['item_description']; ?></td>
				</tr>
				<tr>
					<td class='title'>Price:</td>
					<td>$<?php echo number_format($item['sale_price'], 2); ?></td>
				</tr>
				<tr>
					<td class='title'>Tax:</td>
					<td>$<?php echo number_format($item['sale_tax'], 2); ?></td>
				</tr>
				<tr>
					<td nowrap class='title'>Email Note:</td>
					<td><textarea cols='50' name='email_note'></textarea></td>
				</tr>
				<tr>
					<td nowrap class='title'></td>
					<td><?php echo validation_errors(); ?></td>
				</tr>				
				<tr>
					<td nowrap class='title'></td>
					<td>
						<input name='item_id' type='hidden' value='<?php echo $item['item_id']; ?>' />
						<input type='submit' value='Create an Appraisal' />
					</td>
				</tr>
				
			</table>
			<?php echo form_close(); ?>
			<?php endforeach;?>
		<?php else: ?>
			<h4>No items to availabled for appraisal.</h4>
		<?php endif;?>
		<?php if(sizeof($invoice_items) == 0 && sizeof($appraised_items) == 0):?>
			<p>don't look up just yet. he's still watching you...</p>	
		<?php endif;?>
		<p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>