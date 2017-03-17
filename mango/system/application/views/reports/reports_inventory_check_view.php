<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<title><?php echo $this->config->item('project_name'); ?> - Run Inventory Check Report</title>
	
	<script type='text/javascript'>
		$(document).ready(function() {
			$('#everything').bind('click', function() {
				var box = $(this);
				if(box.is(':checked')) {
					$('input:radio, input:checkbox').each(function(item, index) {
						$(this).attr('disabled', true);
						if($(this).attr('id') == 'everything') {
							$(this).attr('disabled', false);
						}
					});
				}
				else {
					$('input:radio, input:checkbox').each(function(item, index) {
						$(this).attr('disabled', false);
					});
				}
			});
		});
	</script>
	<style type='text/css'>
		input.submit_link {
			border: 0px; 
			cursor: pointer;
			color: #0033FF;
			background-color: transparent;
			font-weight: normal;
			font-family: Lucida Grande, Verdana, Sans-serif;
			font-size: 14px;
		}
		
		input.submit_link:hover {
			text-decoration: underline;
			color: #3399FF;
		}
		
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');	
?>
	<div id="content">
		<h2>Run Inventory Check Report </h2>
		<ul id="submenu">
			<li><?php echo anchor('reports', '<< Back to Reports Main'); ?></li>
			<li>|</li>
		</ul>
		<h3>Inventory Check Report: </h3>
		<?php if(!$this->input->post('submit_report')): ?>
			<?php echo form_open('reports/inventory_check_report'); ?>
				<table class='form_table'>
					<tr>
						<td class='title'>Major Class:</td>
						<td>
							<select name='major_class_id'>
								<option value='any'>Any Major Classes</option>
								<?php foreach($major_classes as $major): ?>
									<?php if($this->input->post('major_class_id') == $major['major_class_id']): ?>
										<option value='<?php echo $major['major_class_id']; ?>' selected ><?php echo '[' . $major['major_class_id'] . '] ' . $major['major_class_name']; ?> </option>
									<?php else:?>
										<option value='<?php echo $major['major_class_id']; ?>' ><?php echo '[' . $major['major_class_id'] . '] ' . $major['major_class_name']; ?> </option>
									<?php endif;?>
								<?php endforeach; ?>
							</select>					
						</td>			
					</tr>
					<tr>
						<td class='title'>Minor Class:</td>
						<td>
							<select name='minor_class_id'>
								<option value='any'>Any Minor Classes</option>
								<?php foreach($minor_classes as $minor): ?>
									<?php if($this->input->post('minor_class_id') == $minor['minor_class_id']): ?>
										<option value='<?php echo $minor['minor_class_id']; ?>' selected ><?php echo '[' . $minor['minor_class_id'] . '] ' . $minor['minor_class_name']; ?> </option>
									<?php else: ?>
										<option value='<?php echo $minor['minor_class_id']; ?>' ><?php echo '[' . $minor['minor_class_id'] . '] ' . $minor['minor_class_name']; ?> </option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>					
						</td>
					</tr>
					<tr>
						<td class='title'>Status:</td>
						<td>
							<?php foreach($status as $state): ?>
								<?php echo form_checkbox($state['field_name'], $state['id'], $state['checked']); ?> <?php echo $state['name']; ?> <br />
							<?php endforeach;?>
						</td>
					</tr>
					<tr> 
						<td class='title'>Quantity:</td>
						<td>
							<input id="quantity1" type="radio" name="quantity" value= "1" checked /> Show me Items with Quantity of One or Higher <br />
							<input id="quantity2" type="radio" name="quantity" value="2"/> Show me Items with Quantity of Zero Only <br />
							<input id="quantity3" type="radio" name="quantity" value="3" /> Ignore Quantity <br />
							<br />
							Or... <br />
							<input id='everything' type="checkbox" name="everything" /> Just Show me Everything...
						</td>
					</tr>
					<tr>
						<td class='title'></td>
						<td><input type='submit' name='submit_report' value='Run Report' /></td>
					</tr>
				</table>
			<?php echo form_close(); ?>
			<?php else: ?>
				<ul id='submenu'>
					<li><?php echo anchor('reports/inventory_check_report/', 'Run Another Report');?></li>
					<li>|</li>
					<li>
						<?php echo form_open('printer/report_inventory_check', 'style="display: inline;" target="_blank"')?>
							<?php foreach($fields as $key => $value): ?>
								<?php if($key == 'status'): ?>
									<?php foreach($value as $key_status): ?>
										<input type='hidden' name='<?php echo $status[$key_status]['field_name']; ?>' value='<?php echo $status[$key_status]['id']; ?>' /> 
									<?php endforeach;?>
								<?php else: ?>
									<input type='hidden' name='<?php echo $key;?>' value='<?php echo $value?>' />
								<?php endif;?>
							<?php endforeach;?>
							<input name='images' type='checkbox' value='true' />Show Images? <input class='submit_link' type='submit' name='print_report' value='Print Report' />
						<?php echo form_close(); ?>
					</li>
					<li>|</li>
					<li>
						<?php echo form_open('reports/convert_inventory_report_to_image_report', 'style="display: inline;"')?>
							<?php foreach($fields as $key => $value): ?>
								<?php if($key == 'status'): ?>
									<?php foreach($value as $key_status): ?>
										<input type='hidden' name='<?php echo $status[$key_status]['field_name']; ?>' value='<?php echo $status[$key_status]['id']; ?>' /> 
									<?php endforeach;?>
								<?php else: ?>
									<input type='hidden' name='<?php echo $key;?>' value='<?php echo $value?>' />
								<?php endif;?>
							<?php endforeach;?>
							<input class='submit_link' type='submit' name='print_report' value='Convert to Image Report' />
						<?php echo form_close(); ?>
					</li>					
					<li>|</li>
					<li>
						<?php echo form_open('downloader/report_inventory_check', 'style="display: inline;" target="_blank"')?>
							<?php foreach($fields as $key => $value): ?>
								<?php if($key == 'status'): ?>
									<?php foreach($value as $key_status): ?>
										<input type='hidden' name='<?php echo $status[$key_status]['field_name']; ?>' value='<?php echo $status[$key_status]['id']; ?>' /> 
									<?php endforeach;?>
								<?php else: ?>
									<input type='hidden' name='<?php echo $key;?>' value='<?php echo $value?>' />
								<?php endif;?>
							<?php endforeach;?>
							<input class='submit_link' type='submit' name='print_report' value='Download Report (csv)' />
						<?php echo form_close(); ?>
					</li>					
				</ul>
				<table class='customer_table'>
					<tr>
						<th width='100px'>Item Number</th>
						<th >Item Name</th>
						<th>Cost</th>
						<th >Price</th>
						<th width='180px'>Status</th>
						<th width='70px'>Quantity</th>
					</tr>
					<?php 
						$t_cost = 0;
						$t_retail = 0;
						$t_count = 0;
						$t_rows = 0;
					?>
					<?php foreach($report_data as $item): ?>
						<?php
							$t_cost += $item['purchase_price'];
							$t_retail += $item['item_price'];
							$t_count += $item['item_quantity'];
							++$t_rows;
						?>
						<tr>
							<td>
								<?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_number']); ?>
								<br />
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
							<td><?php echo $item['item_name']; ?></td>
							<td>$<?php echo number_format($item['purchase_price'],2); ?></td>
							<td>$<?php echo number_format($item['item_price'],2); ?></td>
							<td>
								<?php echo $status[$item['item_status']]['name']; ?>
								<?php if($item['item_status'] == 0): //sold ?>
									<?php if(sizeof($item['buyer_data']) > 0): ?>
										to <?php echo anchor($item['buyer_data']['link'], $item['buyer_data']['name']); ?>
										<br />
										[<?php echo anchor('sales/invoice/' . $item['invoice_id'], 'View Invoice')?>]
									<?php else: ?>
										[<span class=warning>No Invoice Found</span>]
									<?php endif;?>
								<?php elseif($item['item_status'] == 2): //out on job ?>
									<?php if(sizeof($item['workshop_data']) > 0): ?>
										<br /> 
										<?php echo anchor('workshop/edit/' .$item['workshop_data'][0]['workshop_id'], $item['workshop_data'][0]['name']); ?>
									<?php else: ?>
										[<span class=warning>No Job Found</span>]
									<?php endif;?>
								<?php elseif($item['item_status'] == 3): //pending sale ?>
									<?php if(sizeof($item['buyer_data']) > 0): ?>
										to <?php echo anchor($item['buyer_data']['link'], $item['buyer_data']['name']) ?>
										<br /> 
										[<?php echo anchor('sales/invoice/' . $item['invoice_id'], 'View Invoice'); ?>]
									<?php else:?>
										[<span class=warning>No Invoice Found</span>]
									<?php endif;?>
								<?php elseif($item['item_status'] == 4): //out on memo ?>
									<?php if(sizeof($item['memo_data']) > 0): ?>
										[<?php echo anchor('sales/invoice/' . $item['memo_data']['invoice_id'], 'View Memo'); ?>]
										<br /> 
										<?php echo anchor($item['memo_data']['buyer_data']['link'], $item['memo_data']['buyer_data']['name']) ?>
									<?php else:?>
										[<span class=warning>No Memo Found</span>]
									<?php endif;?>									
								<?php elseif($item['item_status'] == 7): //returned to Consignee ?>
									<?php if(sizeof($item['seller_data']) > 0): ?>
										<br /> <?php echo anchor($item['seller_data']['link'], $item['seller_data']['name']); ?>
									<?php else:?>
										<br />  [<span class=warning>No Seller Found</span>]
									<?php endif;?>
								<?php endif;?>
							</td>
							<td><?php echo $item['item_quantity']; ?></td>
						</tr>
					<?php endforeach;?>
					<tr>
						<td></td>
						<td class='title' style='text-align: right;'><strong>Total:</strong></td>
						<td><strong>$<?php echo number_format($t_cost, 2);?></strong></td>
						<td><strong>$<?php echo number_format($t_retail, 2);?></strong></td>
						<td nowrap colspan='2'>
							<strong>Total Items: <?php echo $t_rows; ?></strong> <br />
							<strong tile='This means number of items based on quantity, (some items have a quantity of 2 or more)'>Total Count of Items: <?php echo $t_count; ?> </strong>
						</td>
					</tr>
				</table>
			<?php endif;?>
		<p>Reports Section of <?php echo $this->config->item('project_name'); ?></p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>