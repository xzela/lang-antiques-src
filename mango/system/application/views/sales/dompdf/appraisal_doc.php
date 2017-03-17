<?php
session_start();
date_default_timezone_set('America/Los_Angeles');


$appraisel_id = $_REQUEST['appraisel_id'];
//$user_id = $_SESSION['user_id'];


include "open_connection.php";
	//GET appraisel Info
	$get_appraisel_info = "SELECT * FROM inventory_appraisels WHERE appraisel_id = $appraisel_id";
	$appraisel_results = mysql_query($get_appraisel_info) or die($get_appraisel_info . '<br />' . mysql_error());
		$invoice_id = mysql_result($appraisel_results, 0, 'invoice_id');
		$customer_id = mysql_result($appraisel_results, 0 , 'customer_id');
		$item_id = mysql_result($appraisel_results, 0, 'item_id');
		$metal_price = number_format(mysql_result($appraisel_results, 0, 'metal_price'), 2);
		$item_marks = mysql_result($appraisel_results, 0, 'item_marks');
		$item_condition = mysql_result($appraisel_results, 0, 'item_condition');
		$item_manufacture = mysql_result($appraisel_results, 0, 'item_manufacture');
		$market_name = mysql_result($appraisel_results, 0, 'market_name');
		$user_id = mysql_result($appraisel_results, 0, 'user_id');
		//Get Metal name
		$get_metal = "SELECT item_material.material_id, item_material.item_id, materials.material_name "
			. " FROM item_material "
			. " LEFT JOIN materials ON item_material.material_id = materials.material_id "
			. " WHERE item_material.item_id = $item_id LIMIT 1";
		$metal_results = mysql_query($get_metal) or die($get_metal . '<br />' . mysql_error());
		
		$metal = mysql_result($metal_results, 0, 'material_name');
		
		
		
		
		$item_description = mysql_result($appraisel_results, 0, 'item_description');
		$date = date('F d, Y', strtotime(mysql_result($appraisel_results, 0, 'appraisel_date')));

	//Get User Information
	$get_user_info = "SELECT * FROM users WHERE user_id = $user_id";
	$user_results = mysql_query($get_user_info);
		$user_first_name = mysql_result($user_results, 0, 'first_name');
		$user_last_name = mysql_result($user_results, 0, 'last_name');
		$user_creds = mysql_result($user_results, 0, 'short_creds');
		$user_long_creds = mysql_result($user_results, 0, 'long_creds');

	
	//Get User Signature
	$get_user_signature = "SELECT * FROM users_signature WHERE user_id = $user_id";
	$signature_results = mysql_query($get_user_signature);
		if(mysql_num_rows($signature_results) > 0) {
			$signatur_location = 'http://' . $_SERVER['HTTP_HOST']  . mysql_result($signature_results, 0, 'image_location');
			//$signatur_location = 'http://lang.localhost' . mysql_result($signature_results, 0, 'image_location');
		}
		else {
			$signatur_location = null;
		}
	//Get Item information
	$get_item_info = "SELECT * FROM inventory WHERE item_id = $item_id";
	$item_results = mysql_query($get_item_info);
		$item_number = mysql_result($item_results, 0, 'item_number');
		$item_title = mysql_result($item_results, 0, 'item_name');
		//$item_description = mysql_result($item_results, 0, 'item_description');
		//Replacement value
		$replacement_value = number_format(mysql_result($item_results, 0, 'item_price'), 2);
		
	//Get Customer Info
	$get_customer_info = "SELECT * FROM customer_info WHERE customer_id = $customer_id";
	$customer_results = mysql_query($get_customer_info);
		$customer_name = mysql_result($customer_results, 0, 'first_name') . ' ' . mysql_result($customer_results, 0, 'last_name');
		$customer_address = mysql_result($customer_results, 0, 'address');
		$customer_city = mysql_result($customer_results, 0, 'city');
		$customer_state = mysql_result($customer_results, 0, 'state');
		$customer_zip = mysql_result($customer_results, 0, 'zip');
		
	//Get Invoice Info
	$get_invoice_info = "SELECT * FROM invoice WHERE invoice_id = $invoice_id";
	$invoice_results = mysql_query($get_invoice_info);
		$purchase_date = date("F d, Y", strtotime(mysql_result($invoice_results, 0, 'sale_date')));
		//$date = date("M d, Y", strtotime(mysql_result($invoice_results, 0, 'sale_date')));
		
	//Get Invoice Items Info
	$get_invoice_item_info = "SELECT * FROM invoice_items WHERE invoice_id = $invoice_id AND item_id = $item_id";
	$invoice_item_results = mysql_query($get_invoice_item_info);
		$purchase_price = number_format(mysql_result($invoice_item_results, 0, 'sale_price'), 2);
	
	
	//Replacement
	
	//Purchase
	$discount_text = "";
	$use_discount = false;
	$r_item_price = mysql_result($item_results, 0, 'item_price');
	$r_sale_price = mysql_result($invoice_item_results, 0, 'sale_price');
	if ($r_item_price > $r_sale_price) {
		$use_discount = true;
		$discount_value = $r_item_price - $r_sale_price;
		$discount_text = "This item was purchased on the date above for $" . $purchase_price . ". On this occasion we extended a courtesy discount of $" . number_format($discount_value) . " off our normal selling price of $" . $replacement_value . ". The regular retail selling price for this item is $" . $replacement_value . " as stated and it is therefore recommended that the item be insured for this amount.";
	}
	
	
include "close_connection.php";


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<style>
	body {
		margin-top: 80px;
		margin-bottom: 20px;
		padding-left: 20px;
		padding-right: 20px;
	}
	h2 {
		text-align: center;
		padding-top: 0px;
	}
	h3 {
		padding: 0px;
		margin: 0px;
	}	
	h4 {
		padding-top: 20px;
	}	
	.document_preview {
		width: 8in;
		border: dashed 1px #666;
	}
	.page_break {
		page-break-after: always;
	}
	table {
		margin: 20px;
	}
	.table_details {
		margin-left: 41px;
	}
	.p {
	  text-align: justify;
	  font-size: 1em;
	  margin: 0.5em;
	  padding: 10px;
	}
	img {
		padding-top: 5px;
	}
	.limit_cond {
		font-size: 8px;
		padding: 0px;
		margin: 0px;
	}
	table {
		padding: 0px;
		margin: 0px;
	}
	.no-break {
		page-break-inside: avoid;
	}
	p {
		/*page-break-inside: avoid;*/
	}
	.thin_table {
		padding: 0px;
		margin: 0px;
		
	}
	
	</style>
</head>
<body>
<script type="text/php">

if ( isset($pdf) ) {
	
	$font = Font_Metrics::get_font("verdana");;
	$size = 8;
	$color = array(0,0,0);
	$text_height = Font_Metrics::get_font_height($font, $size);

	$foot = $pdf->open_object();

	$w = $pdf->get_width();
	$h = $pdf->get_height();

	// Draw a line along the bottom
	$y = $h - $text_height - 24;
	

	$pdf->close_object();
	$pdf->add_object($foot, "all");

	$company_name = "Lang Antiques";
	$tag_line = "Antique and Estate Jewelry";
	$address = "323 Sutter Street, San Francisco, CA 94108, 415-982-2213";
	$page_numbers = "Page {PAGE_NUM} of {PAGE_COUNT}";

	$text_height = Font_Metrics::get_font_height($font, $size);
	// Center the text
	$company_width = Font_Metrics::get_text_width($company_name, $font, $size);	
	$tag_line_width = Font_Metrics::get_text_width($tag_line, $font, $size);
	$address_width = Font_Metrics::get_text_width($address, $font, $size);
	$paging_width = Font_Metrics::get_text_width($page_numbers, $font, $size);
	
	$pdf->page_text($w / 2 - $company_width / 2, $y, $company_name, $font, $size, $color);
	$pdf->page_text($w / 2 - $tag_line_width / 2, $y + $text_height, $tag_line, $font, $size, $color);
	$pdf->page_text($w / 2 - $address_width / 2, $y + ($text_height * 2), $address, $font, $size, $color);
	$pdf->page_text($w - $paging_width / 2, $y + ($text_height * 2), $page_numbers, $font, $size, $color);
	
	
	//Header stuff
	// Open the object: all drawing commands will
	// go to the object instead of the current page
	$header = $pdf->open_object();

	$w = $pdf->get_width();
	$h = $pdf->get_height();

	// Draw a line along the bottom
	$y = 24;
	
	// Add a logo
	$img_w = 99; // 2 inches, in points
	$img_h = 62; // 1 inch, in points -- change these as required
	$pdf->image("logo_99_white.png", "png", 24, 18, $img_w, $img_h);

	// Close the object (stop capture)
	$pdf->close_object();

	// Add the object to every page. You can
	// also specify "odd" or "even"
	$pdf->add_object($header, "all");
	

}
</script>

		<p>
			<span class='warning'>
				<?php echo $date;?>
				<br />
				<br /><?php echo $customer_name; ?>
				<br /><?php echo $customer_address; ?>
				<br /><?php echo $customer_city; ?>, <?php echo $customer_state; ?> <?php echo $customer_zip; ?>
			</span>
		</p>
		<table>
			<tr>
				<td>RE:</td>
				<td>Estimate to replace <?php echo $item_title;?>, described in the enclosed appraisal, purchased at Lang Antiques.</td>
			</tr>
		</table>
		<table class="table_details">
			<tr>
				<td >Stock Number: </td>
				<td >&nbsp;</td>
				<td><?php echo $item_number;?></td>
			</tr>
			<tr>
				<td >Purchase Date:</td>
				<td >&nbsp;</td>
				<td> <?php echo $purchase_date; ?></td>
			</tr>
			<tr>
				<td >Invoice Number:</td>
				<td >&nbsp;</td>
				<td><?php echo $invoice_id; ?></td>
			</tr>
			<tr>
				<td>Retail Replacement Value:</td>
				<td >&nbsp;</td>
				<td>$<?php echo $replacement_value; ?></td>
			</tr>
			<tr>
				<td>Purchase Price:</td>
				<td >&nbsp;</td>
				<td>$<?php echo $purchase_price; ?></td>
			</tr>
		</table>
			<?php
			if ($use_discount) {
				echo "<p>$discount_text</p>";
			}
			?>

		<p>Dear <?php echo $customer_name;?>,</p>

		<p>Thank you for choosing to purchase your <?php echo $item_title; ?> from Lang Antiques.</p>

		<p>For your records I have enclosed a point of sale appraisal report transmitted by pdf file for the item of jewelry purchased from Lang Antiques. The function of the appraisal is to provide a basis for obtaining insurance coverage. The replacement value is based on the purchase price and any discount will be noted.   It is given subject to the terms and conditions listed on page two of this letter and in the body of the appraisal.</p>
		<p>It has been a pleasure serving you and we look forward to being of continued assistance.</p>
		<p>Respectfully Submitted</p>
		<?php if($signatur_location != ''):?>
			<img src="<?php echo $signatur_location; ?>" />
		<?php endif;?>
		<p>
			<?php echo $user_first_name . " " . $user_last_name; ?>
			<br /><?php echo $user_creds; ?>
		</p>
		<div class="page_break"></div>

		<!-- START OF Methodologys -->
		<h2>Our Appraisal Methodology</h2>
		<p>A tested and certified gem and jewelry appraiser using the latest "state of the art" methods and precision equipment performed this appraisal report.  The jewelry and/or gemstones described in this appraisal have been analyzed and graded using industry standards for diamonds, colored gemstones, and precious metals.  The utmost care and precaution has been taken to present an honest and unbiased report on the quality and value of this merchandise.  Each item described in this report has been photographed or scanned and file copies of all pertinent data are maintained, and we do not permit access to them by anyone without your authorization.  All information relative to this appraisal is regarded as confidential.  The appraisal is written in conformity with National Association of Jewelry Appraisers Uniform Standards of Professional Practice.</p>
		<p>Jewelry constructed solely of, or in combination with, precious metals, i.e., platinum, palladium, yellow or white gold and/or silver is tested, analyzed and described for the type and content of such metal using the touchstone and acid methods as authorized by the client and/or electronic gold tester.  When it can be determined, the type of construction is described and the article circa dated. </p>
		<p>Unless otherwise stated, all gemstones are graded and evaluated in their mountings to the maximum extent that the mounting permits examination.  Approximate weights are formulated by taking measurements and applying acceptable formulas; as such they are estimates only.  Keen determination of color, clarity and proportions may be prevented by certain types of mountings, small fancy, round, full and single, and baguette cut diamonds (melee) are evaluated according to their approximate weights and average quality grades using Gemological Institute of America (GIA) grading scale.  Major diamonds are graded with the use of pre-graded permanent master diamonds color comparison stones and the grading nomenclature prescribed by the GIA.  Colored Gemstones are graded using the GIA prescribed colored stones grading system and nomenclature.  Major colored stones are described using Gem Dialogue color grading system.</p>
		<p>Where an appraisal is based not only on the item(s), but also on data or documentation supplied therewith, this appraisal report shall also state by making reference thereto and, where appropriate, attaching copies hereto.  Because jewelry appraisal and evaluation is not a pure science but includes a subjective professional assessment, estimates of value and quality may vary from one appraiser to another with such variance not necessarily constituting error on the part of the appraiser. </p>
		<div class="page_break"></div>
		<!-- END OF Methodologys -->

		<h2>Point of Sale Jewelry Appraisal</h2>
		<p><?php echo $date;?></p>
		<p>
			Prepared at the request of: 
			<br /><?php echo $customer_name; ?>
			<br /><?php echo $customer_address; ?>
			<br /><?php echo $customer_city; ?>, <?php echo $customer_state; ?> <?php echo $customer_zip; ?>
			
		</p>
		<p>
			<?php echo $metal; ?>, <?php echo $market_name; ?> Close $<?php echo $metal_price; ?> per ounce
		</p>

		<p>
			<b><u><?php echo $item_title;?></u></b>:
			<br />
			<?php echo $item_description; ?>
		</p>
		<p>
			<b>Marks:</b> <?php echo $item_marks; ?>
		</p>
		<p>
			<b>Condition:</b> <?php echo $item_condition; ?>
		</p>
		<p>
			<b>Method of Manufacture:</b> <?php echo $item_manufacture; ?>
		</p>
		
		<?php
			$disclaimer = ' <span style="color: #999;"> - This values will be removed on print</span>';
			
			include 'open_connection.php';
			include 'diamond_info.php';
			include 'gemstone_info.php';
			include 'pearl_info.php';
			include 'opal_info.php';
			include 'jade_info.php';
			include 'close_connection.php';
		?>
		<div class="page_break"></div>
		<div class="no-break">
			<?php
				include "open_connection.php";
				
				$get_current_info = "SELECT * FROM inventory_appraisel_plots WHERE appraisel_id = $appraisel_id ";
				$check_results = mysql_query($get_current_info) or die($get_current_info . '<br />' . mysql_error());
				$gemstone_array = array(1 => 'Gemstone', 2 => 'Pearl', 3 => 'Diamond', 4 => 'Jadeite', 5=> 'Opal');
				if(mysql_num_rows($check_results) >= 1 ) {
					while($plot_row = mysql_fetch_array($check_results)) {
						$plot_location = 'http://' . $_SERVER['HTTP_HOST']  . $plot_row['image_location'];
						$plot_symbols = $plot_row['plot_symbols'];
						$plot_comments = $plot_row['plot_comments'];
						$plot_width = $plot_row['image_width'];
						
						$gemstone_name_text = $gemstone_array[$plot_row['gemstone_type']];
						
						//check the width of the plot
						if($plot_width >= 500) {
							$plot_width = "width: 300px;";
						}
						else {
							//$plot_width = "width: " . $plot_width . "px;";
							$plot_width = "width: 300px;";
						}
						?>
						<h3>Plot for <?php echo $gemstone_name_text; ?> ID #<?php echo $plot_row['gemstone_id']; ?></h3>
						<div>
						Plotting of internal and external characteristics center diamond:
						<br />
							<center>
								<img src="<?php echo $plot_location; ?>" style="<?php echo $plot_width; ?>; " />
							</center>
							<br /><b>Key to Plotting Symbols:</b> <?php echo $plot_symbols; ?>
							<br /><?php echo $plot_comments; ?>
							<br />Red symbols denote internal inclusions. Green denotes external blemishes.  Symbols indicate nature and position of characteristics, not necessarily their size.  Where applicable prongs are shown by black symbols.
						</div>
						<?php
					}
				}
				
				include "close_connection.php";
			?>
			<!-- PHOTOGRAPH SECTION STARTS-->
			<br />
		</div>
		<div class="no-break">
			<div>
				<center>
				<?php
				include "open_connection.php";
					$get_image = "SELECT image_location, image_id FROM image_base WHERE item_id = $item_id ORDER BY image_seq ASC";
					$image_results = mysql_query($get_image) or die("error in query: " . mysql_error());
					while ($row = mysql_fetch_array($image_results)) {
						$photo_locations = 'http://' . $_SERVER['HTTP_HOST']  . $row['image_location'];
						?>
						<img src="<?php echo $photo_locations; ?>" style="width: 150px; " /> &nbsp;
						<?php
					}
				include "close_connection.php";
				?>
				</center>
			</div>
			<p class="limit_cond">Photographs included in this appraisal report are for the purpose of design representation and documentation only, and should not be relied upon for accurate color reproduction, clarity, brilliance or size.  These photos are scanned images at 300 dpi.</p>
			<p>
				<b><u>Retail Replacement Value:</u></b> $<?php echo $replacement_value; ?>
				<br />Sales taxes are not included in the appraised values.
			</p>			
		</div>
		<p></p>
		<div class='page_break'></div>
		<!-- PHOTOGRAPH SECTION ENDS-->
		<div class="no-break">
			Prepared By:
			<p></p>
			<?php if($signatur_location != ''):?>
				<img src="<?php echo $signatur_location; ?>" />
			<?php endif;?>
			<p>
				<?php echo $user_first_name . " " . $user_last_name; ?>
				<br /><?php echo $user_creds; ?>
				<br /><b>Appraisers Qualifications</b>
				<br /><?php echo $user_long_creds;?>
			</p>
		</div>
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
				<td>The appraiser’s compensation is based on an hourly rate and is not dependent upon the amount of value determined at the conclusion of the work, neither as a fixed percentage of that value determination, nor as compensation connected to a predetermined scale relating fee to value range.</td>
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
		<div class="page_break"></div>

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
				<td>Possession of this report does not provide title to the items appraised.  Appraisal values are based on the appraiser’s assumption of whole ownership.  This appraisal process does not discover liens, encumbrances, or fractional interests, but if any are known they will be noted.</td>
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
		<table class="limit_cond">
			<tr>
				<td>Binocular Microscope</td>
				<td>Monochromatic light</td>
			</tr>
			<tr>
				<td>Loupe</td>
				<td>Color description system (Gem Dialogue)</td>
			</tr>
			<tr>
				<td>Leverage Gauge</td>
				<td>Gold testing acids and test needles</td>
			</tr>
			<tr>
				<td>Moe Gauge</td>
				<td>Penlight</td>
			</tr>
			<tr>
				<td>Diamond grading light</td>
				<td>Polaroid filters</td>
			</tr>
			<tr>
				<td>Incandescent spotlight </td>
				<td>Immersion Cell</td>
			</tr>
			<tr>
				<td>Polariscope</td>
				<td>Electronic scale </td>				
			</tr>
			<tr>
				<td>Mechanical balance</td>
				<td>Dichroscope</td>
			</tr>
			<tr>
				<td>Color filter</td>
				<td>Ultraviolet light SW and LW </td>
			</tr>
			<tr>
				<td>Refractometer</td>
				<td>Master diamonds Proportion scope</td>
			</tr>
			<tr>
				<td>Table gauge</td>
				<td>Spectroscope</td>
			</tr>
			<tr>
				<td>Optic character equipment</td>
				<td>Hot Point</td>
			</tr>
		</table>
</body>
</html>
