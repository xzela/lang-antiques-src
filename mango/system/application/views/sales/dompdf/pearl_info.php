<?php
	$get_pearls = "SELECT pearl_info.p_id, pearl_info.item_id, pearl_info.is_center, pearl_info.p_weight, pearl_info.p_x1,  pearl_info.p_x2, "
	. " pearl_info.p_quantity, pearl_info.p_color, pearl_info.p_shape, pearl_info.p_luster, pearl_info.is_ranged, "
	. " stone_type.template_type, stone_type.stone_name "
	. " FROM pearl_info "
	. " LEFT JOIN stone_type ON pearl_info.p_type_id = stone_type.stone_id "
	. " WHERE pearl_info.item_id = $item_id ";
		
	$pearl_results = mysql_query($get_pearls) or die($get_pearls . '<br />' .  mysql_error());
	if (mysql_num_rows($pearl_results) >= 1) {
		echo "<table class='thin_table'>";
	}	
	
	while($row = mysql_fetch_array($pearl_results)) {
		$pearl_id = $row['p_id'];
		$is_ranged = $row['is_ranged'];
		$stone_name = $row['stone_name'];
		$pearl_quantity = $row['p_quantity'];
		$pearl_weight = $row['p_weight'];
		$pearl_color = $row['p_color'];
		$pearl_shape = $row['p_shape'];
		$pearl_luster = $row['p_luster'];
		$template_type = $row['template_type'];
		
		if ((string)$row['p_x1'] != 0) {
			if($is_ranged == 0) {
				$pearl_measurements = $row['p_x1'] . 'x' . $row['p_x2'];
			}
			else {
				$pearl_measurements = $row['p_x1'] . '-' . $row['p_x2'];
			}
			$pearl_measurements_html = "<tr><td>Measurements:</td><td>$pearl_measurements mm</td></tr>";
		}
		else {
			$pearl_measurements_html = "";
		}
		
		$pearl_color_html = "";
		$pearl_shape_html = "";
		$pearl_luster_html = "";
		
		if ($pearl_color != '') {
			$pearl_color_html = "<tr><td>Body Color:</td><td>$pearl_color;</td></tr>";
		}
		
		if ($pearl_shape != '') {
			$pearl_shape_html = "<tr><td>Shape:</td><td>$pearl_shape</td></tr>";
		}
		
		if($pearl_luster != '') {
			$pearl_luster_html = "<tr><td>Luster:</td><td>$pearl_luster</td></tr>";
		}
		
		
		?>
		<tr>
			<td colspan='2'>
				<b>(<?php echo $pearl_quantity; ?>) <?php echo $stone_name;?>(s)</b>
				<?php
				if (isset($_REQUEST['printer'])) {
				}
				else {
				?>
					[<a href="item_edit_pearl.php?item_id=<?php echo $item_id;?>&pearl_id=<?php echo $pearl_id; ?>&appraisel_id=<?php echo $appraisel_id; ?>">Edit</a>] 
					[<a href="appraisel_upload_plot.php?appraisel_id=<?php echo $appraisel_id; ?>&stone_type=<?php echo $template_type; ?>&gemstone_id=<?php echo $pearl_id; ?>">Upload Gemstone Plot</a>]
				<?php
				}
				?>
			</td>
		</tr>
		<?php
			echo $pearl_measurements_html;
			echo $pearl_color_html;
			echo $pearl_shape_html;
			echo $pearl_luster_html;
			
		?>
		<?php
	}
	if (mysql_num_rows($pearl_results) >= 1) {
		echo "</table>";
	}	

?>