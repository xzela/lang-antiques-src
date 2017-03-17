<?php 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 

	
	<title>Print Credit Card Information</title>
	
	<script type="text/javascript">
	window.onload = function() {
		window.print();
	}
	</script>
	<style type='text/css'>
	body {
		font-family: Lucida Grande, Verdana, Sans-serif;
		font-size: 14px;
		color: #4F5155;
	
	}	
	#print_body {
		width: 950px;
		margin: 0 auto;
	}
	
	h1 {
		border-bottom: 1px solid #999999;
	}
	h3 {
		border-top: 1px solid #999999;
		border-bottom: 1px dashed #999999;
		border-left: 1px solid #999999;
		border-right: 1px solid #999999;
		background-color: #dfdfdf;
		padding: 5px;
		margin-bottom: 0px;
		
	}
	table {
		padding-top: 10px;
		width: 100%;
		border-bottom: 1px solid #999999;
		border-left: 1px solid #999999;
		border-right: 1px solid #999999;
	}
	table td.title {
		width: 120px;
		text-align: right;
	}
	</style>	
</head>
<body id='print_body'>
	<h1>Credit Card Information for <?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?></h1>
	
	<h3>Credit Card Information:</h3>
	<table>
		<tr>
			<td class='title'>Card Holder:</td>
			<td><?php echo $card_data['card_holder']; ?></td>
		</tr>
		<tr>
			<td class='title'>Card Number:</td>
			<td><?php echo $card_data['card_number']; ?></td>
		</tr>
		<tr>
			<td class='title'>Expiration Date:</td>
			<td><?php echo $card_data['card_month'] . '/' . $card_data['card_year']; ?></td>
		</tr>
		<tr>
			<td class='title'>CVV Number:</td>
			<td><?php echo $card_data['card_cvv']; ?></td>
		</tr>
	</table>
	<h3>Billing Address:</h3>
	<table>
		<tr>
			<td class='title'>Address:</td>
			<td><?php echo $buyer_data['address']; ?></td>
		</tr>
		<tr>
			<td class='title'>City:</td>
			<td><?php echo $buyer_data['city']; ?></td>
		</tr>
		<tr>
			<td class='title'>State/Zip:</td>
			<td><?php echo $buyer_data['state'] . '/' . $buyer_data['zip']; ?></td>
		</tr>
	</table>
	<h3>Notes:</h3>
	<table>
		<tr>
			<td><?php echo $invoice_data['notes']; ?></td>
		</tr>
		
	</table>
</body>
</html>