	<?php echo snappy_style('calendar.css'); //autoloaded ?>	
	<?php echo snappy_script('calendar_us.js'); //autoloaded ?>	
	
	<fieldset>
		<legend>Layaway Payment Information</legend>
		<form name='layaway_date_form'>
			<table class='form_table'>
				<tr>
					<td class='title'>Layaway Start Date:</td>
					<td>
						<input name="layaway_start_date" type="text" value="<?php echo $invoice_data['layaway_start_date'] == '0000-00-00' ? '' : date('m/d/Y', strtotime($invoice_data['layaway_start_date'])); ?>" />
						<script type="text/javascript">
						A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
						new tcal ({
							// form name
							'formname': 'layaway_date_form',
							// input name
							'controlname': 'layaway_start_date',
							// callback
							'callback': function (str) {
								$.post(base_url + 'sales/jAJAX_updateInvoiceField/date', {
										invoice_id : invoice_id,
										id: 'layaway_start_date',
										value: str
									});
							} 
						});
						</script>							
					</td>
				</tr>
				<tr>
					<td class='title'>Layaway End Date:</td>
					<td>
						<?php if($invoice_data['layaway_end_date'] == ''): ?>
								<span class='warning'>Layaway is still open</span> [<?php echo anchor('sales/close_layaway/' . $invoice_data['invoice_id'], 'Close Layaway')?>]
						<?php else: ?>									
							<input name="layaway_end_date" type="text" value="<?php echo date('m/d/Y', strtotime($invoice_data['layaway_end_date'])); ?>" />
							<script type="text/javascript">
							A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
							new tcal ({
								// form name
								'formname': 'layaway_date_form',
								// input name
								'controlname': 'layaway_end_date',
								// callback
								'callback': function (str) {
									$.post(base_url + 'sales/jAJAX_updateInvoiceField/date', {
											invoice_id : invoice_id,
											id: 'layaway_end_date',
											value: str
										});
								} 
							});
							</script>	
							[<?php echo anchor('sales/reopen_layaway/' . $invoice_data['invoice_id'], 'ReOpen Layaway')?>]
						<?php endif; ?>					
					</td>
				</tr>
			</table>
		</form>
		<h3>Layaway Payments <span class='normal_text'>[<?php echo anchor('sales/add_layaway_payment/' . $invoice_data['invoice_id'], 'Add Layaway Payment')?>]</span></h3>
		<table class='invoice_table layaway_table'>
			<tr>
				<th>Layaway ID</th>
				<th>Type</th>
				<th>Amount</th>
				<th>Options</th>
			</tr>
			<?php if(sizeof($layaway_payments) > 0): ?>
				<?php foreach($layaway_payments as $payment): ?>
					<tr>
						<td><?php echo $payment['layaway_id']; ?></td>
						<td>
							<?php if($payment['payment_type'] == 1):?>
								Down Payment
							<?php else:?>
								Additional Payment
							<?php endif;?>
						</td>
						<td>
							$<?php echo number_format($payment['amount'], 2); ?> <?php echo $payment_methods[$payment['method']]['name']; ?>  payment was made on <?php echo $payment['payment_date']; ?>
						</td>
						<td>
							<?php echo form_open('sales/remove_layaway_payment/' . $invoice_data['invoice_id'] . '/' . $payment['layaway_id']); ?>
							<input class='warning' type='submit' value='Remove' />
							<?php echo form_close(); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else:?>
				<tr>
					<td colspan='4' class='warning'>No Payments Found</td>
				</tr>
			<?php endif;?>
			<tr>
				<td colspan='2' class='title top_lite'>Total Payments:</td>
				<td colspan='2' class='top_lite'>$<?php echo number_format($total_layaway_payments, 2); ?></td>
			</tr>
		</table>
	</fieldset>