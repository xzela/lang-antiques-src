<?php

//include 'scripts/utils/check_login.php';

?>

<html>
<head>
	<title>Print Tag</title>
	<link href="styles/styles.css" rel="stylesheet" type="text/css" >
</head>
<body>
	<div class="container">
		<?php
			include "scripts/header.php";
		?>
		<?php
			include "scripts/menu.php";
		?>
		<div id="content">
			<h2>Print Tag for Item</h2>
			<p>Welcome to the Tag Printing Interface. Please note that this can only run locally! When you are viewing this page you are not accessing the internet version of Clangity!</p>
			<h3>Printing Tags</h3>
			<p>Here are some tips on how to print tags for specific items. <br/>
				<ol>
					<li>Find the item you are looking for by searching for its ID number. </li>
					<li>Verify that the item you are looking at is the correct item. </li>
					<li>All text fields only allow 16 characters (This in includes spaces, "&nbsp;")</li>
				</ol>
				First, find the item you are looking for by searching for its ID number. 
			</p>
		</div>
			<?php
			include "scripts/footer.php";
			?>
	</div>
</body>
</html>
