<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title>Project Mango - Monthly Sum Total Report Report</title>
	<style type='text/css'>
		#report_table {
			border: 1px solid #999;
			margin: 5px;
			border-collapse: collapse;
		}
		#report_table th {
			border-bottom: 1px dashed #999;
			background-color: #ddd;
			padding-bottom: 5px;
		}
		#report_table td {
			border-bottom: 1px dashed #ddd;
			padding: 2px;
		}
		#report_table td.total {
			padding-top: 6px;
			font-weight: bold;
			background-color: #eee;
			border-bottom: 2px solid #999;
		}		
		#report_table td.invoice_total {
			border-bottom: 1px dashed #999;
		}
		#report_table td.date_header {
			padding-top: 6px;
			font-weight: bold;
			background-color: #eee;
			border-bottom: 1px dashed #999;			
		}
		
		#report_table td.no_border {
			border-top: 0;
			border-bottom: 0px;
		}
		
		#report_table td.right_border {
			border-right: 1px dashed #ddd;
		}
		
	</style>

</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Monthly Sum Total Report </h2>
		<ul id="submenu">
			<li><?php echo anchor('reports/monthly_sales_report', '<< Back to Monthly Sales Reports '); ?></li>
			<li>|</li>
			<li>
				<?php echo form_open(base_url() . 'printer/monthly_sum_total_report', 'style="display: inline;" target="_blank"'); ?>
					<input type='hidden' name='year' value='<?php echo $year; ?>' />
					<input type='hidden' name='month' value='<?php echo $month; ?>' />
					<input class='submit_link' type='submit' name='printer_submit' value='Print This Report' />
				<?php echo form_close(); ?>
			</li>			
		</ul>
		<h3>Monthly Sum Total Report:</h3>
		<?php //print_r($date_span); ?>
		<table id='report_table'>
			<tr>
				<th>Date</th>
				<th>Invoice Type</th>
				<th>Invoice ID</th>
				<th>Item Number</th>
				<th>Total</th>
			</tr>
			<?php 
				$p_sum = 0;
				$sold_items = 0;
			?>
			<?php //print_r($date_span['2010-03-24']); ?>
			<?php foreach($date_span as $date): //start of date range loop?>
				<?php $daily_sum = 0; ?>
				<tr>
					<td colspan='5' class='date_header' ><strong><?php echo date('M d, Y', strtotime($date['name']));?></strong></td>
				</tr>
				<?php if(isset($date['invoices']) && sizeof($date['invoices']) > 0): //start of invoice sizeof if ?>
					<?php foreach($date['invoices'] as $invoice): //start of invoice loop ?>
					<?php $invoice_sum = 0;?>
						<tr>
							<td class='no_border right_border'></td>
							<td>Invoice: </td>
							<?php if($invoice['sales_slip_number'] != null): ?>
								<td><?php echo anchor('sales/invoice/' . $invoice['invoice_id'], $invoice['invoice_id']); ?> - Slip Number: <?php echo $invoice['sales_slip_number']; ?></td>
							<?php else: ?>
								<td><?php echo anchor('sales/invoice/' . $invoice['invoice_id'], $invoice['invoice_id']); ?></td>
							<?php endif;?>
							<td></td>
							<td></td>
						</tr>
						<?php if(isset($invoice['items']) && sizeof($invoice['items']) > 0): //start of invoice item sizeof if ?>
							<?php foreach($invoice['items'] as $item): //start of invoice item loop ?>
								<?php 
									$invoice_sum += $item['sale_price'];
									$sold_items++; 
								?>
								<tr>
									<td class='no_border'></td>
									<td class='no_border right_border'></td>
									<td>Item: </td>
									<td><?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_number']); ?></td>
									<td>$<?php echo number_format($item['sale_price'],2); ?></td>
								</tr>
							<?php endforeach; //end of invoice item loop ?>
						<?php endif; //end of invoice item sizeof if ?>
						
						<?php if(isset($invoice['special']) && sizeof($invoice['special']) > 0): //start of invoice special item sizeof if ?>
							<?php foreach($invoice['special'] as $special): //start of invoice special item loop ?>
								<?php $invoice_sum += $special['item_price']; ?>
								
								<tr>
									<td class='no_border'></td>
									<td class='no_border right_border' ></td>
									<td>Special: </td>
									<td><?php echo $special['item_description']; ?></td>
									<td>$<?php echo number_format($special['item_price'],2); ?></td>
								</tr>
							<?php endforeach; //end of invoice special item loop ?>
						<?php endif; //end of invoice special sizeof if ?>
						
						<?php if(isset($invoice['credit']) && sizeof($invoice['credit']) > 0): //start of invoice store credit sizeof if ?>
							<?php foreach($invoice['credit'] as $credit): //start of invoice store credit loop ?>
								<?php //$invoice_sum += $credit['amount'] - $invoice['tax']; ?>
								<tr>
									<td class='no_border right_border'></td>
									<td></td>
									<td></td>
									<td>Credit Used</td>
									<td>$<span class=''><?php echo number_format($credit['amount'] - $invoice['tax'],2); ?></span></td>
								</tr>
							<?php endforeach; //end of invoice store credit loop ?>
						<?php endif; //end of invoice store credit size of if ?>
						<tr>
							<td class='no_border'></td>
							<td class='right_border invoice_total'></td>
							<td class='invoice_total'></td>
							<td class='invoice_total'></td>
							<td class='invoice_total'>Total: $<?php echo number_format($invoice_sum,2)?></td>
						</tr>
					<?php $daily_sum += $invoice_sum; ?>
					<?php endforeach; //end of invoice loop ?>
				<?php endif; //end of invoice sizeof if ?>

				<?php if(isset($date['layaway_paid']) && sizeof($date['layaway_paid'])): //start of layaway sizeof if ?>
					<?php foreach($date['layaway_paid'] as $layaway): //start of layaway loop ?>
							<tr>
								<td class='no_border right_border'></td>
								<td>Layaway: </td>
								<td><?php echo anchor('sales/invoice/' . $layaway['invoice_id'], $layaway['invoice_id']); ?></td>
								<td></td>
								<td></td>
							</tr>
						<?php if(isset($layaway['items']) && sizeof($layaway['items']) > 0): //start of layaway items sizeof if ?>
							<?php foreach($layaway['items'] as $item): //start of layaway items loop ?>
								<?php 
									$daily_sum += $item['sale_price'];
									$sold_items++;  
								?>
								<tr>
									<td></td>
									<td></td>
									<td>Item: </td>
									<td><?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_number']); ?></td>
									<td>$<?php echo number_format($item['sale_price'],2); ?></td>
								</tr>
							<?php endforeach; //end of layaway items loop ?>
						<?php endif; //end of layaway sizeof if ?>
						
						<?php if(isset($layaway['special']) && sizeof($layaway['special']) > 0): //start of layaway special item sizeof if ?>
							<?php foreach($layaway['special'] as $special): //start of layaway special item loop ?>
								<?php $daily_sum += $special['item_price']; ?>
								<tr>
									<td class='no_border'></td>
									<td></td>
									<td>Special: </td>
									<td><?php echo $special['item_description']; ?></td>
									<td>$<?php echo number_format($special['item_price'],2); ?></td>
								</tr>
							<?php endforeach; //end of layaway special item loop ?>
						<?php endif; //end of layaway special item sizeof if ?>
						<tr>
							<td class='no_border'></td>
							<td></td>
							<td></td>
							<td></td>
							<td>Total: $<?php echo number_format($layaway['total_price'],2); ?></td>							
						</tr>						
					<?php endforeach; //end of layaway loop ?>
				<?php endif; //end of layaway sizeof if ?>
				<?php if(isset($date['returns']) && sizeof($date['returns']) > 0): //start of returns sizeof if ?>
					<?php foreach($date['returns'] as $return): //start of returns loop ?>
						<tr>
							<td></td>
							<td>Return: </td>
							<td><?php echo anchor('sales/returns/' . $return['return_id'], $return['return_id']); ?></td>
							<td>Invoice ID: <?php echo anchor('sales/invoice/' . $return['invoice_id'], $return['invoice_id']); ?></td>
							<td>
								<?php if($return['credit_given']): ?>
									<span class='warning'>Credit Given</span>
								<?php endif;?>
							</td>
						</tr>
						<?php if(isset($return['items']) && sizeof($return['items']) > 0): //start of returned items sizeof if ?>
							<?php foreach($return['items'] as $r_item): //start of returned items loop ?>
							<?php 
								$daily_sum -= $r_item['sale_price'];
								//$sold_items--; //remove the returned item as sold 
							?>
								<tr>
									<td></td>
									<td></td>
									<td>Returned Item:</td>
									<td><?php echo $r_item['item_number']; ?></td>
									<td>$<span class='warning'><?php echo number_format(-$r_item['sale_price'],2); ?></span></td>
								</tr>
							<?php endforeach; //end of returned items loop ?>
						<?php endif; //end of returned items if ?>
						
						<?php if(isset($return['specials']) && sizeof($return['specials']) > 0): //start of returned special items sizeof if ?>
							<?php foreach($return['specials'] as $r_special): //start of returned special items loop ?>
							<?php $daily_sum -= $r_special['item_price']; ?>
								<tr>
									<td></td>
									<td></td>
									<td>Returned Item:</td>
									<td><?php echo $r_special['item_description']; ?></td>
									<td>$<span class='warning'><?php echo number_format(-$r_special['item_price'],2); ?></span></td>
								</tr>
							<?php endforeach; //end of returned special items loop ?>
						<?php endif; //end of returned special items sizeof if ?>
					<?php endforeach; //end of returns loop ?>
				<?php endif; //end of returns sizeof if ?>
				<tr>
					<td class='total' colspan='3'><strong>Daily Total for <?php echo date('M d, Y', strtotime($date['name'])); ?>:</strong></td>
					<td class='total'></td>
					<td class='total'><strong>$<?php echo number_format($daily_sum, 2);?></strong></td>
				</tr>
				<?php $p_sum += $daily_sum; ?>
			<?php endforeach; //end of date range loop?>
			<tr>
				<td>Items Sold:</td>
				<td><?php echo $sold_items; ?></td>
			</tr>
			<tr>
				<td>Credit used:</td>
				<td>$<?php echo number_format($sums['credit'], 2); ?></td>
			</tr>			
			<tr>
				<td>Returned:</td>
				<td>$<?php echo number_format($sums['returned'], 2); ?></td>
			</tr>
			<tr>
				<td>GRAND TOTAL:</td>
				<td>$<?php //echo number_format($sums['sum'], 2); ?><?php echo number_format($p_sum, 2);?></td>
			</tr>
		</table>
		<p>Reports Section of Project Mango</p>
	</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>