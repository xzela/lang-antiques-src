<?php

?>

<table id="gemstone_table">
	<tr>
		<th>Gemstone</th>
		<th>Center Stone</th>
		<th>Shape/Cut</th>
		<th>Weight</th>
		<th>Color</th>
		<th>Clarity</th>
		<th>Quantity</th>
		<th>Notes</th>
		<th>Options</th>
	</tr>
	<?php 
		$center_array = array(0=>'No', 1=> snappy_image('icons/star.png') . 'Yes ');
		$total_diamond_weight = 0;
		$total_diamond_quantity = 0;
	?>
	<tr>
		<td class='stone_title'><strong><?php echo snappy_image('icons/diamond.png');?> Diamonds:</strong></td> 
		<td class='stone_title' colspan='8'>[<?php echo anchor('inventory/diamond/' . $item_data['item_id'] . '/add', 'Add Diamonds', 'class="green"');?>]</td>
	</tr>
	<?php foreach($diamonds as $dia): ?>
		<?php $total_diamond_quantity += $dia['d_quantity']; ?>
		<?php $total_diamond_weight += $dia['d_carats']; ?>
		<tr>
			<td><?php echo anchor('inventory/diamond/' . $item_data['item_id'] . '/edit/' . $dia['d_id'], $gemstone_names[$dia['d_type_id']]['stone_name']); ?></td>
			<td><?php echo $center_array[$dia['is_center']]; ?></td>
			<td><?php echo $dia['diamond_shape']; ?></td>
			<td><?php echo number_format($dia['d_carats'], 3); ?></td>
			<td><?php echo $dia['color']; ?></td>
			<td><?php echo $dia['clarity']; ?></td>
			<td><?php echo $dia['d_quantity']; ?></td>
			<td><?php echo $dia['d_cert_by']; ?> <?php echo $dia['d_cert_num']; ?></td>
			<td style='text-align: center;'>
				[<?php echo anchor('inventory/diamond/' . $item_data['item_id'] . '/edit/' . $dia['d_id'], 'Edit'); ?>]
			</td>
		</tr>
	<?php endforeach; ?>
	<?php if(sizeof($diamonds) >= 1): ?>
		<tr>
			<td class='totals' colspan='3'>Totals:</td>
			<td colspan='3'><?php echo number_format($total_diamond_weight,2); ?></td>
			<td><?php echo $total_diamond_quantity; ?></td>
		</tr>
	<?php endif; ?>
	<tr>
		<td class='stone_title'><strong><?php echo snappy_image('icons/ruby.png');?> Gemstones:</strong></td>
		<td class='stone_title' colspan='8'>[<?php echo anchor('inventory/gemstone/' . $item_data['item_id'] . '/add', 'Add Gemstones', 'class="green"'); ?>]</td>
	</tr>
	<?php foreach($gemstones as $gem): ?>
		<tr>
			<td><?php echo anchor('inventory/gemstone/' . $item_data['item_id'] . '/edit/' . $gem['gem_id'], $gemstone_names[$gem['gem_type_id']]['stone_name']); ?></td>
			<td><?php echo $center_array[$gem['is_center']]; ?></td>
			<td><?php echo $gem['gemstone_shape']; ?> </td>
			<td><?php echo number_format($gem['gem_carat'], 3); ?></td>
			<td class='empty'></td>
			<td class='empty'></td>
			<td><?php echo $gem['gem_quantity']; ?></td>
			<td><?php echo $gem['gem_notes']; ?></td>
			<td style='text-align: center;'>
				[<?php echo anchor('inventory/gemstone/' . $item_data['item_id'] . '/edit/' . $gem['gem_id'], 'Edit'); ?>]
			</td>
		</tr>
	<?php endforeach; ?>
	
	<tr>
		<td class='stone_title'><strong><?php echo snappy_image('icons/pearl.png');?> Pearls:</strong> </td>
		<td class='stone_title' colspan='8'>[<?php echo anchor('inventory/pearl/' . $item_data['item_id'] . '/add', 'Add Pearls')?>]</td>
	</tr>
	<?php foreach($pearls as $pearl): ?>
		<tr>
			<td><?php echo anchor('inventory/pearl/' . $item_data['item_id'] . '/edit/' . $pearl['p_id'], $gemstone_names[$pearl['p_type_id']]['stone_name']); ?></td>
			<td><?php echo $center_array[$pearl['is_center']]; ?></td>
			<td><?php echo $pearl['p_shape']; ?></td>
			<td><?php echo number_format($pearl['p_weight'], 3); ?></td>
			<td class='empty'></td>
			<td class='empty'></td>
			<td><?php echo $pearl['p_quantity']; ?></td>
			<td><?php echo $pearl['p_notes']; ?></td>
			<td style='text-align: center;'>
				[<?php echo anchor('inventory/pearl/' . $item_data['item_id'] . '/edit/' . $pearl['p_id'], 'Edit'); ?>]
			</td>
		</tr>
	<?php endforeach; ?>

	<tr>
		<td class='stone_title'><strong><?php echo snappy_image('icons/jadeite.png');?> Jadeite:</strong> </td>
		<td class='stone_title' colspan='8'>[<?php echo anchor('inventory/jadeite/' . $item_data['item_id'] . '/add', 'Add Jadeite', 'class="green"')?>]</td>
	</tr>
	<?php foreach($jadeite as $jade): ?>
		<tr>
			<td><?php echo anchor('inventory/jadeite/' . $item_data['item_id'] . '/edit/' . $jade['j_id'], $gemstone_names[$jade['j_type_id']]['stone_name']); ?></td>
			<td><?php echo $center_array[$jade['is_center']]; ?></td>
			<td><?php echo $jade['j_cut']; ?></td>
			<td><?php echo number_format($jade['j_carat'], 3); ?></td>
			<td class='empty'></td>
			<td class='empty'></td>
			<td><?php echo $jade['j_quantity']; ?></td>
			<td><?php echo $jade['j_notes']; ?></td>
			<td style='text-align: center;'>
				[<?php echo anchor('inventory/jadeite/' . $item_data['item_id'] . '/edit/' . $jade['j_id'], 'Edit'); ?>]
			</td>
		</tr>
	<?php endforeach; ?>


	<tr>
		<td class='stone_title'><strong><?php echo snappy_image('icons/opal.png');?> Opal: </strong> </td>
		<td class='stone_title' colspan='8'>[<?php echo anchor('inventory/opal/' . $item_data['item_id'] . '/add', 'Add Opals', 'class="green"')?>]</td>
	</tr>
	<?php foreach($opals as $opal): ?>
		<tr>
			<td><?php echo anchor('inventory/opal/' . $item_data['item_id'] . '/edit/' . $opal['o_id'], $gemstone_names[$opal['o_type_id']]['stone_name']); ?></td>
			<td><?php echo $center_array[$opal['is_center']]; ?></td>
			<td><?php echo $opal['opal_shape']; ?></td>
			<td><?php echo number_format($opal['o_carat'], 3); ?></td>
			<td class='empty'></td>
			<td class='empty'></td>
			<td><?php echo $opal['o_quantity']; ?></td>
			<td><?php echo $opal['o_notes']; ?></td>
			<td style='text-align: center;'>
				[<?php echo anchor('inventory/opal/' . $item_data['item_id'] .'/edit/' . $opal['o_id'], 'Edit'); ?>]
			</td>
		</tr>
	<?php endforeach; ?>		
</table>