<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Contact Us at Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>

	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>


	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>

	<meta name='keywords' content='' />
	<meta name="description" content="Contact Information for Buying or Selling your Antique and Estate Jewelry at Lang Antiques" />
	<style type='text/css'>
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
				<?php echo anchor('/', 'Home'); ?> &gt; Contact Us
			</div>
			<h2 id='top_h2'>Contact Us</h2>
			<h3>Address:</h3>
			<p>Lang Antique &amp; Estate Jewelry <br />
				323 Sutter Street <br />
				San Francisco, CA 94108<br />
				Google Map <a target="_blank" href="http://maps.google.com/maps?f=q&hl=en&q=323+sutter+st,+94108&ie=UTF8&om=1&ll=37.789438,-122.405806&spn=0.096589,0.160675&z=13&iwloc=addr">directions</a>
			</p>
			<h3>Phone or Fax:</h3>
			<p>
				<?php //OrangeSoda tracking number ?>
				<?php //Toll Free: (877) 637-4981 <br /> ?>
				Toll Free:  800-924-2213 <br />
				Local to San Francisco: (415) 982-2213<br />
				Fax Number: (415) 986-8855
			</p>


			<h3>Business Hours:</h3>
			<p>Our staff is available to take your questions, calls, and emails between the hours of 10:30am and 5:30pm PST Monday thru Saturday.</p>
 			<table style=''>
				<tr>
					<td>Monday</td>
					<td>10:30am - 5:30pm</td>
				</tr>
				<tr>
					<td>Tuesday</td>
					<td>10:30am - 5:30pm</td>
				</tr>
				<tr>
					<td>Wednesday</td>
					<td>10:30am - 5:30pm</td>
				</tr>
				<tr>
					<td>Thursday</td>
					<td>10:30am - 5:30pm</td>
				</tr>
				<tr>
					<td>Friday</td>
					<td>10:30am - 5:30pm</td>
				</tr>
				<tr>
					<td>Saturday</td>
					<td>10:30am - 5:30pm</td>
				</tr>
				<tr>
					<td>Sunday</td>
					<td style='text-align: center;'>Closed</td>
				</tr>
			</table>

<!-- 			<div style="border: 1px solid #666; padding: 10px; margin: 5px; margin-bottom: 20px; border-radius: 5px; background-color: #f1f1f1;">
			<h2>Holiday Store Hours: December 10th - 24th</h2>
				<p>
					Our staff is available to take your questions, calls, and emails between the holiday hours of 10:30am and 6:00pm PST Monday through Saturday.
					Until Christmas we will be open on Sunday between 11:00am and 5:00pm.
				</p>
				<table style='text-align: left;'>
					<tr>
						<td>Monday</td>
						<td>10:30am - 6:00pm</td>
					</tr>
					<tr>
						<td>Tuesday</td>
						<td>10:30am - 6:00pm</td>
					</tr>
					<tr>
						<td>Wednesday</td>
						<td>10:30am - 6:00pm</td>
					</tr>
					<tr>
						<td>Thursday</td>
						<td>10:30am - 6:00pm</td>
					</tr>
					<tr>
						<td>Friday</td>
						<td>10:30am - 6:00pm</td>
					</tr>
					<tr>
						<td>Saturday</td>
						<td>10:30am - 6:00pm</td>
					</tr>
					<tr>
						<td>Sunday</td>
						<td>11:00am - 5:00pm</td>
					</tr>
					<tr>
						<td>Monday Christmas Eve</td>
						<td>10:30am - 3:00pm</td>
					</tr>
					<tr>
						<td>Monday New Years Eve</td>
						<td>10:30am - 3:00pm</td>
					</tr>
				</table>
			</div> -->
			<!--
			<h3>Holiday Business Hours:</h3>
			<p>
				Our staff is available to take your questions, calls, and emails between the holiday hours of 10:30am and 6:00pm PST Monday thru Saturday.
				Until Christmas we will be open on Sunday between 11:00am and 5:00pm.
				We'll also be open on Christmas Eve, Thursday Dec 24th, 2009 between 10:30am and 3:00pm.
			</p>
			<table style=''>
				<tr>
					<td>Monday</td>
					<td>10:30am - 6:00pm</td>
				</tr>
				<tr>
					<td>Tuesday</td>
					<td>10:30am - 6:00pm</td>
				</tr>
				<tr>
					<td>Wednesday</td>
					<td>10:30am - 6:00pm</td>
				</tr>
				<tr>
					<td>Thursday</td>
					<td>10:30am - 6:00pm</td>
				</tr>
				<tr>
					<td>Friday</td>
					<td>10:30am - 6:00pm</td>
				</tr>
				<tr>
					<td>Saturday</td>
					<td>10:30am - 6:00pm</td>
				</tr>
				<tr>
					<td>Sunday</td>
					<td>11:00am - 5:00pm</td>
				</tr>
				<tr>
					<td>New Years Eve</td>
					<td>10:30am - 3:00pm</td>
				</tr>
				<tr>
					<td>Christmas Eve</td>
					<td>10:30am - 3:00pm</td>
				</tr>
				<tr>
					<td>Christmas Day</td>
					<td style='text-align: center'>Closed</td>
				</tr>
				<tr>
					<td>New Year Day</td>
					<td style='text-align: center'>Closed</td>
				</tr>
			</table>

			<h3>Holiday Business Hours:</h3>
			<table style=''>
				<tr>
					<td>New Years Eve</td>
					<td>10:30am - 3:00pm</td>
				</tr>
				<tr>
					<td>New Year Day</td>
					<td style='text-align: center'>Closed</td>
				</tr>
			</table>
						-->
			<h3>Email:</h3>
			<p>
				If you have a question about any product, please feel free to email us at - <a href="mailto:info@langantiques.com">info@langantiques.com</a>
			</p>
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
