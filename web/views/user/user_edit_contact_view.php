<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Edit Your Customer Contact Information - Lang Antiques</title>
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
				<?php echo anchor('/', 'Home'); ?> &gt; <?php echo anchor('user/user-account', 'Customer Account'); ?> &gt; Edit Contact Information
			</div>
			<h2 id='top_h2'>Edit Your Customer Contact Information</h2>
			<?php echo form_open('user/edit-contact');?>
			<table class='customer_table'>
				<tr>
					<td colspan='2' class='headliner'>Your Contact:</td>
				</tr>
				<tr>
					<td class='title'>First Name:</td>
					<td><input type='text' name='first_name' value='<?php echo set_value('first_name', $customer_data['first_name'])?>' size='40' /></td>
				</tr>
				<tr>
					<td class='title'>Middle:</td>
					<td><input type='text' name='middle_name' value='<?php echo set_value('middle_name', $customer_data['middle_name'])?>' size='40' /></td>
				</tr>				
				<tr>
					<td class='title'>Last Name:</td>
					<td><input type='text' name='last_name' value='<?php echo set_value('last_name', $customer_data['last_name'])?>' size='40' /></td>
				</tr>
				<tr>
					<td class='title'>Email:</td>
					<td><input type='text' name='email' value='<?php echo set_value('email', $customer_data['email'])?>' size='40' /></td>
				</tr>

				<tr>
					<td colspan='2' class='headliner'>Spouse Contact:</td>
				</tr>
				<tr>
					<td class='title'>First Name:</td>
					<td><input type='text' name='spouse_first' value='<?php echo set_value('spouse_first', $customer_data['spouse_first'])?>' size='40' /></td>
				</tr>
				<tr>
					<td class='title'>Middle:</td>
					<td><input type='text' name='spouse_middle' value='<?php echo set_value('spouse_middle', $customer_data['spouse_middle'])?>' size='40' /></td>
				</tr>
				<tr>
					<td class='title'>Last Name:</td>
					<td><input type='text' name='spouse_last' value='<?php echo set_value('spouse_last', $customer_data['spouse_last'])?>' size='40' /></td>
				</tr>
				<tr>
					<td colspan='2' class='headliner'>Phone Numbers:</td>
				</tr>
				<tr>
					<td class='title'>Home Number:</td>
					<td><input type='text' name='home_phone' value='<?php echo set_value('home_phone', $customer_data['home_phone'])?>' size='40' /></td>
				</tr>
				<tr>
					<td class='title'>Work Number:</td>
					<td><input type='text' name='work_phone' value='<?php echo set_value('work_phone', $customer_data['work_phone'])?>' size='40' /></td>
				</tr>
				<tr>
					<td class='title'>Cell Number:</td>
					<td><input type='text' name='cell_phone' value='<?php echo set_value('cell_phone', $customer_data['cell_phone'])?>' size='40' /></td>
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
