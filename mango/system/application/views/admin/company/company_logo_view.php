<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - Company Logo </title>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	
	<script type="text/javascript">
	var json = {"fields" : {}};

	$(document).ready(function () {
		var content = $('.input_field').val();
		$.each($('.input_field'), function(i, val) {
			json.fields[i] = {"name" : this.name, "value" : this.value};
		}); 
		
		$('.input_field').bind('keyup change', function(event) {
			var index = $('.input_field').index(this); 
			var content = json.fields[index].value;
			
			//alert(content + '=' + $(this).val());
			var div = $('#change_message');
			if($(this).val() != content) {
				$(this).css('color', 'red');
				$(this).css('border', '1px solid red');
				if(div.is(':hidden')) {
					div.slideDown('slow');
				}
			}
			else {
				$(this).css('color', 'black');
				$(this).css('border', '1px solid #333333');
				var b = true;
				$.each($('.input_field'), function(i, val) {
					if(this.value != json.fields[i].value) {
						b = false;
					}
				});
				if(b) {
					div.slideUp('slow');
				}
			}			
		});
	});
	</script>	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Company Logo</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
		</ul>
		<p>
			You can modify the Company Logo.
			This Logo will show up on Invoices and Memos. <br />
			The image needs to be <strong>160px in length</strong> and <strong>100px in height</strong>. <br />
			 
		</p>
		<div>
		<?php echo form_open_multipart('admin/company_logo'); ?>
			<table class="form_table">
				<tr> 
					<td class="title">Upload Logo:</td>
					<td><input name="logo" type="file" /></td>
				</tr>
				<tr>
					<td class='title'>Current Logo:</td>
					<td>
						<?php if(sizeof($logo_data) > 0): ?>
							<img src='<?php echo $logo_data['image_location']; ?>' />
						<?php else: ?>
							No Logo Yet...
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td colspan='2'>
						<div class='warning'><?php echo $messages; ?></div>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input name='submit_logo' type='submit' value='Upload Logo' />
						|
						<?php echo anchor('admin/', 'Cancel'); ?>
					</td>
				</tr>
			</table>
		<?php echo form_close(); ?>	
		</div>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>