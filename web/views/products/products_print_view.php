<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo $page_data['title']; ?> - Lang Antiques</title>


	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script type='text/javascript'>
		window.onload = function() {
			window.print();
		};
	</script>
	<style type='text/css'>
		body {
			width: 500px;
			margin: 0 auto;
		}
	</style>
</head>
<body>
	<div id="container" >
		<?php echo snappy_image('lang.header.text.jpg'); ?>
		<p>
			Buyers and sellers of fine antique &amp; estate jewelry since 1969
			<br />
			Call us toll free <i>(800) 924-2213</i>
		</p>
		<h2><?php echo $page_data['h2']; ?> <span class='normal'>#<?php echo $item_data['item_number']?></span></h2>
		<div style='width: 500px'>
			<?php foreach($item_data['images'] as $image ): ?>
				<?php echo "<img src='" . base_url() ."images/thumbnails/150/" . $image['image_id'] . ".jpg' />";?>
			<?php endforeach; ?>
		</div>

		<p style=''><?php echo $item_data['item_description']; ?></p>
	</div>

</body>
</html>
<?php
ob_flush();
?>
