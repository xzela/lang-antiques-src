<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Edit Your Customer Mailing Information - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>
	
	
	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>
	
	<meta name='keywords' content='' />
	<meta name="description" content="Lang Antiques Customer Account Page. Please Enjoy Your Stay!" />
	
	<style type='text/css'>
		table.customer_table {
			border: 1px solid #60272F;
		}
		table.customer_table td.title {
			border-right: 1px dashed #60272F;  
			text-align: right;
		}
		
		table.customer_table td.headliner {
			font-weight: bold;
			font-style: italic;
			border-bottom: 1px dashed #60272F;  
		}
	</style>
</head>
<body>
	<div id="container" >
		<span class="rtop">
			<b class="r1"></b>
			<b class="r2"></b>
			<b class="r3"></b>
			<b class="r4"></b>
		</span>
	<?php
		$this->load->view('components/header_view');
		$this->load->view('components/menu_view');
	?>
		<div id="content">
			<div class="breadcrumb">
				<?php echo anchor('/', 'Home'); ?> &gt; <?php echo anchor('user/user-account', 'Customer Account'); ?> &gt; Edit Mailing Information
			</div>
			<h2 id='top_h2'>Edit Your Customer Mailing Information</h2>
			<?php echo form_open('user/edit-mailing-address');?>
			<table class='customer_table'>
				<tr>
					<td colspan='2' class='headliner'>Your Mailing Address:</td>
				</tr>
				<tr>
					<td class='title'>Address Line 1:</td>
					<td><input type='text' name='address' value='<?php echo set_value('address', $customer_data['address'])?>' size='80' /></td>
				</tr>
				<tr>
					<td class='title'>Address Line 2:</td>
					<td><input type='text' name='address2' value='<?php echo set_value('address2', $customer_data['address2'])?>' size='80' /></td>
				</tr>				
				<tr>
					<td class='title'>City:</td>
					<td><input type='text' name='city' value='<?php echo set_value('city', $customer_data['city'])?>' size='40' /></td>
				</tr>
				<tr>
					<td class='title'>State/Zip:</td>
					<td>
						<input type='text' name='state' value='<?php echo set_value('state', $customer_data['state'])?>' size='2' maxlength='2' />
						/ <input type='text' name='zip' value='<?php echo set_value('zip', $customer_data['zip'])?>' size='10' />
					</td>
				</tr>
				<tr>
					<td class='title'>Country:</td>
					<td><input type='text' name='country' value='<?php echo set_value('country', $customer_data['country'])?>' size='40' /></td>
				</tr>
				<tr>
					<td colspan='2'><?php echo validation_errors(); ?></td>
				</tr>
				<tr>
					<td></td>
					<td><input type='submit' value='Change Information' /> | <?php echo anchor('user/user-account', 'Cancel');?></td>
				</tr>
			</table>
			<?php echo form_close();?>
		</div>
	<?php $this->load->view('components/footer_view.php'); ?>
		<span class="rbottom">
			<b class="r4"></b>
			<b class="r3"></b>
			<b class="r2"></b>
			<b class="r1"></b>
		</span>		
	</div>	
</body>
</html>
<?php
ob_flush();
?>
