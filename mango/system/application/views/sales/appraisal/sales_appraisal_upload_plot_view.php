<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title><?php echo $this->config->item('project_name'); ?> - Appraisal Upload Plot </title>
	<?php echo snappy_script('ajax/prototype.js'); ?>
	<?php echo snappy_script('ajax/scriptaculous.js'); ?>
	<?php echo snappy_script('ajax/controls.js'); ?>
	<?php echo snappy_script('ajax/effects.js'); ?>
	<?php echo snappy_script('inventory/photograph.js'); ?>
	<script type='text/javascript'>
		var base_url = '<?php echo base_url(); ?>';
	</script>
	
	<style>
	.area {
		border: 1px solid #999;
		padding: 5px;
		margin: 5px;
		background-color: #efefef;
		
	}
	#sort_images {
		
	}
	#sort_images li {
		padding: 5px;
		margin: 5px;
		border: 1px solid #d9d9d9;
		background-color: #fcfcfc;
	}
	#sort_images li img {
		padding: 5px;
		margin: 5px;
		border: 1px solid #d9d9d9;
		background-color: #fff;
	}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Upload Appraisal Plot</h2>
		<ul id='submenu'>
			<li><?php echo anchor('sales/appraisal/' . $appraisal_data['appraisal_id'], 'Back to Appraisal'); ?></li>
		</ul>
		<h3>Select a File to Upload</h3>
		<div class='area'>
			<?php $attr = array('enctype' => 'multipart/form-data'); ?>
			<?php echo form_open('sales/upload_plot/' . $appraisal_data['appraisal_id'] . '/' . $appraisal_data['stone_id'] . '/' . $appraisal_data['template_type'], $attr);?>
				<table>
					<tr>
						<td>File:</td>
						<td>
							<input id="imgfile" name="imgfile" type="file" size='70'/>
							<p class='warning'>Plot Images can only be 500px by 500px. Anything larger causes problems.</p>
						</td>
					</tr>
					<tr>
						<td>Plotting Symbols:</td>
						<td>
							<textarea name='plot_symbols' rows="4" cols="40"><?php echo set_value('plot_symbols'); ?></textarea>
						</td>
					</tr>
					<tr>
						<td>Plotting Comments:</td>
						<td>
							<textarea name='plot_comments' rows="4" cols="40"><?php echo set_value('plot_commets'); ?></textarea>
						</td>
					</tr>
					<tr>
						<td></td>
						<td><input id="upload_submit" name="upload_submit" type="submit"  value='Upload Image' /></td>
					</tr>
					<tr>
						<td></td>
						<td class='warning'><?php echo $upload_errors; ?></td>
					</tr>
				</table>
			<?php echo form_close(); ?>
		<?php if(sizeof($current_plot) > 0):?>
		<h3>Current Plot</h3>
			<img src='<?php echo $current_plot['image_location']; ?>' />
			[<?php echo anchor('sales/remove_plot/' . $current_plot['appraisal_image_id'], 'Remove Plot')?>]
		<?php endif;?>
		</div>
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>