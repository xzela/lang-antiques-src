<?php

	$get_opals = "SELECT opal_info.o_id, opal_info.item_id, opal_info.is_center, opal_info.is_ranged, opal_info.o_carat, opal_info.o_x1, opal_info.o_x2, "
	. " opal_info.o_x3, opal_info.o_quantity, opal_info.o_secon_hue, opal_info.o_prim_hue, opal_info.o_hue_inten, opal_info.o_trans, opal_info.o_pattern, opal_info.o_color, "
	. " diamond_cut.cut_name, "
	. " stone_type.template_type, stone_type.stone_name "
	. " FROM opal_info "
	. " LEFT JOIN stone_type ON opal_info.o_type_id = stone_type.stone_id "
	. " LEFT JOIN diamond_cut ON opal_info.o_cut_id = diamond_cut.cut_id "
	. " WHERE opal_info.item_id = $item_id ";
		
	$opal_results = mysql_query($get_opals) or die($get_opals . '<br />' .  mysql_error());
	if (mysql_num_rows($opal_results) >= 1) {
		echo "<table class='thin_table'>";
	}	
	
	while($row = mysql_fetch_array($opal_results)) {
		$opal_id = $row['o_id'];
		$cut_name = $row['cut_name'];
		$stone_name = $row['stone_name'];
		$opal_quantity = $row['o_quantity'];
		$opal_weight = $row['o_carat'];
		$opal_prim_hue = $row['o_prim_hue']; //Predomiant Spectral Hue
		$is_ranged = $row['is_ranged'];
		$opal_color = $row['o_color']; //Body Color
		$opal_trans = $row['o_trans']; //Transparency
		$opal_hue_inten = $row['o_hue_inten']; //Hue Intensenty
		$opal_pattern = $row['o_pattern']; //Hue Patterns		
		$template_type = $row['template_type'];

		
		
		if((string)$row['o_x1'] != 0) {
			if($is_ranged == 0) {
				$opal_measurements = $row['o_x1'] . 'x' . $row['o_x2'] . 'x' . $row['o_x3'];
			}
			else {
				$opal_measurements = $row['o_x1'] . '-' . $row['o_x2'];
			}
			$opal_measurements_html = "<tr><td>Measurements:</td><td>$opal_measurements mm</td></tr>";
		}
		else {
			$opal_measurements_html = "";
		}
		
		$opal_color_html = "";
		$opal_weight_html = "";
		$opal_trans_html = "";
		$opal_hue_inten_html = "";
		$opal_pattern_html = "";
		$opal_prim_hue_html = ""; //Predomiant Spectral Hue
		
		if ($opal_color != '') { //Body Color
			$opal_color_html = "<tr><td>Body Color:</td><td> $opal_color</td></tr>";
		}
		
		if ($opal_weight != '' ) {
			$opal_weight_html = "<tr><td>Carat Weight:</td><td> $opal_weight carats</td></tr>";
		}
		
		if ($opal_trans != '' ){//Transparency
			$opal_trans_html = "<tr><td>Transparency:</td><td> $opal_trans</td></tr>";
		}
		
		if ($opal_hue_inten != '') {//Hue Intensenty
			$opal_hue_inten_html = "<tr><td>Hue Intensity:</td><td> $opal_hue_inten</td></tr>";
		}
		
		if ($opal_pattern != '') {//Hue Patterns	
			$opal_pattern_html = "<tr><td>Hue Pattern:</td><td> $opal_pattern</td></tr>";
		}
		
		if ($opal_prim_hue != '') { //Predomiant Spectral Hue
			$opal_prim_hue_html = "<tr><td>Predominant Spectral Hue:</td><td>$opal_prim_hue</td></tr>";
		}
		
		
		?>
		<tr>
			<td colspan='2'>
				<b>(<?php echo $opal_quantity; ?>) <?php echo $cut_name;?> Cut <?php echo $stone_name;?>(s)</b> 
				<?php
				if (isset($_REQUEST['printer'])) {
				}
				else {
				?>
					[<a href="item_edit_opal.php?item_id=<?php echo $item_id;?>&opal_id=<?php echo $opal_id; ?>&appraisel_id=<?php echo $appraisel_id; ?>">Edit</a>] 
					[<a href="appraisel_upload_plot.php?appraisel_id=<?php echo $appraisel_id; ?>&stone_type=<?php echo $template_type; ?>&gemstone_id=<?php echo $opal_id; ?>">Upload Gemstone Plot</a>]
				<?php
				}
				?>
			</td>
		</tr>
		<?php
			echo $opal_measurements_html;
			echo $opal_color_html;
			echo $opal_weight_html;
			echo $opal_trans_html;
			echo $opal_hue_inten_html;
			echo $opal_pattern_html;
			echo $opal_prim_hue_html;
		?>
		<?php
	}
	if (mysql_num_rows($opal_results) >= 1) {
		echo "</table>";
	}	
	
?>