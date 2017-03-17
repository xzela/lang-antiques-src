<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Our Store in Downtown San Francisco - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>


	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>

	<meta name='keywords' content='' />
	<meta name="description" content="Lang Antiques - Antique and Estate jewelry Buyers and Sellers since 1969. Over 7,000 individual Jewels and Objets d'Art - a veritable museum of the jeweler's art." />

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
				<?php echo anchor('/', 'Home'); ?> &gt; Our Down Town San Francisco Store
			</div>
			<h2 id='top_h2'>Our Downtown Store in San Francisco</h2>
			<?php //echo $content; ?>

			<?php echo snappy_image('photos/store-ext.jpg', 'Store view from the sidewalk', '', 'style="float: left; margin: .2em; padding: .2em;"');?>
			<h3>Welcome! </h3>
			<p style="margin-bottom: 0px; padding-bottom: 0px;">
				Lang Antique &amp; Estate Jewelry was originally established in 1969 by its namesake, Jarmilla Lang, just a few doors down from its present location in San Francisco's Union Square.
			</p>
				<?php echo snappy_image('photos/store_wint.jpg', 'Window view of our display case from outside', '', 'style="float: right; margin: .2em; padding: .2em;"');?>
			<p>
				From her original home in Czechoslovakia, Mrs. Lang brought to San Francisco old world refinement and taste along with a wide ranging expertise in antique jewelry.
				Lang's still stands out as a refreshing reminder of vanished times amid the corporate clones which dominate today's jewelry trade.
			</p>
			<p>
				The spellbinding window alone stops unsuspecting passersby in their tracks.
				They sometimes ogle for an hour at a time.
				Lang's entertains visitors from all over the United States, Europe and Asia who say they have never seen a store like it anywhere in the world.
			</p>
				<?php echo snappy_image('photos/store_int.jpg', 'Inside view from the door', '', 'style="float: left; margin: .2em; padding: .2em;"');?>
			<p>
				Upon entering the store one is immediately transported back a century to cozier, quainter times.
				The cases brim over with sparkling one-of-a-kind baubles: rings, earrings, bracelets, brooches, necklaces, timepieces, and old silver from days of yore.
				Lang's staggering collection of over seven thousand individual jewels and objets d'art is a veritable museum of the jeweler's art.
			</p>
			<p>
				Today Lang's is headed by Suzanne Martinez, GG, a native San Franciscan who along with a dedicated staff of jewelry experts and professionals has developed Lang's into one of the foremost estate jewelers in the world.
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
