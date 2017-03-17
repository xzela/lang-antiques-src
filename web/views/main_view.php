<?php
ob_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Lang Antique Jewelry, Estate Jewelry Buyers and Sellers.</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="Buyers and Sellers of authentic Fine Estate Jewelry and Fine Antique Jewelry since 1969. We also specialize in original vintage diamond engagement rings from all periods. Large and extensive collection." />
	<meta name="keywords" content="Antique Jewelry, Estate Jewelry, Antique Engagement Rings, Jewelry Buyers, Vintage Jewelry, Diamond Buyers" />
	<?php echo snappy_script('browser.selector.js');?>
	<?php $this->load->view('components/global.includes.php'); ?>

	<?php echo snappy_script('jquery/jquery.cycle.core.js');?>
	<?php echo snappy_script('jquery/jquery.cycle.trans.js');?>

	<script type="text/javascript">
		$(function() {
			$('#fadeimages').cycle({
				fx: 'fade',
				pause: true,
				pauseOnPagerHover: true,
				timeout: 3000
				});
		});
		if (window != top) top.location.href = location.href;
	</script>
	<style type='text/css'>
		.fadeimages {
			height:  440px;
			width:   600px;
			padding: 0;
			margin:  0px auto;
		}

		.fadeimages img {
			border-radius: 5px;
			padding: 15px;
			border:  1px solid #963D48;
			background-color: #eee;
			width:  600px;
			height: 400px;
			top:  0;
			left: 0
		}
		.notications {
			border-radius: 5px;
			border: 1px solid #963D48;
			background-color: #F9F9F7;
			padding: 5px;
			margin: 10px;
			margin-top: 0px;
			font-size: 18px;
		}
		.video_frame {
			padding: 5px;
			border-radius: 5px;
			border: 1px solid #963D48;
			background-color: #F9F9F7;
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
			<div class="notications">Check out our <a href="#video">newest video</a>!</div>
				<div id='fadeimages' class='fadeimages'>

					<?php //echo anchor('products/category/diamond-rings', snappy_image('fade_images/xmas_2009/lang_diamond_rings_xmas.jpg', 'Antique and Estate Diamond Rings'));?>
					<?php //echo anchor('products/category/gemstone-rings', snappy_image('fade_images/xmas_2009/lang_gemstone_rings_xmas.jpg', 'Antique and Estate Gemstone Rings'));?>
					<?php //echo anchor('products/category/earrings', snappy_image('fade_images/xmas_2009/lang_earrings_xmas.jpg', 'Antique and Estate Diamond Earrings'));?>
					<?php //echo anchor('products/category/pins-and-brooches', snappy_image('fade_images/xmas_2009/lang_pins_brooches_xmas.jpg', 'Antique and Estate Diamond Pins and Brooches'));?>
					<?php //echo anchor('products/category/bracelets', snappy_image('fade_images/xmas_2009/lang_bracelets_xmas.jpg', 'Antique and Estate Diamond Bracelets'));?>
					<?php //echo anchor('products/category/necklaces', snappy_image('fade_images/xmas_2009/lang_necklaces_xmas.jpg', 'Antique and Estate Diamond Necklaces'));?>
					<?php //echo anchor('products/category/wedding-bands', snappy_image('fade_images/xmas_2009/lang_wedding_bands_xmas.jpg', 'Antique and Estate Wedding Bands'));?>
					<?php //echo anchor('products/type/victorian', snappy_image('fade_images/xmas_2009/lang_victorian_xmas.jpg', 'Antique Diamond Victorian Jewelry'));?>
					<?php //echo anchor('products/type/edwardian', snappy_image('fade_images/xmas_2009/lang_edwardian_xmas.jpg', 'Antique Diamond Edwardian Jewelry'));?>
					<?php //echo anchor('products/type/art-nouveau', snappy_image('fade_images/xmas_2009/lang_art_nouveau_xmas.jpg', 'Antique and Estate Art Nouveau Jewelry'));?>
					<?php //echo anchor('products/type/art-deco', snappy_image('fade_images/xmas_2009/lang_art_deco_xmas.jpg', 'Diamond Art Deco Jewelry'));?>
					<?php //echo anchor('products/type/retro', snappy_image('fade_images/xmas_2009/lang_retro_xmas.jpg', 'Diamond and Ruby Retro Jewelry'));?>
					<?php //echo anchor('products/type/contemporary', snappy_image('fade_images/xmas_2009/lang_modern_xmas.jpg', 'Modern Diamond Jewelry'));?>
					<?php //echo anchor('products/type/georgian', snappy_image('fade_images/xmas_2009/lang_georgian_xmas.jpg', 'Antique Georgian Jewelry'));?>
					<?php //echo anchor('products/type/gentlemans-jewelry', snappy_image('fade_images/xmas_2009/lang_gents_xmas.jpg', 'Gents Jewelry'));?>
					<!-- <?php //echo anchor('search/pearls', snappy_image('fade_images/xmas_2009/lang_pearls_xmas.jpg'));?> -->
					<?php //echo anchor('products/type/cameos', snappy_image('fade_images/xmas_2009/lang_cameos_xmas.jpg', 'Antique and Estate Cameos'));?>

					<?php echo anchor('products/category/diamond-rings', snappy_image('fade_images/2012.08/lang_diamond_rings.jpg', 'Antique and Estate Diamond Rings'));?>
					<?php echo anchor('products/category/gemstone-rings', snappy_image('fade_images/lang_gemstone_rings.jpg', 'Antique and Estate Gemstone Rings'));?>
					<?php echo anchor('products/category/earrings', snappy_image('fade_images/2012.08/lang_earrings.jpg', 'Antique and Estate Diamond Earrings'));?>
					<?php echo anchor('products/category/pins-and-brooches', snappy_image('fade_images/lang_pins_brooches.jpg', 'Antique and Estate Diamond Pins and Brooches'));?>
					<?php echo anchor('products/category/bracelets', snappy_image('fade_images/2012.08/lang_bracelets.jpg', 'Antique and Estate Diamond Bracelets'));?>
					<?php echo anchor('products/category/necklaces', snappy_image('fade_images/lang_necklaces.jpg', 'Antique and Estate Diamond Necklaces'));?>
					<?php echo anchor('products/category/wedding-bands', snappy_image('fade_images/lang_wedding_bands.jpg', 'Antique and Estate Wedding Bands'));?>
					<?php echo anchor('products/type/victorian', snappy_image('fade_images/2012.08/lang_victorian.jpg', 'Antique Diamond Victorian Jewelry'));?>
					<?php echo anchor('products/type/edwardian', snappy_image('fade_images/lang_edwardian.jpg', 'Antique Diamond Edwardian Jewelry'));?>
					<?php echo anchor('products/type/art-nouveau', snappy_image('fade_images/2012.08/lang_art_nouveau.jpg', 'Antique and Estate Art Nouveau Jewelry'));?>
					<?php echo anchor('products/type/art-deco', snappy_image('fade_images/2012.08/lang_art_deco.jpg', 'Diamond Art Deco Jewelry'));?>
					<?php echo anchor('products/type/retro', snappy_image('fade_images/2012.08/lang_retro.jpg', 'Diamond and Ruby Retro Jewelry'));?>
					<?php echo anchor('products/type/contemporary', snappy_image('fade_images/lang_modern.jpg', 'Modern Diamond Jewelry'));?>
					<?php echo anchor('products/type/georgian', snappy_image('fade_images/lang_georgian.jpg', 'Antique Georgian Jewelry'));?>
					<?php echo anchor('products/type/gentlemans-jewelry', snappy_image('fade_images/lang_gents.jpg', 'Gents Jewelry'));?>
					<!-- <?php echo anchor('search/pearls', snappy_image('fade_images/lang_pearls.jpg'));?> -->
					<?php echo anchor('products/type/cameos', snappy_image('fade_images/lang_cameos.jpg', 'Antique and Estate Cameos'));?>
				</div>
			<h2 class='welcome'>Welcome to Our Store!</h2>
			<p>
				Since 1969, from our quaint little shop in the heart of downtown San Francisco, Lang's has specialized in all types of Fine Antique Jewelry and Fine Estate Jewelry from all periods.
				<a name="video"></a>
			</p>
<!-- 			<div style="border: 1px solid #666; padding: 10px; margin: 5px; margin-bottom: 20px; border-radius: 5px; background-color: #f1f1f1;">
				<h2>Holiday Shipping Notice:</h2>
				<p style="font-weight: bold;">
					Last day for 2 day Fed Ex to be delivered by Christmas - <u>Thursday December 20th</u>
					<br />
					Last day for Overnight Fed Ex to be delivered by Christmas - <u>Saturday December 22th</u>
					<br />
					(Orders must be placed by 3PM)
				</p>
			</div>
 -->
<!-- 			<div style="border: 1px solid #666; padding: 10px; margin: 5px; margin-bottom: 20px; border-radius: 5px; background-color: #f1f1f1;">
			<h2>Holiday Store Hours: December 10th - 24th</h2>
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
			-->
			</div>
			<center class="video_frame">
				<iframe width="560" height="315" src="http://www.youtube.com/embed/kACpdoIoL7k" frameborder="0" allowfullscreen></iframe>
			</center>
			<p>
				We proudly offer you a wide and diversified array of fine jewels from every important design period:
				<i>Victorian Jewelry, Edwardian Jewelry, Art Deco Jewelry, etc.</i>
				including a large selection of <i>Estate Diamond Engagement Rings</i>.
			</p>
			<p>
				We hope you enjoy perusing our collection, but please keep in mind that we carry over 7,000 unique, individual items in our store, only about one third of which appear on our website.
				So if you don't see what you are looking for, by all means, please call or email us.
				Our staff of experienced gemologists, appraisers and antique and estate jewelry experts will be more than happy to assist you.
			</p>
			<h3 class='notice' style='text-align: center; '>And of course, we are also active <a href="selling-your-jewelry/">jewelry buyers</a> and <a href="selling-your-jewelry/">diamond buyers</a>.</h3>

			<h3 class='notice' style='text-align: center; '>Be sure to check out the <a href='search/whats-new/'> What's New</a> page to see our most recent additions!</h3>

 			<center>
				<h3>Normal Business Hours:</h3>
                <p>Our staff is available to take your questions, calls, and
                emails between the hours of 10:30am and 5:30pm PST Monday
                through Saturday.</p>
				<table style='text-align: left;'>
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
						<td>Closed</td>
					</tr>
				</table>
			</center>

			<div style="clear: both;"></div>
			<div class='vendor_logo' >
				<div>
					<?php echo anchor('http://www.womensjewelry.org', snappy_image('vendor_logos/wja_logo.jpg', 'wja'), 'target="_blank"'); ?>
					<?php echo anchor('http://www.validatedsite.com/directory/list.aspx?q=L', snappy_image('vendor_logos/validatedsite_logo.jpg', 'Lang Antiques is a validated site'), 'target="_blank"'); ?>
					<?php // echo anchor('http://baylist.sfgate.com/lang-antiques/biz/85653', snappy_image('vendor_logos/baylist_logo.jpg', 'Vote for Lang Antiques in Best Antiques in The Bay Area'), 'target="_blank"'); ?>
					<?php echo anchor('http://www.bbbonline.org/cks.asp?id=107060114164130', snappy_image('vendor_logos/bbb_logo.jpg', 'Click to verify BBB accreditation and to see a BBB report.'), 'target="_blank"'); ?>
                    <?php echo anchor('http://www.bbbonline.org/cks.asp?id=107060114164130', snappy_image('vendor_logos/bbb-rating-a-plus.jpg', 'Click to verify BBB accreditation and to see a BBB report.'), 'target="_blank"'); ?>
				</div>
				<div>
					<?php echo anchor('http://accreditedgemologists.org/', snappy_image('vendor_logos/aga_logo.jpg', 'aga'), 'target="_blank"'); ?>
					<?php echo anchor('http://www.agta.org/', snappy_image('vendor_logos/agta_logo.jpg', 'agta'), 'target="_blank"'); ?>
					<?php echo anchor('http://www.jewelryhistorians.com/', snappy_image('vendor_logos/asojh_logo.jpg', 'asojh'), 'target="_blank"'); ?>
					<?php echo anchor('http://www.najaappraisers.com/', snappy_image('vendor_logos/naja_logo.jpg', 'naja'), 'target="_blank"'); ?>
					<?php echo anchor('http://www.gemstone.org', snappy_image('vendor_logos/ica_logo.jpg', 'ica'), 'target="_blank"'); ?>
					<?php echo anchor('http://www.gia.edu/educational-programs/alumni/index.html', snappy_image('vendor_logos/gia_alumni_logo.jpg', 'gia'), 'target="_blank"'); ?>
				</div>
				<div>
					<?php echo anchor('http://www.fodors.com/world/north-america/usa/california/san-francisco/review-109452.html', "<img src='http://www.fodors.com/fodorschoice/images/fc-badge09-234.gif' alt='Fodors Choice 2009 Travel Reviews'>"); ?>
					<?php echo anchor('pages/we-recycle-gemstones', snappy_image('vendor_logos/lang.recycles.small.jpg', 'Recycled Diamonds'));?>
				</div>
				<div style="padding: 10px;">
					<?php echo snappy_image('vendor_logos/amex_logo.jpg', 'American Express'); ?>
					<?php echo snappy_image('vendor_logos/discover_card_logo.jpg', 'Discover Card'); ?>
					<?php echo snappy_image('vendor_logos/master_card_logo.jpg', 'Master Card'); ?>
					<?php echo snappy_image('vendor_logos/visa_logo.jpg', 'VISA'); ?>
				</div>
			</div>
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
