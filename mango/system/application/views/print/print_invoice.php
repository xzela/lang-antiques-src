<?php
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />


	<title>
		<?php echo $invoice_data['invoice_type_text']; ?> #<?php echo $invoice_data['invoice_id']; ?> for
			<?php if($invoice_data['buyer_type'] == 1 || $invoice_data['buyer_type'] == 3):?>
				<?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?>
			<?php elseif($invoice_data['buyer_type'] == 2):?>
				<?php echo  $buyer_data['name']; ?>
			<?php endif; ?>
	</title>
	<script type="text/javascript">
		//@TODO add printing stuff here
		window.onload = function() {
			//window.print();
		}
	</script>
	<style type='text/css'>
	body {
		font-family: Lucida Grande, Verdana, Sans-serif;
		font-size: 14px;
		color: #4F5155;

	}
	#print_body {
		width: 950px;
		margin: 0 auto;
	}


	h1, h4 {
		padding-top: 0px;
		padding-bottom: 0px;
		margin-top: 0px;
		margin-bottom: 0px;
	}
	#print_header_logo {
		float: left;
		clear: left;
	}
	#print_header_logo p, #print_header p {
		margin: 0px;
	}
	#print_header {
		float: right;
		clear: right;
	}
	.break {
		clear: both;
	}
	.invoice_table {
		border: 1px solid #999999;
		margin: 2px;
		margin-top: 10px;
		margin-bottom: 20px;
		border-collapse: collapse;
		width: 940px;
	}
	.invoice_table th {
		background-color: #e1e1e1;
		border-bottom: 1px dashed #999999;
	}

	.invoice_table td.header {
		background-color: #e1e1e1;
		border-bottom: 1px dashed #999999;
		border-top: 1px solid #999999;
		font-weight: bold;
	}
	.invoice_table td {
		vertical-align: top;
		border-bottom: 1px dashed #c1c1c1;
		padding: 5px;
	}
	.invoice_table td.top {
		border-top: 1px solid #999999;
	}

	.invoice_table td.title {
		font-weight: bold;
		text-align: right;
	}
	.invoice_table td.left {
		border-left: 1px solid #c1c1c1;
	}
	.invoice_table td.nonborder {
		border-bottom: 0;
	}
	p.italic {
		font-style: italic;
	}
	table.sub_table {
	}

	table.sub_table td {
		font-size: 14px;
		border: 0px;
		padding: 2px;
	}
	.buyer_type {
		color: maroon;
	}

    .fineprint {
        font-size: 12px;
    }
	</style>
</head>
<body id='print_body'>
	<div id='print_header_logo' style=''>
		<img src='<?php echo $company_logo['image_location']; ?>' />
		<br />
		<br />
		<br />
		<h4><?php echo $company_data['company_name']; ?></h4>
		<p><?php echo $company_data['address']; ?></p>
		<p><?php echo $company_data['city']; ?>, <?php echo $company_data['state']; ?> <?php echo $company_data['zip']; ?></p>
		<p>Tel: <?php echo $company_data['phone_number']; ?></p>
		<p>Fax: <?php echo $company_data['fax_number']; ?></p>
	</div>
	<div id='print_header' style=''>
		<h1><?php echo $invoice_data['invoice_type_text']; ?></h1>
		<h4 class='buyer_type'><?php echo ($invoice_data['buyer_type'] == 3)? 'Internet' : 'In Store' ;?> Purchase</h4>
		<h4><?php echo $invoice_data['invoice_type_text']; ?> ID: <?php echo $invoice_data['invoice_id']; ?></h4>
		<h4><?php echo $invoice_data['invoice_type_text']; ?> Date: <?php echo date('F d, Y', strtotime($invoice_data['sale_date'])); ?></h4>
		<br />
		<br />
		<br />
		<h4 style='margin-top: 10px;'>
			<?php if($invoice_data['buyer_type'] == 1 || $invoice_data['buyer_type'] == 3):?>
				<?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?>
			<?php elseif($invoice_data['buyer_type'] == 2):?>
				<?php echo  $buyer_data['name']; ?>
			<?php endif; ?>
		</h4>
		<p>
			<?php echo $buyer_data['address']; ?>
			<?php if($buyer_data['address2'] != ''): ?>
				<br /> <?php echo $buyer_data['address2']; ?>
			<?php endif;?>
		</p>
		<p><?php echo $buyer_data['city']; ?>, <?php echo $buyer_data['state']; ?> <?php echo $buyer_data['zip']; ?></p>
		<p><?php echo $buyer_data['country']; ?></p>
		<?php if($invoice_data['buyer_type'] == 2): //vendor ?>
			<p>Phone: <?php echo $buyer_data['phone'];?></p>
			<p>Fax: <?php echo $buyer_data['fax'];?></p>
		<?php else: ?>
			<p>Home: <?php echo $buyer_data['phone'];?></p>
			<p>Work: <?php echo $buyer_data['other_phone'];?></p>
		<?php endif;?>
	</div>
	<div class='break'></div>
	<br />
		<table class='invoice_table'>
			<tr>
				<th nowrap>Number</th>
				<th>Description</th>
				<th nowrap><?php echo ($invoice_data['invoice_type']  == 3) ? 'Price' : 'Retail Price'; ?></th>
				<th nowrap>Tax</th>
			</tr>
			<?php if(sizeof($invoice_items) > 0 ):?>

				<?php foreach($invoice_items as $item):?>
					<tr>
						<td style='border-top: 1px dashed #c1c1c1; border-bottom: 0px;'>
							<div style='text-align: center;'><?php echo $item['item_number']; ?></div>
								<?php if(sizeof($item['image_array']['external_images']) > 0): ?>
									<?php
										echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['external_images'][0]['image_location'] . '&image_type=' . $item['image_array']['external_images'][0]['image_class'] . '&image_size=' . $item['image_array']['external_images'][0]['image_size'] . "' />";
									?>
								<?php elseif(sizeof($item['image_array']['internal_images']) > 0):?>
									<?php
										echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $item['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $item['image_array']['internal_images'][0]['image_size'] . "' />";
									?>
								<?php else: ?>
									No Image Provided
								<?php endif; ?>
						</td>
						<td class='left'>
							<h4><?php echo $item['item_name'];?></h4>
							<p><?php echo $item['item_description'];?></p>
								<?php if(!empty($item['stone']['gemstones'])): ?>
									<table class='sub_table'>
										<?php foreach($item['stone']['gemstones'] as $gemstone):?>
											<tr>
												<td><?php echo $gemstone['gemstone_name']; ?></td>
												<td><?php echo $gemstone['is_center']? 'Center Stone' : 'Side Stone'; ?></td>
												<td><?php echo $gemstone['gemstone_shape']; ?> cut</td>
                                                <?php if($gemstone['gem_carat'] > 0 ) : ?>
												    <td><?php echo number_format($gemstone['gem_carat'],2);  ?> cts</td>
                                                <?php else: ?>
                                                    <td></td>
                                                <?php endif; ?>
											</tr>
										<?php endforeach;?>
									</table>
								<?php endif;?>
								<?php if(!empty($item['stone']['diamonds'])): ?>
									<table class='sub_table'>
										<?php $dtw = 0;?>
										<?php foreach($item['stone']['diamonds'] as $diamond):?>
											<?php $dtw += $diamond['d_carats'];?>
											<tr>
												<td><?php echo $diamond['diamond_name']; ?></td>
												<td><?php echo $diamond['is_center']? 'Center Stone' : 'Side Stone'; ?></td>
												<td><?php echo $diamond['diamond_shape']; ?> cut</td>
                                                <?php if($diamond['d_carats'] > 0): ?>
												    <td><?php echo number_format($diamond['d_carats'],2); ?> cts</td>
                                                <?php else: ?>
                                                    <td></td>
                                                <?php endif; ?>
												<td><?php echo $diamond['color']; ?></td>
												<td><?php echo $diamond['clarity']; ?></td>
											</tr>
										<?php endforeach;?>
										<tr>
											<td colspan='3' style='text-align: right;'>Total Diamond Weight:</td>
											<td><?php echo number_format($dtw,2); ?> cts</td>
										</tr>
									</table>
								<?php endif;?>
								<?php if(!empty($item['stone']['pearls'])): ?>
									<table class='sub_table'>
										<?php foreach($item['stone']['pearls'] as $pearl):?>
											<tr>
												<td><?php echo $pearl['pearl_name']; ?></td>
												<td><?php echo $pearl['is_center']? 'Center Stone' : 'Side Stone'; ?></td>
												<td><?php echo $pearl['p_shape']; ?></td>
											</tr>
										<?php endforeach;?>
									</table>
								<?php endif;?>
								<?php if(!empty($item['stone']['jadeite'])): ?>
									<table class='sub_table'>
										<?php foreach($item['stone']['jadeite'] as $jade):?>
											<tr>
												<td><?php echo $jade['jade_name']; ?></td>
												<td><?php echo $jade['is_center']? 'Center Stone' : 'Side Stone'; ?></td>
                                                <?php if($jade['j_carat'] > 0): ?>
												    <td><?php echo number_format($jade['j_carat'],2); ?> cts</td>
                                                <?php else: ?>
                                                    <td></td>
                                                <?php endif;?>
                                                <?php if($jade['j_cut'] != ''): ?>
												    <td><?php echo $jade['j_cut']; ?> cut</td>
                                                <?php else: ?>
                                                    <td></td>
                                                <?php endif; ?>
											</tr>
										<?php endforeach;?>
									</table>
								<?php endif;?>
								<?php if(!empty($item['stone']['opals'])): ?>
									<table class='sub_table'>
										<?php foreach($item['stone']['opals'] as $opal):?>
											<tr>
												<td><?php echo $opal['opal_name']; ?></td>
												<td><?php echo $opal['is_center']? 'Center Stone' : 'Side Stone'; ?></td>
                                                <?php if($opal['o_carat'] > 0): ?>
												    <td><?php echo $opal['o_carat']; ?> cts</td>
                                                <?php else: ?>
                                                    <td></td>
                                                <?php endif;?>
												<td><?php echo $opal['opal_shape']; ?></td>
											</tr>
										<?php endforeach;?>
									</table>
								<?php endif;?>
						</td>
						<td class='left'>$<?php echo number_format($item['sale_price'], 2); ?></td>
						<td class='left'>$<?php echo number_format($item['sale_tax'], 2); ?></td>
					</tr>
				<?php endforeach;?>
			<?php endif;?>
			<?php if(sizeof($special_items) > 0):?>
				<?php $repairs = array(); ?>
				<tr>
					<td colspan='5' class='header'>Special Orders</td>
				</tr>
				<?php foreach($special_items as $item):?>
					<?php if($item['item_type'] != 3): // 3 = repair?>
					<tr>
						<td colspan='2'><?php echo $item['item_description']; ?></td>
						<td class='left'>$<?php echo number_format($item['item_price'], 2); ?></td>
						<td class='left'>$<?php echo number_format($item['item_tax'], 2); ?></td>
					</tr>
					<?php else:?>
						<?php $repairs[] = $item; ?>
					<?php endif; ?>
					<?php if(sizeof($repairs) > 0): ?>
						<tr>
							<td colspan="5" class="header">Repairs</td>
						</tr>
						<?php foreach($repairs as $repair): ?>
							<tr>
								<td colspan='2'><?php echo $repair['item_description']; ?></td>
								<td class='left'>$<?php echo number_format($repair['item_price'], 2); ?></td>
								<td class='left'>$<?php echo number_format($repair['item_tax'], 2); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>

				<?php endforeach; ?>
			<?php endif;?>
			<tr>
				<td class='top nonborder'></td>
				<td class='top title nonborder'>Price:</td>
				<td class='top left' colspan='2'>$<?php echo number_format($invoice_data['total_price'], 2); ?></td>
			</tr>
			<tr>
				<td class='nonborder' ></td>
				<td class='title nonborder'>Tax:</td>
				<td class='left' colspan='2'>$<?php echo number_format($invoice_data['tax'], 2); ?></td>
			</tr>

			<?php if($invoice_data['is_shipped'] == 1):?>
			<tr>
				<td class='nonborder' ></td>
				<td class='title nonborder' nowrap>Shipping Method:</td>
				<td class='left' colspan='2'><?php echo $invoice_data['ship_method']; ?></td>
			</tr>
			<tr>
				<td class='nonborder' ></td>
				<td class='title nonborder' nowrap>Shipping Cost:</td>
				<td class='left' colspan='2'>$<?php echo $invoice_data['ship_cost']; ?></td>
			</tr>
			<tr>
				<td class='nonborder' ></td>
				<td class='title nonborder' nowrap>Ship to:</td>
				<td class='left' colspan='2' nowrap>
					<?php echo $invoice_data['ship_contact']; ?> <br />
					<?php echo $invoice_data['ship_address']; ?><br />
					<?php if($invoice_data['ship_address2'] != ''): ?>
						<?php echo $invoice_data['ship_address2']; ?> <br />
					<?php endif;?>
					<?php echo $invoice_data['ship_city']; ?>, <?php echo $invoice_data['ship_state']; ?> <?php echo $invoice_data['ship_zip']; ?>
				</td>
			</tr>

			<?php endif;?>
			<tr>
				<td class='nonborder' ></td>
				<td class='title nonborder' nowrap>Total:</td>
				<td class='left' colspan='2'>$<?php echo number_format(($total_invoice_price), 2); ?></td>
			</tr>
		</table>
		<?php if($invoice_data['invoice_type'] == 3): ?>
			<!-- Memos do not show Payment information -->
		<?php //elseif($invoice_data['invoice_type'] == 1 && $invoice_data['layaway_end_date'] == ''): //layaway ?>
		<?php elseif($invoice_data['invoice_type'] == 1): //layaway ?>
			<!-- Need to Show Layaway Payments -->
			<table class='invoice_table' wodth='100%'>
				<tr>
					<th style='text-align: left;'>Layaway Payments</th>
					<th style='text-align: left;'>Date</th>
					<th style='text-align: left;'>Amount</th>
				</tr>
				<?php $t_payments = 0; ?>
				<?php if(sizeof($layaway_payments) > 0): ?>
					<?php foreach($layaway_payments as $payment):?>
						<?php $t_payments += $payment['amount']; ?>
						<tr>
							<td><?php echo $payment_methods[$payment['method']]['name']; ?></td>
							<td><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></td>
							<td>$<?php echo number_format($payment['amount'], 2); ?></td>
						</tr>
					<?php endforeach;?>
				<?php else: ?>
					<tr>
						<td colspan='4'>No Payments</td>
					</tr>
				<?php endif;?>
				<tr>
					<td></td>
					<td>Total Remaining:</td>
					<td>$<?php echo number_format($total_invoice_price - $t_payments, 2);?></td>
				</tr>
			</table>
		<?php else: ?>
			<table class='invoice_table' width='100%'>
				<tr>
					<th style='text-align: left;'>Payments</th>
					<th style='text-align: left;'>Date</th>
					<th style='text-align: left;'>Amount</th>
				</tr>
			<?php $t_payments = 0; ?>
			<?php if(sizeof($payments) > 0):?>
				<?php foreach($payments as $payment):?>
					<?php $t_payments += $payment['amount']; ?>
					<tr>
						<td><?php echo $payment_methods[$payment['method']]['name']; ?></td>
						<td><?php echo date('M d, Y', strtotime($payment['date'])); ?></td>
						<td>$<?php echo number_format($payment['amount'], 2); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else:?>
				<tr>
					<td colspan='4'>No Payments</td>
				</tr>
			<?php endif;?>
				<tr>
					<td></td>
					<td class='title'>Balance Due:</td>
					<td>$<?php echo number_format(($invoice_data['total_price'] + $invoice_data['tax'] + $invoice_data['ship_cost']) - $t_payments, 2); ?></td>
				</tr>
			</table>
		<?php endif;?>

		<?php if($customer_total_store_credit != 0 && $show_store_credit == "true"): ?>
			<div style="border: 1px solid #999; padding: 5px; margin: 5px;">
				<p>
					Our records indicate that you have a balance of: <strong>$<?php echo number_format($customer_total_store_credit,2); ?></strong> in store credit.
				</p>
			</div>
		<?php endif;?>

		<?php if($invoice_data['print_notes'] != ''):?>
			<h4>Notes:</h4>
			<p class='italic'>
				<?php echo $invoice_data['print_notes']; ?>
			</p>
		<?php endif;?>
		<?php if($invoice_data['invoice_type'] == 3): ?>
			<p class='fineprint'>
				<strong>ALL MEMOS ARE FOR 7 DAYS UNLESS OTHERWISE NOTED</strong>
				<br />
				I hereby acknowledge receipt of the goods listed on the above date from ZJC, Inc. consignor.
				I agree to insure said goods at full value for the benefit of the consignor against all risks of loss.
				I agree that I am and will be fully responsible to the consignor for any damages to or loss of said merchandise for any cause whatsoever, including but not limited to robbery, burglary and theft, whether or not resulting from or contributed to by my fault or negligence.
				I agree to return said merchandise to consignor on demand of consignor at any time prior to its sale or purchase by me.
				It is understood that this consignment is for the sole purpose of sale or purchase by me, and upon such sale or purchase I agree to pay to the consignor the above sum.
				Title to said merchandise shall remain with the consignor until actual payment  has been received by the consignor.
				In the event consignor takes legal action to protect its rights hereunder, the undersigned agrees to pay consignor reasonable legal fees and costs incurred.
				I have read and understand the foregoing.
			</p>
			<p>
				<br />
				<br />
				X ________________________________________________________
			</p>
		<?php else: ?>
			<?php if($invoice_data['buyer_type'] == 3): ?>
				<p class='fineprint'>
					<strong>Internet Purchases: You have 10 days to return your purchase for any reason.
					All sales are final after 10 days.</strong>
				</p>
			<?php endif;?>
			<?php if($invoice_data['buyer_type'] == 1 || $invoice_data['buyer_type'] == 3): //customers?>
				<p class='fineprint'>
					You <strong>MUST</strong> contact us immediately once you receive your package to notify us of any problem regarding the condition of the piece, such as damage or extreme wear.
					If you have not contacted us within this short time period we will assume that any damage upon return was caused by you.
					We take the utmost care to package our shipments and make sure that our jewelry is in the best condition possible.
					Exchange for store credit only within 30 days. Shipping and sizing are non refundable.
				</p>
				<p class='fineprint'>
					<strong>In Store Purchases:</strong>
					No Refunds.
					Exchange for store credit only within 30 days.
					No exchange for jewelry which has been worn, altered or damaged.
				</p>
				<p class='fineprint'>
					<strong>Layaways:</strong> All sales final.
					Final payment is due no later than 90 days from the date of the initial down payment.
				</p>
                <p class='fineprint'>
                    Because mountings prohibit full and accurate observation of gem quality and weight, all data pertaining to mounted gems can be considered as approximate unless accompanied by an independent laboratory certificate ( AGL, EGL, GIA).
                    Unless otherwise stated, all colored stones and pearls are assumed to be subject to a relatively stable and possibly undetected color and/or clarity enhancement.
                </p>
				<p class='fineprint'>
					<strong>I understand and agree to the aforementioned terms of sale and when applicable I hereby affirm that this property is being purchased for use outside California.</strong>
				</p>
				<p>
				X ______________________________________________________
				</p>

			<?php else: ?>
				<p>All sales are final. No refunds or exchanges.</p>
			<?php endif;?>
		<?php endif;?>
		</body>
</html>