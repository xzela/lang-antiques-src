<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Could Not Find Page -  Lang Antiques</title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<?php include "scripts/styles.php"; ?>	
	<base href="http://<?php echo $domain; ?>/" />
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
		include "scripts/header.php";
		include "scripts/menu.php";
	?>
		<div id="content">
			<div class="breadcrumb">
				<a href="/">Home</a> &gt; 404 Error
			</div>
			<div id="item_container">
				<h2>Hmmm.... We Couldn't find the page you were looking for</h2>
				<h3>Hmmm.... We couldn't find the page you were looking for.</h3>
				<p>
					If you are having difficulty finding that special item, you may call us during our <a href='/contact-us/'>business hours</a> and we'll be more than happy to help you.
				</p>
				<p>
					You can also try using the links on the left side of our web site or you can search for an item using our search tool.
				</p>
			</div>
		</div>
		<?php
			include "scripts/footer.php";
		?>
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
