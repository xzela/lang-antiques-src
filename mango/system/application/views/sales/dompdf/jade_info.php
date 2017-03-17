<?php
	$get_jade = "SELECT jadeite_info.j_id, jadeite_info.item_id, jadeite_info.is_center, jadeite_info.j_carat, jadeite_info.j_x1, jadeite_info.j_x2, jadeite_info.j_x3, "
	. " jadeite_info.j_quantity, jadeite_info.ils_hue, jadeite_info.ils_tone, jadeite_info.ils_inten, jadeite_info.fls_hue, jadeite_info.fls_tone, jadeite_info.fls_inten, "
	. " jadeite_info.is_ranged, jadeite_info.j_cut, jadeite_info.j_dialogue_number, "
	. " stone_type.template_type, stone_type.stone_name "
	. " FROM jadeite_info "
	. " LEFT JOIN stone_type ON jadeite_info.j_type_id = stone_type.stone_id "
	. " WHERE jadeite_info.item_id = $item_id ";
		
	$jade_results = mysql_query($get_jade) or die($get_jade . '<br />' .  mysql_error());
	if (mysql_num_rows($jade_results) >= 1) {
		echo "<table class='thin_table'>";
	}	
	while($row = mysql_fetch_array($jade_results)) {
		$jade_id = $row['j_id'];
		$is_ranged = $row['is_ranged'];
		$stone_name = $row['stone_name'];
		$jade_quantity = $row['j_quantity'];
		$jade_weight = $row['j_carat']; //we do not use
		
		$jade_cut = $row['j_cut'];
		
		$jade_ils_hue = $row['ils_hue'];
		$jade_ils_tone = $row['ils_tone'];
		$jade_ils_inten = $row['ils_inten'];
		
		$jade_fls_hue = $row['fls_hue'];
		$jade_fls_tone = $row['fls_tone'];
		$jade_fls_inten = $row['fls_inten'];
		
		$jade_dialogue_number = $row['j_dialogue_number'];
		
		$template_type = $row['template_type'];
		
		if((string)$row['j_x1'] != 0) {
			if($is_ranged == 0) {
				$jade_measurements = $row['j_x1'] . 'x' . $row['j_x2'] . 'x' . $row['j_x3'];
			}
			else {
				$jade_measurements = $row['j_x1'] . '-' . $row['j_x2'];
			}
			$measurements_html = "<tr><td>Measurements:</td><td>$jade_measurements mm</td></tr>";
		}
		else {
			$measurements_html = "";
		}
		
		
		
		
		//Display the stuff
		$jade_dialogue_number_html = "";
		$jade_ils_hue_html = "";
		$jade_ils_tone_html = "";
		$jade_ils_intensity_html = "";
		$jade_fls_hue_html = "";
		$jade_fls_tone_html = "";
		$jade_fls_intensity_html = "";
		
		//Display Jade Dialogue Number
		if($jade_dialogue_number != '') {
			$jade_dialogue_number_html = "<tr><td>Gem Dialogue Number:</td><td>$jade_dialogue_number</td></tr>";
		}
		if($jade_ils_hue != '') {
			$jade_ils_hue_html = "<tr><td>Incandescent Hude:</td><td>$jade_ils_hue</td></tr>";
		}
		if($jade_ils_tone != '') {
			$jade_ils_tone_html = "<tr><td>Incandescent Hude:</td><td>$jade_ils_tone</td></tr>";
		}
		if($jade_ils_inten != '') {
			$jade_ils_intensity_html = "<tr><td>Incandescent Hude:</td><td>$jade_ils_inten</td></tr>";
		}
		if($jade_fls_hue != '') {
			$jade_fls_hue_html = "<tr><td>Incandescent Hude:</td><td>$jade_fls_hue</td></tr>";
		}
		if($jade_fls_tone != '') {
			$jade_fls_tone_html = "<tr><td>Incandescent Hude:</td><td>$jade_fls_tone</td></tr>";
		}
		if($jade_fls_inten != '') {
			$jade_fls_hue_html = "<tr><td>Incandescent Hude:</td><td>$jade_fls_inten</td></tr>";
		}
		
		?>
		<tr>
			<td colspan='2'>
				<b>(<?php echo $jade_quantity; ?>) <?php echo $stone_name;?></b>
				<?php
				if (isset($_REQUEST['printer'])) {
				}
				else {
				?>
					[<a href="item_edit_jadeite.php?item_id=<?php echo $item_id;?>&jadeite_id=<?php echo $jade_id; ?>&appraisel_id=<?php echo $appraisel_id; ?>">Edit</a>] 
					[<a href="appraisel_upload_plot.php?appraisel_id=<?php echo $appraisel_id; ?>&stone_type=<?php echo $template_type; ?>&gemstone_id=<?php echo $jade_id; ?>">Upload Gemstone Plot</a>]				
				<?php
				}
				?>
			</td>
		</tr>
		<?php
			echo $measurements_html;
			echo $jade_dialogue_number_html;
			echo $jade_ils_hue_html;
			echo $jade_ils_tone_html;
			echo $jade_ils_intensity_html;
			echo $jade_fls_hue_html;
			echo $jade_fls_tone_html;
			echo $jade_fls_intensity_html;
		?> 	
		<?php
	}
	if (mysql_num_rows($jade_results) >= 1) {
		echo "</table>";
	}	
	
	?>