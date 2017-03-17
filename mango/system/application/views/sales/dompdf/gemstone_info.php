<?php
	$get_gemstones = "SELECT stone_info.gem_id, stone_info.item_id, stone_info.is_center, stone_info.gem_carat, stone_info.gem_x1,  stone_info.gem_x2, "
	. " stone_info.gem_quantity, stone_info.gem_x3, stone_info.gem_hue, stone_info.gem_tone, stone_info.gem_clarity, stone_info.gem_dialogue_number, stone_info.is_ranged, "
	. " stone_info.gem_cut_grade, stone_info.gem_dialogue_number, diamond_cut.cut_name, "
	. " stone_type.template_type, stone_type.stone_name "
	. " FROM stone_info "
	. " LEFT JOIN stone_type ON stone_info.gem_type_id = stone_type.stone_id "
	. " LEFT JOIN diamond_cut ON stone_info.gem_cut_id = diamond_cut.cut_id "
	. " WHERE stone_info.item_id = $item_id ";
		
	$gemstones_results = mysql_query($get_gemstones) or die($get_gemstones . '<br />' .  mysql_error());
	if (mysql_num_rows($gemstones_results) >= 1) {
		echo "<table class='thin_table'>";
	}
	while($row = mysql_fetch_array($gemstones_results)) {
		$stone_name = $row['stone_name'];
		$gem_quantity = $row['gem_quantity'];
		$gem_cut_grade = $row['gem_cut_grade'];
		$template_type = $row['template_type'];
		
		$cut_name = $row['cut_name'];
		$gem_id = $row['gem_id'];

		if ($row['gem_x1'] != 0) {
			if ($row['gem_x3'] != 0) {
				$measurements = number_format($row['gem_x1'], 2) . ' x ' . number_format($row['gem_x2'], 2) . ' x ' . number_format($row['gem_x3'], 2);
			}
			else {
				$measurements = $row['gem_x1'] . '-' . $row['gem_x2'];
			}
			$measurements_html = "<tr><td>Measurements:</td><td>$measurements mm</td></tr>";
		}
		else {
			$measurements_html = "";
		}
		if ((string)$row['gem_carat'] != 0) {
			$stone_weight_html = "<tr><td>Weight:</td><td>" . number_format($row['gem_carat'], 2) . " carats</td></tr>";	
		}
		else {
			$stone_weight_html = "";
		}
		if ($row['gem_hue'] != "") {
			$stone_hue_html = "<tr><td>Hue:</td><td>" . $row['gem_hue'] . "</td></tr>";	
		}
		else {
			$stone_hue_html = "";
		}
		if ($row['gem_tone'] != "") {
			$stone_tone_html = "<tr><td>Tone:</td><td>" . $row['gem_tone'] . "</td></tr>";	
		}
		else {
			$stone_tone_html = "";
		}		
		if ($row['gem_clarity'] != "") {
			$stone_clarity_html = "<tr><td>Clarity:</td><td>" . $row['gem_clarity'] . "</td></tr>";	
		}
		else {
			$stone_clarity_html = "";
		}
		if ($row['gem_dialogue_number'] != "") {
			$stone_dialogue_html = "<tr><td>Gem Dialogue Number:</td><td>" . $row['gem_dialogue_number'] . "</td></tr>";	
		}
		else {
			$stone_dialogue_html = "";
		}	
		
		if ($row['gem_cut_grade'] != "") {
			$stone_cut_html = "<tr><td>Cut:</td><td>" . $row['gem_cut_grade'] . "</td></tr>";	
		}
		else {
			$stone_cut_html = "";
		}	
		
		?>
		<tr>
			<td colspan='2'>
				<b>(<?php echo $gem_quantity; ?>) <?php echo $cut_name;?> Cut <?php echo $stone_name;?>(s)</b>
				<?php
				if (isset($_REQUEST['printer'])) {
				}
				else {
				?>
					[<a href="item_edit_gemstone.php?item_id=<?php echo $item_id;?>&stone_id=<?php echo $gem_id; ?>&appraisel_id=<?php echo $appraisel_id; ?>">Edit</a>] 
					[<a href="appraisel_upload_plot.php?appraisel_id=<?php echo $appraisel_id; ?>&stone_type=<?php echo $template_type; ?>&gemstone_id=<?php echo $gem_id; ?>">Upload Gemstone Plot</a>]				
				<?php
				}
				?>
			</td>
		</tr>
		<?php
			echo $measurements_html;
			echo $stone_weight_html;
			echo $stone_hue_html;
			echo $stone_tone_html;
			echo $stone_clarity_html;
			echo $stone_cut_html;
			echo $stone_dialogue_html;
		?>
		<?php
	}
	if (mysql_num_rows($gemstones_results) >= 1) {
		echo "</table>";
	}	
?>