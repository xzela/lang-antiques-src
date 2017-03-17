<?php
include 'scripts/printer.methods.php';

$queues = getTagQueues();


?>

<html>
<head>
	<title>Find Item</title>
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
			<h2>Find Item For Printing</h2>
			<p class="item_section">
				Enter an Item Number.
				<form action="scripts/search_item.php" method="POST">
					<b>Search:</b> <input type="text" name="search_txt" /> <input type="submit" name="submit_search" value="Search" />
				</form>
				<?php
				if(isset($_REQUEST['nothing'])) {
				?>
					<span style="border: 1px dashed #ddd; width: 100%" class="warning">Nothing Found. Try your search again.</span>
				<?php
				}
				?>
			</p>
			
			<div>
				<h2>Tag Queues: </h2>
				<?php foreach($queues as $queue):?>
				<?php endforeach;?>
			</div>
		</div>
			<?php
			include "scripts/footer.php";
			?>
	</div>
</body>
</html>
