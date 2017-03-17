<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Customer Account - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>
	
	
	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>
	
	<meta name='keywords' content='' />
	<meta name="description" content="Lang Antiques Customer Account Page. Please Enjoy Your Stay!" />
	
	<style type='text/css'>
	.address_table {
	}
	.address_title {
		font-weight: bold;
		vertical-align: top;
		text-align: right;
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
				<?php echo anchor('/', 'Home'); ?> &gt; Customer Account
			</div>
			<h2 id='top_h2'>Customer Account At Lang Antiques</h2>
			<h3>Welcome <?php echo $customer_data['first_name'] . ' ' . $customer_data['last_name']; ?></h3>
			<h4>Personal Information: <span class='small_text'>[<?php echo anchor('user/edit-contact', 'Edit'); ?>]</span></h4>
			<table class='address_table'>
				<tr>
					<td class='address_title' >Name:</td>
					<td class='address_field' ><?php echo $customer_data['first_name'] . ' ' . $customer_data['last_name']; ?></td>
				</tr>
				<tr>
					<td class='address_title' >Spouse:</td>
					<td class='address_field' ><?php echo $customer_data['spouse_first'] . ' ' . $customer_data['spouse_last']; ?></td>
				</tr>
				<tr>
					<td class='address_title' >Home Phone:</td>
					<td class='address_field' ><?php echo $customer_data['home_phone']; ?></td>
				</tr>
				<tr>
					<td class='address_title' >Work Phone:</td>
					<td class='address_field' ><?php echo $customer_data['work_phone'];?></td>
				</tr>
			</table>
			<h4>Mailing Address <span class='small_text'>[<?php echo anchor('user/edit-mailing-address', 'edit'); ?>]</span></h4>
				<?php if($customer_data['address'] == ''): ?>
					<p>
						Our records indicate we did not have a mailing address on file. 
						If you would like to receive our seasonal greeting cards, please add your mailing address today. 
						<br />
						Click here to <?php echo anchor('user/edit-mailing-address', 'Edit Your Mailing Address'); ?>.
					</p>
				<?php else: ?>
				<table class='address_table'>
					<tr>
						<td class='address_title' >Address:</td> 
						<td class='address_field'>
							<?php echo $customer_data['address']; ?>
							<?php if($customer_data['address2'] != ''): ?>
								<br /> <?php echo $customer_data['address2']; ?>
							<?php endif;?>
						</td>
					</tr>
					<tr>
						<td class='address_title'>City:</td> 
						<td class='address_field'><?php echo $customer_data['city']; ?></td>
					</tr>
					<tr>
						<td class='address_title'>Zip/State:</td> 
						<td class='address_field'><?php echo $customer_data['zip']; ?> <?php echo $customer_data['state']; ?></td>
					</tr>
					<tr>
						<td class='address_title'>Country:</td> 
						<td class='address_field'><?php echo $customer_data['country']; ?> </td>
					</tr>
					
				<?php endif; ?>
			</table>
			<h4>Shipping Address <span class='small_text'>[<?php echo anchor('user/edit-shipping-address', 'edit')?>]</span></h4>
			<?php if($customer_data['ship_address'] == ''):?>
				<p>
					We couldn't find your shipping address in our files. <br />
					If you would like you may <?php echo anchor('user/edit-shipping-address', 'enter a new address')?> now.
				</p>
			<?php else: ?>
				<table class='address_table'>
				<tr>
					<td class='address_title'>Ship Contact:</td> 
					<td class='address_field'><?php echo $customer_data['ship_contact']; ?></td>
				</tr>
				<tr>
					<td class='address_title'>Ship Phone:</td> 
					<td class='address_field'><?php echo $customer_data['ship_phone']; ?></td>
				</tr>
				
				<tr>
					<td class='address_title'>Address:</td> 
					<td class='address_field'>
						<?php echo $customer_data['ship_address']; ?>
						<?php if($customer_data['ship_address2'] != ''): ?>
							<br />
							<?php echo $customer_data['ship_address2']; ?>
						<?php endif;?>
					</td>
				</tr>
				<tr>
					<td class='address_title'>City:</td> 
					<td class='address_field'><?php echo $customer_data['ship_city']; ?></td>
				</tr>
				<tr>
					<td class='address_title'>State/Zip:</td> 
					<td class='address_field'>
						<?php echo $customer_data['ship_state']; ?>
						<?php echo $customer_data['ship_zip']; ?> 
					</td>
				</tr>
				<tr>
					<td class='address_title'>Country:</td> 
					<td class='address_field'><?php echo $customer_data['ship_country']; ?></td>
				</tr>				
				</table>
			<?php endif; ?>			
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
