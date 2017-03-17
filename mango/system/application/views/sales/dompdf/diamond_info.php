<?php
			$get_diamonds = "SELECT diamond_info.d_id, diamond_info.item_id,  stone_type.stone_name, diamond_info.d_cut_id, diamond_cut.cut_name, diamond_info.d_type_id, "
				. " diamond_info.is_center, diamond_info.d_quantity, diamond_info.d_x1, diamond_info.d_x2, diamond_info.d_x3, diamond_info.d_carats, diamond_info.d_depth_percnt,"
				. " diamond_info.d_table_percnt, diamond_info.d_girdle_thick, diamond_info.d_culet, diamond_info.d_cert_by, diamond_info.d_cert_date, diamond_info.d_cert_num, diamond_info.d_report_num,"
				. " stone_type.template_type "
				. " FROM diamond_info "
				. " LEFT JOIN stone_type ON diamond_info.d_type_id = stone_type.stone_id "
				. " LEFT JOIN diamond_cut ON diamond_info.d_cut_id = diamond_cut.cut_id "
				. " WHERE diamond_info.item_id = $item_id ";
				
			$diamond_results = mysql_query($get_diamonds);
			if (mysql_num_rows($diamond_results) >= 1) {
				echo "<table class='thin_table'>";
			}	
			
			while($row = mysql_fetch_array($diamond_results)) {
				$d_id = $row['d_id'];
				$stone_name = $row['stone_name'];
				$diamond_quantity = $row['d_quantity'];
				$cut_name = $row['cut_name'];
				$diamond_id = $row['d_id'];
				$template_type = $row['template_type'];
				if ((string)$row['d_carats'] != 0) {
					$stone_weight_html = "<tr><td>Weight:</td><td>" . number_format($row['d_carats'], 2) . " carats</td></tr>";	
				}
				else {
					$stone_weight_html = "";
				}
				
				
				$color_clarity_text = "";
				
				//Get the Color Information
				$color_text = "";
				$get_colors = "SELECT diamond_color.color_abrv "
					 . " FROM item_diamond_color "
					 . " LEFT JOIN diamond_color ON item_diamond_color.color_id = diamond_color.color_id "
					 . " WHERE item_diamond_color.diamond_id = $diamond_id "
					 . " ORDER BY diamond_color.color_abrv ASC ";
				$color_results = mysql_query($get_colors) or die("Error with query: " . $get_colors . "<br />" . mysql_error());
				$color_count = mysql_num_rows($color_results);
				if ($color_count != 0) {
					$color_array = array();
					mysql_data_seek($color_results, 0);
					while ($colorrow = mysql_fetch_array($color_results)) {
						array_push($color_array, $colorrow);
					}
					$color_text = $color_array[0][0];
					if ($color_count > 1) {
						$color_text .= "-" . $color_array[$color_count-1][0];
					}
					$color_clarity_text .= '<tr><td>Color: </td><td>' . $color_text . '</td></tr>';
				}
				else {
					//do nothing
				}
				
				
				//Get Clarity Informatiuon
				$clarity_text = "";
				$get_clarity = "SELECT diamond_clarity.clarity_abrv "
					. " FROM item_diamond_clarity "
					. " LEFT JOIN diamond_clarity ON item_diamond_clarity.clarity_id = diamond_clarity.clarity_id "
					. " WHERE item_diamond_clarity.diamond_id = $diamond_id";
				$clarity_results = mysql_query($get_clarity) or die("Error with query: " . $get_clarity . "<br />" . mysql_error());
				$clarity_count = mysql_num_rows($clarity_results);
				
				if ($clarity_count != 0) {
					$clarity_array = array();
					mysql_data_seek($clarity_results, 0);
					while ($clarityrow = mysql_fetch_array($clarity_results)) {
						array_push($clarity_array, $clarityrow);
					}
					$clarity_text = $clarity_array[0][0];
					if ($clarity_count > 1) {
						 $clarity_text .= "-" . $clarity_array[$clarity_count-1][0];
					}
					$color_clarity_text .= '<tr><td>Clarity: </td><td>' . $clarity_text . '</td></tr>';
				}
				else {
					//do nothing
				}
				
				
				//If the stone is the center store, display more information
				$other_fields = "";
				if ($row['is_center']) {
					//Depth Percentage
					if ($row['d_depth_percnt'] == 0 || $row['d_depth_percnt'] == "" )  {
						$depth_percnt = "";
					}
					else {
						$depth_percnt = number_format($row['d_depth_percnt'], 1);
						$other_fields .= "<tr><td>Depth Percentage:</td><td>$depth_percnt %</td></tr>";
					}
					
					
					//Table Percentage
					if ($row['d_table_percnt'] == 0 || $row['d_table_percnt'] == "" )  {
						$table_percnt = "";
					}
					else {
						$table_percnt = number_format($row['d_table_percnt']);
						$other_fields .= "<tr><td>Table Percentage:</td><td>$table_percnt %</td></tr>";
					}
					
					
					//Girdle
					if ($row['d_girdle_thick'] == "" )  {
						$girdle = "";
					}
					else {
						$girdle = $row['d_girdle_thick'];
						$other_fields .= "<tr><td>Girdle:</td><td>$girdle</td></tr>";
					}
					
					
					//Culet
					if ($row['d_culet'] == '')  {
						$culet = "";
					}
					else {
						$culet = $row['d_culet'];
						$other_fields .= "<tr><td>Culet:</td><td>$culet</td></tr>";
					}
				}
				else {
				}
				
				//Cert Report Stuff!!
				if ($row['d_cert_by'] == '')  {
					$cert_by = "";
				}
				else {
					$cert_by = $row['d_cert_by'];
					$other_fields .= "<tr><td>Certificate By:</td><td>$cert_by</td></tr>";
				}					
				if ($row['d_cert_date'] == '')  {
					$cert_date = "";
				}
				else {
					$cert_date = $row['d_cert_date'];
					$other_fields .= "<tr><td>Certificate Date:</td><td>$cert_date</td></tr>";
				}
				if ($row['d_cert_num'] == '')  {
					$cert_num = "";
				}
				else {
					$cert_num = $row['d_cert_num'];
					$other_fields .= "<tr><td>Certificate Number:</td><td>$cert_num</td></tr>";
				}
				if ($row['d_report_num'] == '')  {
					$report_num = "";
				}
				else {
					$report_num = $row['d_report_num'];
					$other_fields .= "<tr><td>Report Number:</td><td>$report_num</td></tr>";
				}
					
				if ((string)$row['d_x1'] != 0) {
					if ((string)$row['d_x3'] != 0) {
						$measurements = number_format($row['d_x1'], 2) . ' x ' . number_format($row['d_x2'], 2) . ' x ' . number_format($row['d_x3'], 2);
					}
					else {
						$measurements = $row['d_x1'] . '-' . $row['d_x2'];
					}
					$measurements_html = "<tr><td>Measurements:</td><td>$measurements mm</td></tr>";
				}
				else {
					$measurements_html = "";
				}
				
				
				?>
				<tr>
					<td colspan='2'>			
						<b>(<?php echo $diamond_quantity; ?>) <?php echo $cut_name;?> Cut <?php echo $stone_name;?>(s) (ID: #<?php echo $d_id; ?>)</b>			
						<?php
						if (isset($_REQUEST['printer'])) {
						}
						else {
						?>
							[<a href="item_edit_diamond.php?item_id=<?php echo $item_id;?>&diamond_id=<?php echo $diamond_id; ?>&appraisel_id=<?php echo $appraisel_id; ?>">Edit</a>] 
							[<a href="appraisel_upload_plot.php?appraisel_id=<?php echo $appraisel_id; ?>&stone_type=<?php echo $template_type; ?>&gemstone_id=<?php echo $diamond_id; ?>">Upload Gemstone Plot</a>]
						<?php
						}
						?>
					</td>
				</tr>
				<?php
					echo $measurements_html;
					echo $stone_weight_html;
					echo $color_clarity_text;
					echo $other_fields;
				?>
			<?php
			}
			if (mysql_num_rows($diamond_results) >= 1) {
				echo "</table>";
			}	
			
		?>