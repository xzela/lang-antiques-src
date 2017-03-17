<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Our Colorful Greeting Cards - Lang Antiques</title>
	<?php echo snappy_style('colorbox/colorbox.css');?>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>
	
	
	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	
	<?php echo snappy_script('jquery/jquery.colorbox.js');?>

	
	<meta name='keywords' content='' />
	<meta name='description' content='' />
	<script type='text/javascript'>
		$(document).ready(function() {
			var options = {transition:"elastic", width:"80%"};
			$("a[rel='lang_cards']").colorbox(options);
			//$("a[rel='1996_cards']").colorbox(options);
			//$("a[rel='1997_cards']").colorbox(options);
		});
	</script>
	
	<style type='text/css'>
	.card_div {
		
	}
	.card_div img {
		padding: 5px;
		margin: 5px;
		
		border: 1px solid #ddd;
	}
	
	.card_h3 {
		border-top: 1px solid #A1735E;
		padding: 3px;
		color: #000;
		background: transparent url('../images/effects/h4_lite_maroon_background.gif') top repeat-x;
	}
	
	.photo {
		float: right;
		padding: 5px;
		margin: 5px;
		border: solid 1px #ddd;
	}
	
	.floater {
		float: left;
		border: 1px solid #aaa;
		padding: 5px;
		margin: 5px;
	}
	
	table td {
		padding: 5px;
	}
	table td h3 {
		padding: 0px;
		margin: 0px;
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
				<?php echo anchor('/', 'Home'); ?> &gt; Our Greeting Cards
			</div>
			<h2 id='top_h2'>A Small Collection of Our Seasonal Greeting Cards</h2>
			<?php echo snappy_image('photos/staff_photos/rich_close-up.jpg', 'Rich Sigberman', '', 'class="photo"');?>
			<p>Back in 1996,  Mark, the owner of Lang, wanted to put out an end of year card, and I was introduced to him.  Little did I know what this would lead to.  The first two cards we did were basically conservative in approach and used an engraving-like pen and ink style to convey "antique".  They were handsome cards, and soon Mark's sense of humor began to show, first with Santa's helpers making jewelry, and then graduating on to themes for other holidays, going further out into the boundaries of taste, political commentary, and, oh yes, a few even alluded to jewelry .  I believe Lang may be the only estate jewelry store in history that used a dreary World War 1 scene as the frontpiece for a "V-Day" card (that's Valentine's Day , by the way).</p> 
			<p>Along the way, we've parodied cheesy record albums, same sex marriage, Whistler's Mother, and the Millenium panic.  Somehow, we tie it all in with the Lang jewelry store and website, and have had a great time doing it.  Apparently most of the recipients of the cards agree, and some have taken to keeping and collecting them. </p>
			<p>As an illustrator, my job is to try to convey my clients' interests and sensibilities.  None have been more challenging or rewarding than these cards for Lang.</p>
			<p>Rich Sigberman<br />
				415-927-0912<br />
				<a href='http://www.sigsart.com' target='_blank'>http://www.sigsart.com</a></p>
			<h3 class="card_h3" >1996</h3>
			<!-- fix to make these float, i'm using tables... i know it's wrong but i must -->
			<table>
				<tr>
					<td><h3>The First Card</h3></td>
					<td><h3>New Years</h3></td>
					<td><h3>Valentine's Day</h3></td>
				</tr>
				<tr>
					<td>
						<div class="card_div">
							<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1996_Lang_First_card_front_large.jpg' rel='lang_cards' title='1996 Langs First Card'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1996_Lang_First_card_front_small.jpg" /></a>
						</div>
					</td>
					<td>
						<div class="card_div">
							<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1996_Lang_New_Years_front_large.jpg' rel='lang_cards' title='1996 New Years Card'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1996_Lang_New_Years_front_small.jpg" /></a>
						</div>
					</td>
					<td>
						<div class="card_div">	
							<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1996_Lang_Valentine_Day_front_large.jpg' rel='lang_cards' title='1996 Valentine Day Card' ><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1996_Lang_Valentine_Day_front_small.jpg" /></a>
						</div>
					</td>
				</tr>
			</table>				
			<h3 class="card_h3">1997</h3>
				<table>
					<tr>
						<td><h3>Mothers Day</h3></td>
						<td><h3>Thanksgiving Day</h3></td>
					</tr>
					<tr>
						<td>
							<div class="card_div">
								<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1997_Lang_Mothers_Day_front_large.jpg' rel='lang_cards' title='1997 Mothers Day' ><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1997_Lang_Mothers_Day_front_small.jpg" /></a>
							</div>
						</td>
						<td>
							<div class="card_div">
								<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1997_Lang_Thanksgiving_front_large.jpg' rel='lang_cards' title='1997 Thanskgiving'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1997_Lang_Thanksgiving_front_small.jpg" /></a>
							</div>						
						</td>
					</tr>
				</table>
				<h3>Seasons Greetings</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1997_Lang_Seasons_Greetings_front_large.jpg' rel='lang_cards' title='1997 Front of Seasons Greetings' ><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1997_Lang_Seasons_Greetings_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1997_Lang_Seasons_Greetings_inside_large.jpg' rel='lang_cards' title='1997 Inside of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1997_Lang_Seasons_Greetings_inside_small.jpg" /></a>
				</div>

			<h3 class="card_h3">1998</h3>
				<table>
					<tr>
						<td><h3>Valentine's Day</h3></td>
						<td><h3>Mothers Day</h3></td>
						<td><h3>4th of July</h3></td>
					</tr>
					<tr>
						<td>
							<div class="card_div">
								<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1998_Lang_Valentines_front_large.jpg' rel='lang_cards' title='1998 Valentines Day' ><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1998_Lang_Valentines_front_small.jpg" /></a>
							</div>							
						</td>
						<td>
							<div class="card_div">
								<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1998_Lang_Mothers_Day_front_large.jpg' rel='lang_cards' title='1998 Mothers Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1998_Lang_Mothers_Day_front_small.jpg" /></a>
							</div>
						</td>
						<td>
							<div class="card_div">
								<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1998_Lang_4th_front_large.jpg' rel='lang_cards' title='1998 4th of July'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1998_Lang_4th_front_small.jpg" /></a>
							</div>							
						</td>
					</tr>
				</table>
				<h3>Halloween</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1998_Lang_Halloween_front_large.jpg' rel='lang_cards' title='1998 Front of Halloween'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1998_Lang_Halloween_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1998_Lang_Halloween_inside_large.jpg' rel='lang_cards' title='1998 Inside of Halloween'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1998_Lang_Halloween_inside_small.jpg" /></a>
				</div>				
				<h3>Seasons Greetings</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1998_Lang_Season_Greetings_front_large.jpg' rel='lang_cards' title='1998 Front of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1998_Lang_Season_Greetings_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1998_Lang_Season_Greetings_inside_large.jpg' rel='lang_cards' title='1998 Inside of Seaons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1998_Lang_Season_Greetings_inside_small.jpg" /></a>
				</div>
				
			<h3 class="card_h3">1999</h3>
				<h3>Mothers Day</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1999_Lang_Mothers_Day_front_large.jpg' rel='lang_cards' title='1999 Front of Mothers Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1999_Lang_Mothers_Day_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1999_Lang_Mothers_Day_inside_large.jpg' rel='lang_cards' title='1999 Inside of Mothers Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1999_Lang_Mothers_Day_inside_small.jpg" /></a>
				</div>
				<h3>Halloween</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1999_Lang_Halloween_front_large.jpg' rel='lang_cards'  title='1999 Front of Halloween'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1999_Lang_Halloween_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/1999_Lang_Halloween_inside_large.jpg' rel='lang_cards'  title='1999 Inside of Halloween'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/1999_Lang_Halloween_inside_small.jpg" /></a>
				</div>

			<h3 class="card_h3">2000</h3>
				<h3>Valentine's Day</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2000_Lang_Valentines_front_large.jpg' rel='lang_cards' title='2000 Front of Valentines Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2000_Lang_Valentines_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2000_Lang_Valentines_inside_large.jpg' rel='lang_cards' title='2000 Inside of Valentines Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2000_Lang_Valentines_inside_small.jpg" /></a>
				</div>						
				<h3>4th of July</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2000_Lang_4th_of_July_front_large.jpg' rel='lang_cards' title='2000 Front of 4th of July'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2000_Lang_4th_of_July_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2000_Lang_4th_of_July_inside_large.jpg' rel='lang_cards' title='2000 Inside of 4th of July'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2000_Lang_4th_of_July_inside_small.jpg" /></a>
				</div>
				<h3>Seasons Greetings</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2000_Lang_Seasons_Greetings_front_large.jpg' rel='lang_cards' title='2000 Front of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2000_Lang_Seasons_Greetings_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2000_Lang_Seasons_Greetings_inside_large.jpg' rel='lang_cards' title='2000 Inside of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2000_Lang_Seasons_Greetings_inside_small.jpg" /></a>
				</div>				

			<h3 class="card_h3">2001</h3>
				<h3>Millenium</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2001_Lang_Millenium_front_large.jpg' rel='lang_cards' title='2001 Front of Millenium'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2001_Lang_Millenium_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2001_Lang_Millenium_inside_large.jpg' rel='lang_cards'  title='2001 Inside of Millenium'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2001_Lang_Millenium_inside_small.jpg" /></a>
				</div>
				<h3>Valentine's Day</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2001_Lang_Valentines_front_large.jpg' rel='lang_cards'  title='2001 Front of Valentine Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2001_Lang_Valentines_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2001_Lang_Valentines_inside_large.jpg' rel='lang_cards' title='2001 Inside of Valentine Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2001_Lang_Valentines_inside_small.jpg" /></a>
				</div>
				<h3>World Peace</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2001_Lang_World_Peace_front_large.jpg' rel='lang_cards' title='2001 Front of World Peace Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2001_Lang_World_Peace_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2001_Lang_World_Peace_inside_part1_large.jpg' rel='lang_cards' title='2001 Inside Part 1 of World Peace Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2001_Lang_World_Peace_inside_part1_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2001_Lang_World_Peace_inside_part2_large.jpg' rel='lang_cards' title='2001 Inside Part 2 of World Peace Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2001_Lang_World_Peace_inside_part2_small.jpg" /></a>
				</div>						

			<h3 class="card_h3">2002</h3>
				<h3>Mothers Day</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2002_Lang_Mothers_Day_front_large.jpg' rel='lang_cards' title='2002 Front of Mothers Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2002_Lang_Mothers_Day_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2002_Lang_Mothers_day_inside_large.jpg' rel='lang_cards' title='2002 Inside of Mothers Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2002_Lang_Mothers_day_inside_small.jpg" /></a>
				</div>
				<h3>Valentine's Day</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2002_Lang_Valentines_front_large.jpg' rel='lang_cards' title='2002 Front of Valentine Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2002_Lang_Valentines_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2002_Lang_Valentines_inside_large.jpg' rel='lang_cards' title='2002 Inside of Valentine Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2002_Lang_Valentines_inside_small.jpg" /></a>
				</div>					
				<h3>Seasons Greetings</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2002_Lang_Seasons_Greetings_front_large.jpg' rel='lang_cards' title='2002 Front of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2002_Lang_Seasons_Greetings_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2002_Lang_Seasons_Greetings_inside_large.jpg' rel='lang_cards'  title='2002 Inside of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2002_Lang_Seasons_Greetings_inside_small.jpg" /></a>
				</div>

			<h3 class="card_h3">2003</h3>
				<h3>Mothers Day</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2003_Lang_Mothers_Day_front_large.jpg' rel='lang_cards' title='2003 Front of Mothers Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2003_Lang_Mothers_Day_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2003_Lang_Mothers_Day_inside_part1_large.jpg' rel='lang_cards' title='2003 Inside Part 1 of Mothers Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2003_Lang_Mothers_Day_inside_part1_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2003_Lang_Mothers_Day_inside_part2_large.jpg' rel='lang_cards' title='2003 Inside Part 2 of Mothers Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2003_Lang_Mothers_Day_inside_part2_small.jpg" /></a>
				</div>
				<h3>Halloween</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2003_Lang_Halloween_front_large.jpg' rel='lang_cards' title='2003 Front of Halloween'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2003_Lang_Halloween_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2003_Lang_Halloween_inside_large.jpg' rel='lang_cards' title='2003 Inside of Halloween'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2003_Lang_Halloween_inside_small.jpg" /></a>
				</div>
				<h3>Thanksgiving</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2003_Lang_Thanksgiving_front_large.jpg' rel='lang_cards' title='2003 Front of Thanksgiving'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2003_Lang_Thanksgiving_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2003_Lang_Thanksgiving_inside_large.jpg' rel='lang_cards' title='2003 Inside of Thanksgiving'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2003_Lang_Thanksgiving_inside_small.jpg" /></a>
				</div>
				<h3>Seasons Greetings</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2003_Lang_Seasons_Greetings_front_large.jpg' rel='lang_cards' title='2003 Front of Seasons Greeting'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2003_Lang_Seasons_Greetings_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2003_Lang_Seasons_Greetings_inside_large.jpg' rel='lang_cards' title='2003 Inside of Seasons Greeting'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2003_Lang_Seasons_Greetings_inside_small.jpg" /></a>
				</div>

			<h3 class="card_h3">2004</h3>
				<h3>Auld Lang Syne</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_Auld_Lang_Syne_front_large.jpg' rel='lang_cards' title='2004 Front of Auld Lang Syne'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_Auld_Lang_Syne_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_Auld_Lang_Syne_inside_part1_large.jpg' rel='lang_cards' title='2004 Inside Part 1 of Auld Lang Syne'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_Auld_Lang_Syne_inside_part1_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_Auld_Lang_Syne_inside_part2_large.jpg' rel='lang_cards' title='2004 Inside Part 2 of Auld Lang Syne'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_Auld_Lang_Syne_inside_part2_small.jpg" /></a>
				</div>
			
				<h3>City Hall</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_City_Hall_front_large.jpg' rel='lang_cards' title='2004 Front of City Hall'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_City_Hall_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_City_Hall_inside_part1_large.jpg' rel='lang_cards' title='2004 Inside Part 1 of City Hall'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_City_Hall_inside_part1_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_City_Hall_inside_part2_large.jpg' rel='lang_cards' title='2004 Inside Part 2 of City Hall'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_City_Hall_inside_part2_small.jpg" /></a>
				</div>
				<h3>Election Day</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_Election_Day_front_large.jpg' rel='lang_cards' title='2004 Front of Election Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_Election_Day_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_Election_Day_inside_large.jpg' rel='lang_cards' title='2004 Inside of Election Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_Election_Day_inside_small.jpg" /></a>
				</div>
				<h3>Website Launch Day</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_Internet_front_large.jpg' rel='lang_cards' title='2004 Front of Website Launch Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_Internet_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_Internet_inside_large.jpg' rel='lang_cards' title='2004 Inside of Website Launch Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_Internet_inside_small.jpg" /></a>
				</div>
				<h3>Valentine's Day</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_Valentines_Day_front_large.jpg' rel='lang_cards'  title='2004 Front of Valentines Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_Valentines_Day_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_Valentines_Day_inside_part1_large.jpg' rel='lang_cards' title='2004 Inside Part 1 of Valentines Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_Valentines_Day_inside_part1_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_Valentines_Day_inside_part2_large.jpg' rel='lang_cards' title='2004 Inside Part 2 of Valentines Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_Valentines_Day_inside_part2_small.jpg" /></a>
				</div>
				<h3>Seasons Greetings</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_Seasons_Greetings_front_large.jpg' rel='lang_cards' title='2004 Front of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_Seasons_Greetings_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_Seasons_Greetings_inside_part1_large.jpg' rel='lang_cards' title='2004 Inside Part 1 of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_Seasons_Greetings_inside_part1_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2004_Lang_Seasons_Greetings_inside_part2_large.jpg' rel='lang_cards' title='2004 Inside Part 2 of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2004_Lang_Seasons_Greetings_inside_part2_small.jpg" /></a>
				</div>
				
			<h3 class="card_h3">2005</h3>
				<h3>Seasons Greetings</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2005_Lang_Season_Greetings_front_large.jpg' rel='lang_cards' title='2005 Front of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2005_Lang_Season_Greetings_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2005_Lang_Season_Greetings_inside_large.jpg' rel='lang_cards' title='2005 Inside of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2005_Lang_Season_Greetings_inside_small.jpg" /></a>
				</div>
				
			<h3 class="card_h3">2006</h3>
				<h3>Valentine's Day</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2006_Lang_Valentines_front_large.jpg' rel='lang_cards' title='2006 Front of Valentines Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2006_Lang_Valentines_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2006_Lang_Valentines_inside_large.jpg' rel='lang_cards' title='2006 Inside of Valentines Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2006_Lang_Valentines_inside_small.jpg" /></a>
				</div>
				<h3>Mothers Day</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2006_Lang_Mothers_Day_front_large.jpg' rel='lang_cards' title='2006 Front of Mothers Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2006_Lang_Mothers_Day_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2006_Lang_Mothers_Day_inside_large.jpg' rel='lang_cards' title='2006 Inside of Mothers Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2006_Lang_Mothers_Day_inside_small.jpg" /></a>
				</div>
				<h3>Thanksgiving</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2006_Lang_Thanksgiving_front_large.jpg' rel='lang_cards' title='2006 Front of Thanksgiving'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2006_Lang_Thanksgiving_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2006_Lang_Thanksgiving_inside_large.jpg' rel='lang_cards' title='2006 Inside of Thanksgiving'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2006_Lang_Thanksgiving_inside_small.jpg" /></a>
				</div>
			<h3 class="card_h3">2007</h3>
				<h3>Valentine's Day</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2007_Lang_Valentines_front_large.jpg' rel='lang_cards' title='2007 Front of Valentines Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2007_Lang_Valentines_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2007_Lang_Valentines_inside_large.jpg' rel='lang_cards' title='2007 Inside Part 1 of Valentines Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2007_Lang_Valentines_inside_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2007_Lang_Valentines_back_large.jpg' rel='lang_cards'  title='2007 Inside Part 2 of Valentines Day'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2007_Lang_Valentines_back_small.jpg" /></a>
				</div>			
				<h3>April Fools!</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2007_Lang_April_fools_front_large.jpg' rel='lang_cards' title='2007 Front of April Fools!'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2007_Lang_April_fools_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2007_Lang_April_fools_inside_large.jpg' rel='lang_cards' title='2007 Inside of April Fools!'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2007_Lang_April_fools_inside_small.jpg" /></a>
				</div>
				<h3>Summer of Love</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2007_Lang_Summer_of_Love_front_large.jpg' rel='lang_cards' title='2007 Front of Summer of Love'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2007_Lang_Summer_of_Love_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2007_Lang_Summer_of_Love_inside_large.jpg' rel='lang_cards' title='2007 Inside of Summer of Love'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2007_Lang_Summer_of_Love_inside_small.jpg" /></a>
				</div>
				<h3>Seasons Greetings</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2007_Lang_Seasons_Greetings_front_large.jpg' rel='lang_cards'  title='2007 Front of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2007_Lang_Seasons_Greetings_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2007_Lang_Seasons_Greetings_inside_large.jpg' rel='lang_cards' title='2007 Inside Part 1 of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2007_Lang_Seasons_Greetings_inside_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2007_Lang_Seasons_Greetings_back_large.jpg' rel='lang_cards' title='2007 Inside Part 2 of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2007_Lang_Seasons_Greetings_back_small.jpg" /></a>
				</div>
			<h3 class="card_h3">2008</h3>
				<h3>Decision 2008</h3>
				<div class="card_div">
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2008_Lang_Decision_inside_large.jpg' rel='lang_cards' title='2008 Front of Decision 2008'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2008_Lang_Decision_inside_small.jpg" /></a>	
				</div>
				<h3>Seasons Greetings</h3>
				<div class='card_div'>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2008_Lang_Seasons_Greetings_front_large.jpg' rel='lang_cards' title='2008 Front of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2008_Lang_Seasons_Greetings_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2008_Lang_Seasons_Greetings_inside_large.jpg' rel='lang_cards' title='2008 Inside of Seasons Greetings'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2008_Lang_Seasons_Greetings_inside_small.jpg" /></a>	
				</div>
			<h3 class='card_h3'>2010</h3>
				<h3>Valentine's Day</h3>
				<div class='card_div'>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2010_Lang_Valentines_front_large.jpg' rel='lang_cards' title='2010 Front of Valentines Day Card'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2010_Lang_Valentines_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2010_Lang_Valentines_inside_large.jpg' rel='lang_cards' title='2010 Inside of Valentines Day Card'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2010_Lang_Valentines_inside_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2010_Lang_Valentines_back_large.jpg' rel='lang_cards' title='2010 Backside of Valentines Day Card'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2010_Lang_Valentines_back_small.jpg" /></a>
				</div>
				<h3>Labor Day</h3>
				<div class='card_div'>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2010_Lang_LaborDay_front_large.jpg' rel='lang_cards' title='2010 Front of Labor Day Card'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2010_Lang_LaborDay_front_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2010_Lang_LaborDay_inside1_large.jpg' rel='lang_cards' title='2010 Inside of Labor Day 1 Card'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2010_Lang_LaborDay_inside1_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2010_Lang_LaborDay_inside2_large.jpg' rel='lang_cards' title='2010 Inside of Labor Day 2 Card'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2010_Lang_LaborDay_inside2_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2010_Lang_LaborDay_inside3_large.jpg' rel='lang_cards' title='2010 Inside of Labor Day 3 Card'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2010_Lang_LaborDay_inside3_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2010_Lang_LaborDay_back1_large.jpg' rel='lang_cards' title='2010 Back of Labor Day 1 Card'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2010_Lang_LaborDay_back1_small.jpg" /></a>
					<a href='http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/large/2010_Lang_LaborDay_back2_large.jpg' rel='lang_cards' title='2010 Back of Labor Day 2 Card'><img src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/images/lang_cards/small/2010_Lang_LaborDay_back2_small.jpg" /></a>
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
