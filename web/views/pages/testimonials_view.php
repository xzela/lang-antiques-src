<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Customer Testimonials for Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>

	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>


	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>

	<meta name='keywords' content='' />
	<meta name='description' content='' />

	<style type='text/css'>
		.person_name {
			text-align: right;
			font-weight: bold;
		}
		.testiment {
			-moz-border-radius: 7px;
			-webkit-border-radius: 7px;
			border-radius: 7px;
			border: 1px solid #BEBECC;

			background-image: url('/web/assets/images/quote.jpg');
			background-repeat: no-repeat;
			padding-left: 40px;
			padding-top: 5px;
			margin: 5px;
		}
		.testiment p {
			padding-left: 5px;
			padding-right: 5px;
		}

		.testiment_image_right {
			padding: 5px;
			float: right;
		}
		.testiment_image_left {
			padding: 5px;
			float: left;
		}
		.testiment_image img {
			border: 1px solid #666666;
			width: 100px;
			height: 100px;
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
				<?php echo anchor('/', 'Home'); ?> &gt; Testimonals
			</div>
			<h2 id='top_h2'>Testimonials for Lang Antiques</h2>
			<div class='testiment'>
				<p>
					Hi Suzanne,
				</p>
				<p>
					I wanted to let you know that Alison did a superb job, assisting me in choosing a piece of jewelry from Lang Antiques.
					She was very knowledgeable, and friendly, and the necklace far surpassed my expectations.
					The packaging was fantastic as well.
					I had a great experience and wanted to let you know how pleased I am with my purchase.
					<br />
					<br />
					Thank you,
				</p>
				<p class='person_name'>
					Jinann
				</p>
			</div>
			<div class='testiment'>
				<p>
					Hello,
				</p>
				<p>
					We just wanted to share our absolute delight with the outstanding customer service we received at Lang Antiques on Saturday 6 October.
					My (now!) fiance and I had viewed a number of Art Deco sapphire engagement rings on the website however we weren't sure what we wanted and drove to San Francisco from LA to see what was available in person.
				</p>
				<p>
					All of the rings we'd viewed online looked even more impressive in the store and our choice was perfect for us both.
					Julie Kerlin was fantastic, she stepped us through what was on offer and responded to all of our questions in great detail and with a real passion for what she does.
					We felt welcome and comfortable which made our decision making process all the more easy and enjoyable!
				</p>
				<p>
					Thank you Julie for your great customer service and knowledge, we look forward to our lifetime with this ring and treating it with the respect and care that you showed all of the pieces in your store!
					I have no hesitation in recommending Lang Antiques to our friends and family.
					<br />
					<br />
					Thank you so much!
				</p>
				<p class='person_name'>
					Kristy and Mike, California
				</p>
			</div>
			<div class='testiment'>
				<div class='testiment_image testiment_image_right'>
					<?php echo snappy_image('inventory/testimonial/testiment_stickpin.jpg', 'stickpin'); ?>
				</div>
				<p>Hello Lang Antiques,</p>
				<p>
					From customer encouragement and assistance to the exquisite packaging and jewelry boxes (I love those!), you remain a standard of mercantile that is becoming exceedingly rare.
					And of course, each piece we own of your fine collection is a treasure in its own right.</p>
				<p>&nbsp;</p>
				<p class='person_name'>The Davids, Utah</p>
			</div>
			<div class='testiment'>
				<div class='testiment_image testiment_image_left'>
					<?php echo snappy_image('inventory/testimonial/testiment_sapphire_ring.jpg'); ?>
				</div>
				<p>Dear Suzanne,</p>
				<p>I received my payment check in the mail this morning. I want to thank for your exceptional service!</p>
				<p>&nbsp;</p>
				<p class='person_name'>Trang, Texas</p>
			</div>
			<div class='testiment'>
				<div class='testiment_image testiment_image_right'>
					<?php echo snappy_image('inventory/testimonial/testiment_gold_earrings.jpg'); ?>
				</div>
				<p>Dear Lang Antiques,</p>
				<p>
					My new earrings arrived early today! WOW!! <br />
					So beautifully wrapped (as a present to me and from me) for my recent birthday.<br />
				    I have not taken them off since. Just love them!<br />
					Thank you all! Your store must be a wonder, which I hope to visit someday. Still looking at other gorgeous items on your site.
				</p>
				<p>Question is: How much will I indulge myself? What fun!</p>
				<p class='person_name'>Sally, California</p>
			</div>
			<div class='testiment'>
				<div class='testiment_image testiment_image_left'>
					<?php echo snappy_image('inventory/testimonial/testiment_diamond_necklace.jpg'); ?>
				</div>
				<p>Dear Suzanne,</p>
				<p>
					Thank you for extraordinary customer service! No wonder Lang Antiques has such a great following and reputation.
					Will be in touch soon.
				</p>
				<p>Thank you again for all your wisdom and assistance with the potential sale of my jewelry.</p>
				<p class='person_name'>Catherine, New York</p>
			</div>
			<div class='testiment'>
				<div class='testiment_image testiment_image_right'>
					<?php echo snappy_image('inventory/testimonial/testiment_art_deco_ruby_ring.jpg'); ?>
				</div>
				<p>Dear Suzanne,</p>
				<p>
					I just wanted to let you know that I surprised my fiance last night with your gorgeous Art Deco diamond engagement ring.
					When she opened the box she almost fainted at the dinner table.
					It caused such a scene that half of the restaurant diners came over to ooh and aah.
				</p>
				<p>
					Thank you so much for your wonderful and attentive hand holding during this most important purchase.
					I feel like I made a friend in the jewelry business.
				</p>
				<p class='person_name'>
					Andrew, New York
				</p>
			</div>
			<div class='testiment'>
				<div class='testiment_image testiment_image_left'>
					<?php echo snappy_image('inventory/testimonial/testiment_diamond_swirl_ring.jpg'); ?>
				</div>
				<p>Dear Lang Antiques,</p>
				<p>
					I purchased my wedding ring at Lang back in May after two other rings on my Favorites list began to disappear as soon as I had added them.
					I've <strong>not</strong> regretted my purchase since.
					My ring is oddly-shaped and not at all what others would consider a wedding ring and I love it <i>just</i> fine.
				</p>
				<p>
					A lot of people have commented on it and have asked me where I got it, and I'm very happy to direct them to Lang Antique and Estate Jewelry and tell them how super-friendly the staff is and how comfortable I was when I was in the shop.
				</p>
				<p>
					Thank you so much!
				</p>
				<p class='person_name'>
					Emgee, California
				</p>
			</div>
			<div class='testiment' >
				<div class='testiment_image testiment_image_right'>
					<?php echo snappy_image('inventory/testimonial/testiment_diamond_edwardian_ring.jpg'); ?>
				</div>
				<p>Erin,</p>
 				<p>
					Thank you SO much for the time you spent helping me pick out a ring -
					we finally arrived at the perfect ring and price -
					thank you for the re-size (just a teeny tiny hair over 5.5 - because 5.5 fits me, yet I am getting older) and for the shipping.
					It all matters to me.
					I hope I have it by the end of the week - a big purchase and I can't wait to open that magical box!
				</p>
 				<p class='person_name'>
					Patrice, New York
				</p>
			</div>
			<div class='testiment'>
				<p>Dear Suzanne, </p>
				<p>
					I just wanted to thank you for your sensitivity and thoughtfulness.
					You made a difficult situation so much easier and I felt that my relatively modest pieces was as special to you as the grandest one in the shop.
					You really went above and beyond the call of duty and I do appreciate it.
					You are one in a million.
				</p>
				<p class='person_name'>
					Rebecca, Washington
				</p>
			</div>
			<div class='testiment'>
				<div class='testiment_image testiment_image_left'>
					<?php echo snappy_image('inventory/testimonial/testiment_yellow_diamond_ring.jpg'); ?>
				</div>
				<p>Dear Angela, </p>
				<p>
					The Yellow Diamond  Estate Engagement ring I bought from Lang arrived right on time this morning at 10 AM EST.
					It looks as pretty as the picture.
					I'd like to compliment everyone at Lang who helped with this purchase...you, Alison and Maria in particular.
					Y'all were knowledgeable, friendly, efficient and very clear in your communications.
				</p>
				<p>
					I find that level of excellent service to be all too rare these days.
					I'm sure my wife will treasure this Christmas gift and long overdue engagement ring for a lifetime.
				</p>
				<p class="person_name">
					Rick, Ohio
				</p>
			</div>
			<div class="testiment">
				<div class="testiment_image testiment_image_right">
					<?php echo snappy_image('inventory/testimonial/testiment_cluster_diamond_ring.jpg'); ?>
				</div>
				<p>Alison,</p>
				<p>
						It's an incredibly daunting task to find the ring you feel embodies the beauty and love you have for the person you want to spend the rest of your life with.
						Lang Antiques ended a very long search for me with the PERFECT ring...all the while providing the best response time and customer interaction I could have imagined!
						I can't thank you all enough!
				</p>
				<p class="person_name">
					Evan, Maryland
				</p>
			</div>
			<div class="testiment">
				<div class="testiment_image testiment_image_left">
					<?php echo snappy_image('inventory/testimonial/testiment_vintage_citrine_pearl_ring.jpg'); ?>
				</div>
				<p>Hi Angela,</p>
				<p>
						I just received my ring...
						it is absolutely gorgeous!
						More stunning in person & a perfect fit.
						I am a very pleased costumer!!
				</p>
				<p class="person_name">
					Kelly, Colorado
				</p>
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
