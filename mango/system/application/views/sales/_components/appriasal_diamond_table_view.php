<?php if(sizeof($item_diamonds) > 0):?>
	<table>
	<?php foreach($item_diamonds as $diamond):?>
		<?php 
			//@TODO move this logic someplace else
			$measurements = '';
			$weight = '';
			$color = '';
			$clarity = '';
			$other_fields = '';
			
			if((string)$diamond['measurements'] != null) {
				$measurements = '<tr><td>Measurements:</td><td>' . $diamond['measurements'] . '</td></tr>';
			}
			if ((string)$diamond['d_carats'] != 0) {
				$weight = "<tr><td>Weight:</td><td>" . number_format($diamond['d_carats'], 2) . " carats</td></tr>";	
			}
			if((string)$diamond['color'] != null) {
				$color = '<tr><td>Color:</td><td>' . $diamond['color'] . '</td></tr>';
			}
			if((string)$diamond['clarity'] != null) {
				$clarity = '<tr><td>Clarity:</td><td>' . $diamond['clarity'] . '</td></tr>';
			}
			
			if($diamond['is_center']) {

					
					//Depth Percentage
					if ($diamond['d_depth_percnt'] == 0 || $diamond['d_depth_percnt'] == "" )  {
						$depth_percnt = "";
					}
					else {
						$depth_percnt = number_format($diamond['d_depth_percnt'], 1);
						$other_fields .= "<tr><td>Depth Percentage:</td><td>$depth_percnt %</td></tr>";
					}
					
					
					//Table Percentage
					if ($diamond['d_table_percnt'] == 0 || $diamond['d_table_percnt'] == "" )  {
						$table_percnt = "";
					}
					else {
						$table_percnt = number_format($diamond['d_table_percnt']);
						$other_fields .= "<tr><td>Table Percentage:</td><td>$table_percnt %</td></tr>";
					}
					
					
					//Girdle
					if ($diamond['d_girdle_thick'] == "" )  {
						$girdle = "";
					}
					else {
						$girdle = $diamond['d_girdle_thick'];
						$other_fields .= "<tr><td>Girdle:</td><td>$girdle</td></tr>";
					}
					
					
					//Culet
					if ($diamond['d_culet'] == '')  {
						$culet = "";
					}
					else {
						$culet = $diamond['d_culet'];
						$other_fields .= "<tr><td>Culet:</td><td>$culet</td></tr>";
					}
				}
				?>
		<tr>
			<td colspan='2'>
				<span style='font-weight: bold;'>(<?php echo $diamond['d_quantity']; ?>) <?php echo $diamond['cut_name']; ?> Cut <?php echo $diamond['stone_name']; ?>(s) (ID: #<?php echo $diamond['d_id']; ?>)</span>
				[<?php echo anchor('/inventory/diamond/' . $diamond['item_id'] . '/edit/' . $diamond['d_id'], 'Edit Diamond Details')?>]
				[<?php echo anchor('/sales/upload_plot/' . $appraisal_data['appraisel_id'] . '/' . $diamond['d_id'] . '/' . $diamond['template_type'], 'Change/Upload Plot Image')?>]
			</td>
			
			<?php echo $measurements; ?>
			<?php echo $weight; ?>
			<?php echo $color; ?>
			<?php echo $clarity; ?>
			<?php echo $other_fields; ?>
		</tr>
	<?php endforeach;?>	
	</table>

<?php endif;?>