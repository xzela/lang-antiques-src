<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Invoices - Preview</title>

	<?php echo snappy_script('ajax/prototype.js'); ?>
	<?php echo snappy_script('ajax/scriptaculous.js'); ?>
	<?php echo snappy_script('ajax/controls.js'); ?>
	<?php echo snappy_script('ajax/effects.js'); ?>
	<?php echo snappy_script('ajax/extensions.js'); ?>
	
	<?php 
		//Ajax Options for Scriptacoulus... 
		$options = "okText: 'Save', okButton: false, cancelText: 'Cancel', cancelLink: false, submitOnBlur: 'true', ajaxOptions: {method: 'post'}";
		
	?>
	<script type="text/javascript">
	base_url = '<?php echo base_url(); ?>';
	</script>	
	<style type='text/css'>
	
	.document_preview {
		width: 8in;
		border: dashed 1px #666;
		padding: 5px;
	}
	.page_break {
		margin: 10px;
		padding: 10px;
		page-break-after: always;
		text-align: center;
		border: dashed 1px #666;
		background-color: #ffd;
	}
	table {
		margin: 20px;
	}
	.p {
	  text-align: justify;
	  font-size: 1em;
	  margin: 0.5em;
	  padding: 10px;
	}

	.limit_cond {
		font-size: 8px;
	}

	h4 {
		margin: 0px;
		padding: 0px;
	}

	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');	
?>
	<div id="content">
		<h2>
			Appraisal Preview 
		</h2>
		<ul id="submenu">
			<li><?php echo anchor('sales/invoice/' . $appraisal_data['invoice_id'], '<< Back to Invoice'); ?></li>
			<li>|</li>
			<li><a href='/prototype/system/application/views/sales/dompdf/dompdf.php?input_file=appraisal_doc.php&appraisel_id=<?php echo $appraisal_data['appraisel_id']?>&printer=1&output_file=<?php echo $file_name; ?>.pdf' >Print Appraisal</a></li>
		</ul>
		<div class='document_preview' >
			<p>
				<span class='warning'>
					<?php echo date('M d, Y', strtotime($appraisal_data['appraisel_date'])); //@TODO fix appraisal database misspelling ?>
					<br /><?php echo $customer_data['first_name'] . ' ' . $customer_data['last_name']; ?>
					<br /><?php echo $customer_data['address']; ?>
					<br /><?php echo $customer_data['city']; ?>, <?php echo $customer_data['state']; ?> <?php echo $customer_data['zip']; ?>
				</span>
			</p>
			<table>
				<tr>
					<td>RE:</td>
					<td>Estimate to replace <span class='warning'><?php echo $item_data['item_name'];?></span>, described in the enclosed appraisal, purchased at Lang Antiques.</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>Stock Number: <span class='warning'><?php echo $item_data['item_number'];?></span></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>Purchase Date: <span class='warning'><?php echo date('M d, Y', strtotime($invoice_data['sale_date'])); ?></span></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>Invoice Number: <span class='warning'><?php echo $invoice_data['invoice_id']; ?></span></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>Retail Replacement Value: <span class='warning'>$<?php echo number_format($item_data['item_price'], 2); ?></span></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>Purchase Price: <span class='warning'>$<?php echo number_format($invoice_item_data['sale_price'], 2); ?></span></td>
				</tr>
				<?php if ($item_data['item_price'] > $invoice_item_data['sale_price']): ?> 
					<?php 
					$discount_value = $item_data['item_price'] - $invoice_item_data['sale_price'];
					?>
					<tr>
						<td colspan='2'>
							This item was purchased on the date above for $<?php echo number_format($invoice_item_data['sale_price'], 2) ?>. 
							On this occasion we extended a courtesy discount of $<?php echo number_format($discount_value, 2) ?> 
							off our normal selling price of $<?php echo number_format($item_data['item_price'], 2) ?>. 
							The regular retail selling price for this item is $<?php echo number_format($item_data['item_price'], 2) ?> as stated and it is therefore recommended that the item be insured for this amount.
						</td>
					</tr>
				<?php endif; ?>			
			</table>
			<p>Dear <span class='warning'><?php echo$customer_data['first_name'] . ' ' . $customer_data['last_name'];?>,</span></p>
	
			<p>Thank you for choosing to purchase your <span class='warning'><?php echo $item_data['item_name']; ?></span> from Lang Antiques.</p>
	
			<p>For your records I have enclosed a point of sale appraisal report transmitted by pdf file for the item of jewelry purchased from Lang Antiques. The function of the appraisal is to provide a basis for obtaining insurance coverage. The replacement value is based on the purchase price and any discount will be noted.   It is given subject to the terms and conditions listed on page two of this letter and in the body of the appraisal.</p>
			<p>It has been a pleasure serving you and we look forward to being of continued assistance.</p>
			<p>Respectfully Submitted</p>
			<?php if(isset($signature_data['image_location'])):?>
				<img src="<?php echo $signature_data['image_location']; ?>" />
			<?php else:?>
				<h2 class='warning'>Appraiser has not uploaded a signature!</h2>
			<?php endif;?>
			<p>
				<span class='warning'><?php echo $appraiser_data['first_name'] . ' ' . $appraiser_data['last_name']; ?>
				<br /><?php echo $appraiser_data['short_creds']; ?></span>
			</p>
			<div class="page_break">PAGE BREAK</div>

			<!-- START OF Methodologys -->
			<h2>Our Appraisal Methodology</h2>
			<p>A tested and certified gem and jewelry appraiser using the latest "state of the art" methods and precision equipment performed this appraisal report.  The jewelry and/or gemstones described in this appraisal have been analyzed and graded using industry standards for diamonds, colored gemstones, and precious metals.  The utmost care and precaution has been taken to present an honest and unbiased report on the quality and value of this merchandise.  Each item described in this report has been photographed or scanned and file copies of all pertinent data are maintained, and we do not permit access to them by anyone without your authorization.  All information relative to this appraisal is regarded as confidential.  The appraisal is written in conformity with National Association of Jewelry Appraisers Uniform Standards of Professional Practice.</p>
			<p>Jewelry constructed solely of, or in combination with, precious metals, i.e., platinum, palladium, yellow or white gold and/or silver is tested, analyzed and described for the type and content of such metal using the touchstone and acid methods as authorized by the client and/or electronic gold tester.  When it can be determined, the type of construction is described and the article circa dated. </p>
			<p>Unless otherwise stated, all gemstones are graded and evaluated in their mountings to the maximum extent that the mounting permits examination.  Approximate weights are formulated by taking measurements and applying acceptable formulas; as such they are estimates only.  Keen determination of color, clarity and proportions may be prevented by certain types of mountings, small fancy, round, full and single, and baguette cut diamonds (melee) are evaluated according to their approximate weights and average quality grades using Gemological Institute of America (GIA) grading scale.  Major diamonds are graded with the use of pre-graded permanent master diamonds color comparison stones and the grading nomenclature prescribed by the GIA.  Colored Gemstones are graded using the GIA prescribed colored stones grading system and nomenclature.  Major colored stones are described using Gem Dialogue color grading system.</p>
			<p>Where an appraisal is based not only on the item(s), but also on data or documentation supplied therewith, this appraisal report shall also state by making reference thereto and, where appropriate, attaching copies hereto.  Because jewelry appraisal and evaluation is not a pure science but includes a subjective professional assessment, estimates of value and quality may vary from one appraiser to another with such variance not necessarily constituting error on the part of the appraiser. </p>
			<div class="page_break">PAGE BREAK</div>
			<!-- END OF Methodologys -->

			<h2>Point of Sale Jewelry Appraisal</h2>
			<p><span class='warning'><?php echo date('M d, Y', strtotime($appraisal_data['appraisel_date'])); //@TODO fix appraisal database misspelling ?></span></p>
			<p>
				Prepared at the request of: 
				<br /> 
				<span class='warning'><?php echo $customer_data['first_name'] . ' ' . $customer_data['last_name']; ?>
					<br /><?php echo $customer_data['address']; ?>
					<br /><?php echo $customer_data['city']; ?>, <?php echo $customer_data['state']; ?> <?php echo $customer_data['zip']; ?>
				</span>
			</p>
			<table>
				<tr>
					<td>Material Name:</td>
					<td><span class='warning'><?php echo $material_data['material_name']; ?></span></td>
				</tr>
				<tr>
					<td>Market Name:</td>
					<td>
						<div id='market_name_div' class='editable_field' style='width: 300px;'><?php echo $appraisal_data['market_name']; ?></div>
						<script type="text/javascript">
							new Ajax.InPlaceEditor('market_name_div', base_url + 'sales/AJAX_updateAppraisalField/<?php echo $appraisal_data['appraisel_id']; ?>/market_name', {<?php echo $options; ?>});
						</script>
					</td>
				</tr>
				<tr>
					<td>Market Closing Price:</td>
					<td>
						<div id='market_price_div' class='editable_field' style='width: 300px;'><?php echo $appraisal_data['metal_price']; ?></div>
						<script type="text/javascript">
							new Ajax.InPlaceEditor('market_price_div', base_url + 'sales/AJAX_updateAppraisalField/<?php echo $appraisal_data['appraisel_id']; ?>/metal_price/money', {<?php echo $options; ?>});
						</script>					
					</td>
				</tr>
				<tr>
					<td colspan='2'><span class='warning' style='text-decoration: underlined; font-weight: bold;'><?php echo $item_data['item_name'];?></span></td>
				</tr>
				<tr>
					<td>Item Marks:</td>
					<td>
						<div id='marks_div' class='editable_field' style='width: 500px;'><?php echo $appraisal_data['item_marks']; ?></div>
						<script type="text/javascript">
							new Ajax.InPlaceEditor('marks_div', base_url + 'sales/AJAX_updateAppraisalField/<?php echo $appraisal_data['appraisel_id']; ?>/item_marks', {cols: 70, rows: 3, <?php echo $options; ?>});
						</script>					
					</td>
				</tr>
				<tr>
					<td>Item Condition:</td>
					<td>
						<div id='condition_div' class='editable_field' style='width: 500px;'><?php echo $appraisal_data['item_condition']; ?></div>
						<script type="text/javascript">
							new Ajax.InPlaceEditor('condition_div', base_url + 'sales/AJAX_updateAppraisalField/<?php echo $appraisal_data['appraisel_id']; ?>/item_condition', {cols: 70, rows: 3, <?php echo $options; ?>});
						</script>					
					</td>
				</tr>
				<tr>
					<td>Method of Manufacture:</td>
					<td>
						<div id='manufacture_div' class='editable_field' style='width: 500px;'><?php echo $appraisal_data['item_manufacture']; ?></div>
						<script type="text/javascript">
							new Ajax.InPlaceEditor('manufacture_div', base_url + 'sales/AJAX_updateAppraisalField/<?php echo $appraisal_data['appraisel_id']; ?>/item_manufacture', {cols: 70, rows: 3, <?php echo $options; ?>});
						</script>					
					</td>
				</tr>				
				<tr>
					<td>Item Description:</td>
					<td>
						<div id='description_div' class='editable_field' style='width: 500px;'><?php echo $appraisal_data['item_description']; ?></div>
						<script type="text/javascript">
							new Ajax.InPlaceEditor('description_div', base_url + 'sales/AJAX_updateAppraisalField/<?php echo $appraisal_data['appraisel_id']; ?>/item_description', {cols: 70, rows: 3, <?php echo $options; ?>});
						</script>					
					</td>
				</tr>
				
				<tr>
					<td colspan='2'><span class='warning' style='font-style: italic; '>These fields will be formatted properly when printed.</span></td>
				</tr>
			</table>
			<?php echo $this->load->view('sales/_components/appriasal_diamond_table_view'); ?>
			<?php echo $this->load->view('sales/_components/appriasal_gemstone_table_view'); ?>
			
			<p>Plotting of internal and external characteristics center diamond: </p>
			
			<?php if(sizeof($appraisal_plot_data > 0)): ?>
				<?php foreach($appraisal_plot_data as $plot):?>
					<h3>Plot For <?php echo $plot['gemstone_type']; ?> ID # <?php echo $plot['gemstone_id']; ?></h3>
					<div>
						<center>
							<img src="<?php echo $plot['image_location']; ?>" />
						</center>
						<br /><b>Key to Plotting Symbols:</b> <?php echo $plot['plot_symbols']; ?>
						<br /><?php echo $plot['plot_comments']; ?>
						<br />Red symbols denote internal inclusions. Green denotes external blemishes.  Symbols indicate nature and position of characteristics, not necessarily their size.  Where applicable prongs are shown by black symbols.
					</div>
				<?php endforeach;?>
			<?php endif;?>
			<!-- PHOTOGRAPH SECTION STARTS-->
			<div>
				<center>
				<?php if(sizeof($item_data['image_array']['external_images']) > 0): ?>
					<?php foreach($item_data['image_array']['external_images'] as $image):?>
						<img src='<?php echo base_url();?>system/application/views/_global/thumbnail.php?image_location=<?php echo $image['image_location']; ?>&image_type=<?php echo $image['image_class']; ?>&image_size=<?php echo $image['image_size'];?>&thumb_size=150' />								
					<?php endforeach;?>
				<?php endif;?>
				</center>
			</div>
			<!-- PHOTOGRAPH SECTION ENDS-->
			<p class="limit_cond">
				Photographs included in this appraisal report are for the purpose of design representation and documentation only, 
				and should not be relied upon for accurate color reproduction, clarity, brilliance or size.  
				These photos are scanned images at 300 dpi.
			</p>
			<p>
				<span style='text-decoration: underline; font-weight: bold;'>Retail Replacement Value:</span> <span class='warning'>$<?php echo number_format($item_data['item_price'], 2); ?></span>
				<br />Sales taxes are not included in the appraised values.
				<br />Prepared By:	
			</p>
			
			<div class="page_break">PAGE BREAK</div>		
			<?php if(isset($signature_data['image_location'])):?>
				<img src="<?php echo $signature_data['image_location']; ?>" />
			<?php else:?>
				<h2 class='warning'>Appraiser has not uploaded a signature!</h2>
			<?php endif;?>
			<p>
				<span class='warning'><?php echo $appraiser_data['first_name'] . ' ' . $appraiser_data['last_name']; ?>
				<br /><?php echo $appraiser_data['short_creds']; ?></span>
			</p>			
			<h3>Appraisers Qualifications</h3>
			<p>
				<span class='warning'><?php echo $appraiser_data['long_creds'];?></span>
			</p>
	
			<!-- START CERT PRAC SECTION -->
			<h2>Certification of Appraisal Practices</h2>
			<table>
				<tr>
					<td>1.</td> 
					<td>The statements of fact contained in the appraisal report are true and correct.</td>
				</tr>
				<tr>
					<td>2.</td> 
					<td>The reported analysis, opinions and conclusions are limited only by the reported assumptions and limiting conditions, and are the appraiser's personal, unbiased, professional analysis, opinions, conclusions, and valuations.</td>
				</tr>
				<tr>
					<td>3.</td> 
					<td>The appraiser has no present nor contemplated future interest in the object(s) which is the subject of this appraisal report (unless specified to the contrary) which might tend to prevent making a fair and unbiased appraisal.</td>
				</tr>
				<tr>
					<td>4.</td> 
					<td>The appraiser has no personal interest or bias with respect to the parties involved (unless specified to the contrary).</td>
				</tr>
				<tr>
					<td>5.</td> 
					<td>The appraiser does not have a personal or business relationship with the parties involved which would lead a reasonable person to question the objectivity and validity of this report.</td>
				</tr>
				<tr>
					<td>6.</td> 
					<td>The appraiser's compensation is based on an hourly rate and is not dependent upon the amount of value determined at the conclusion of the work, neither as a fixed percentage of that value determination, nor as compensation connected to a predetermined scale relating fee to value range.</td>
				</tr>
				<tr>
					<td>7.</td> 
					<td>The appraiser has made a personal, physical inspection of the objects(s) specified in this report (unless specified to the contrary).</td>
				</tr>
				<tr>
					<td>8.</td> 
					<td>The appraiser received no separate significant professional assistance (unless specified to the contrary in which case the name of the individual(s) providing such assistance must be stated and, where appropriate, should sign the report.</td>
				</tr>
				<tr>
					<td>9.</td> 
					<td>The analysis, opinions, conclusions and valuations in this report were developed, and the report prepared, in conformity with the Uniform Standards of Professional Appraisal Practice For the Personal Property Discipline.</td>
				</tr>
				<tr>
					<td>10.</td> 
					<td>The knowledge developed in the appraisal will be maintained confidential between this appraiser and the client.</td>
				</tr>
				
			</table>
			<!-- END CERT PRAC SECTION -->
			<div class="page_break">PAGE BREAK</div>
	
			<h4>Additional limiting conditions and assumptions are listed as follows:</h4>
			<table class="limit_cond">
				<tr>
					<td>1.</td>
					<td>Unless otherwise stated this appraisal is not an offer to buy the appraised items at this or any price.</td>
				</tr>
				<tr>
					<td>2.</td>
					<td>Sales tax are not included in appraised values unless so noted.</td>
				</tr>
				<tr>
					<td>3.</td>
					<td>Any financial interest in the items appraised, or any other pertinent personal or financial interest that might be or appear to be improper will be clearly revealed in this report.</td>
				</tr>	
				<tr>
					<td>4.</td>
					<td>Possession of this report does not provide title to the items appraised.  Appraisal values are based on the appraiser's assumption of whole ownership.  This appraisal process does not discover liens, encumbrances, or fractional interests, but if any are known they will be noted.</td>
				</tr>	
				<tr>
					<td>5.</td>
					<td>Appraisal values are the appraiser's best judgment and opinion and are based on current market information.  Past or future values will be clearly marked as such.</td>
				</tr>	
				<tr>
					<td>6.</td>
					<td>Unless expressly stated, the condition of the items appraised are good for their type with serious deficiencies and repairs noted.  Ordinary wear and tear common to this type is not usually noted.</td>
				</tr>	
				<tr>
					<td>7.</td>
					<td>Possession of this report, any portion of this report, or any copy thereof does not include the right of publication without this appraisal firm's written consent.  Public use of the name of the appraiser, appraisal firm name, or information contained in the appraisal is not granted.  Use of this report in advertising is not permitted.</td>
				</tr>	
				<tr>
					<td>8.</td>
					<td>No changes may be made to this report by anyone other than the appraisers who have signed this report.  Zimmelman Jewelry Company cannot be responsible for unauthorized alterations.  Copies of appraisals are kept in the file of the above for at least ten years after the date the report was typed.</td>
				</tr>
				<tr>
					<td>9.</td>
					<td>The limited owner of the appraisal is the party for whom the work was performed.</td>
				</tr>
				<tr>
					<td>10.</td>
					<td>The information in this report is confidential</td>
				</tr>
				<tr>
					<td>11.</td>
					<td>Third parties may rely on the information in this report for the defined purpose and function only.  Third parties requiring further information than what is in the report must obtain the written permission of the owner of the appraisal before we will discuss the appraisal with them.</td>
				</tr>
				<tr>
					<td>12.</td>
					<td>Periodic review of appraisal values is required due to economic fluctuations.  The appraiser does not take on the responsibility to advise clients when values have changed.  Clients must establish their own appraisal value review needs.</td>
				</tr>
				<tr>
					<td>13.</td>
					<td>Hypothetical appraisals will be so noted.</td>
				</tr>
				<tr>
					<td>14.</td>
					<td>Appraisal fees are based on time and are not contingent on value or outcome.</td>
				</tr>
				<tr>
					<td>15.</td>
					<td>Testimony, depositions, hearings or court attendance are not required by reason of rendering this report.  Arrangements for these matters must be made in advance and in accordance with our then prevailing hourly rates.</td>
				</tr>
				<tr>
					<td>16.</td>
					<td>The statements made in this report are true and correct.</td>
				</tr>
				<tr>
					<td>17.</td>
					<td>The reported analysis, opinions and conclusions are limited only by the reported assumptions and limiting conditions.</td>
				</tr>
				<tr>
					<td>18.</td>
					<td>The appraisers have made a personal, physical inspection of the objects specified in this report unless specified to the contrary.</td>
				</tr>
				<tr>
					<td>19.</td>
					<td>Any significant appraisal assistance by another party will be so noted.</td>
				</tr>
				<tr>
					<td>20.</td>
					<td>The analysis, opinions, conclusions, and valuations in this report were developed, and the report prepared in conformity with the NAJA Standards of professional practice and the NAJA code of ethics.  </td>
				</tr>
				<tr>
					<td>21.</td>
					<td>Use of Gemological Institute of America (GIA) does not represent our firm is in any way connected with these other organizations.  We do not guarantee our grading to be exactly the same as GIA.  We do make every effort to use the same standards of GIA.</td>
				</tr>
				<tr>
					<td>22.</td>
					<td>The value placed upon any item of jewelry is based upon the components of quality of materials, method of manufacture, provenance, salability and condition,  The value is also determined by the appraiser in selecting the appropriate market for the defined use of the appraisal.</td>
				</tr>
				<tr>
					<td>23.</td>
					<td>Diamonds are valued by size, shape, proportion, outline, color and clarity.  The finest clarity in the GIA system is the Flawless grade.  Clarity decreases in quality to the imperfect grade.  The finest color grade in the GIA system is D color.  Color decreases in quality and rarity to the N through R range.  Obvious color diamonds in the grades S through Y become increasingly rare as color intensifies.  The color grade  Z denotes a strong degree of color.  Fancy color yellow and brown diamonds are more intense than Z color.  Fancy color pink and blue diamonds are much more rare than yellow or brown diamonds and tend to be less intense in color.  Fancy colored diamonds other than yellow and brown can be very valuable in the Faint Color range of the GIA system.  Proportioning, polishing, shape and outline are cutting considerations covered by the GIA teaching system incorporating cut classes 1 through 4.  These grades do not appear on GIA diamonds reports, but we use them in our reports to add further information.  </td>
				</tr>
				<tr>
					<td>24.</td>
					<td>Stones that are set can only be estimated for weight, color, cutting and clarity.  Mountings obscure exact grading of stones.  We note when stones have been examined loose or unset and that certain characteristics are exact.  Diamond grading reports are made only on unset diamond.  <b>Appraisals are made routinely on mounted diamonds although there is a decrease in the accuracy of the work product.</b></td>
				</tr>
				<tr>
					<td>25.</td>
					<td>
						<pre>GRADING USED FOR DIAMONDS, GIA Grading Scale <br />
							CLARITY<br />
							Flawless (FL) Internally Flawless (IF) Very Very Slightly Included (VVS1-VVS2)<br />
							Very Slightly Included (VS1-VS2) Slightly Included (SI1-SI2) Included (I1-I2-I3)<br />
							COLOR<br />
							Colorless (DEF) Near Colorless (GHIJ) Faint Yellow (KLM) Very Light Yellow (NOPQR)<br />
							Light Yellow (STUVW) Yellow (XYZ) Fancy Yellow
						</pre>
					</td>
				</tr>
				<tr>
					<td>26.</td>
					<td>Colored stones are valued by size, shape, proportion, rarity, color, clarity and country of origin.  Color is the most important factor.  A combination of GIA and Gem Dialogue terminology are used in our appraisals.</td>
				</tr>
				<tr>
					<td>27.</td>
					<td>Unless otherwise stated, all colored stones listed on this report have probably been subjected to a stable and possibly undetectable color enhancement process.  Market values are based on these universally practiced and accepted process and are taken into account by the appraiser</td>
				</tr>
				<tr>
					<td>28.</td>
					<td>The following AGA Certified Gem Laboratory equipment is in our offices.  When appropriate we have indicated the equipment used in this report and the results obtained.</td>
				</tr>
			</table>
			<ul class="limit_cond">
				<li>Binocular Microscope</li>
				<li>Loupe</li>
				<li>Leverage Gauge</li>
				<li>Moe Gauge</li>
				<li>Diamond grading light</li>
				<li>Incandescent spotlight </li>
				<li>Immersion Cell</li>
				<li>Polariscope</li>
				<li>Electronic scale </li>
				<li>Mechanical balance</li>
				<li>Dichroscope</li>
				<li>Color filter</li>				
			</ul>
			<ul class="limit_cond">
				<li>Monochromatic light</li>
				<li>Color description system (Gem Dialogue)</li>
				<li>Gold testing acids and test needles</li>
				<li>Penlight</li>
				<li>Polaroid filters</li>
				<li>Ultraviolet light SW and LW </li>
				<li>Refractometer</li>
				<li>Master diamonds Proportion scope</li>
				<li>Table gauge</li>
				<li>Spectroscope</li>
				<li>Optic character equipment</li>
				<li>Hot Point</li>
			</ul>
		</div>
		<p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>