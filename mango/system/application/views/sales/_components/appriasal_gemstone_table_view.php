<?php if(sizeof($item_gemstones) > 0):?>
	<table>
	<?php foreach($item_gemstones as $gemstone):?>
		<?php 
			//@TODO move this logic someplace else
			$measurements = '';
			$other_fields = '';
			if((string)$gemstone['measurements'] != null) {
				$measurements = '<tr><td>Measurements:</td><td>' . $gemstone['measurements'] . '</td></tr>';
			}
			if ((string)$gemstone['gem_carat'] != 0) {
				$other_fields .= "<tr><td>Weight:</td><td>" . number_format($gemstone['gem_carat'], 2) . " carats</td></tr>";	
			}
			if ($gemstone['gem_hue'] != "") {
				$other_fields .= "<tr><td>Hue:</td><td>" . $gemstone['gem_hue'] . "</td></tr>";	
			}
			if ($gemstone['gem_tone'] != "") {
				$other_fields .= "<tr><td>Tone:</td><td>" . $gemstone['gem_tone'] . "</td></tr>";	
			}
			if ($gemstone['gem_clarity'] != "") {
				$other_fields .= "<tr><td>Clarity:</td><td>" . $gemstone['gem_clarity'] . "</td></tr>";	
			}
			if ($gemstone['gem_dialogue_number'] != "") {
				$other_fields = "<tr><td>Gem Dialogue Number:</td><td>" . $gemstone['gem_dialogue_number'] . "</td></tr>";	
			}
			if ($gemstone['gem_cut_grade'] != "") {
				$other_fields = "<tr><td>Cut:</td><td>" . $gemstone['gem_cut_grade'] . "</td></tr>";	
			}
		?>
		<tr>
			<td colspan='2'>
				<span style='font-weight: bold;'>(<?php echo $gemstone['gem_quantity']; ?>) <?php echo $gemstone['cut_name']; ?> Cut <?php echo $gemstone['stone_name']; ?>(s) (ID: #<?php echo $gemstone['gem_id']; ?>)</span>
				[<?php echo anchor('/inventory/gemstone/' . $gemstone['item_id'] . '/edit/' . $gemstone['gem_id'], 'Edit')?>]
				[<?php echo anchor('/sales/upload_plot/' . $appraisal_data['appraisel_id'] . '/' . $gemstone['gem_id'] . '/' . $gemstone['template_type'], 'Upload Plot')?>]
			</td>
			
			<?php echo $measurements; ?>
			<?php echo $other_fields; ?>
		</tr>
	<?php endforeach;?>	
	</table>

<?php endif;?>