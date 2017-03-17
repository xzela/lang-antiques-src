<?php

//include 'scripts/utils/check_login.php';

?>

<html>
<head>
	<title>Viewing the Queue</title>
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
			<h2>All Tags in the Queue</h2>
			<p>There are all of the tags in the Queue. If you believe that a tag should exist in the queue, try checking the tag under the Inventory section in Clangity and make sure the tag is active.</p>
			<table class="list_table">
			<tr>
				<th>Item ID</th>
				<th>Line 1</th>
				<th>Line 2</th>
				<th>Line 3</th>
				<th>Line 4</th>
				<th>Line 5</th>
				<th>Options</th>
			</tr>
			<?php
				//Get all of the active tags from the database
				include "scripts/open_connection.php";
				$sql = "SELECT * FROM inventory_tag WHERE active = 1";				
				$results = mysql_query($sql);
				
				if(mysql_num_rows($results) == 0) {
					echo "<tr><td colspan='7' class='warning'>No Tags in the Queue.</td></tr>";
				}
				else {
					while($row = mysql_fetch_array($results)) {
						
						echo "<tr>";
							echo "<td>{$row['item_number']}</td>";
							echo "<td>{$row['line_1']}</td>";
							echo "<td>{$row['line_2']}</td>";
							echo "<td>{$row['line_3']}</td>";
							echo "<td>{$row['line_4']}</td>";
							echo "<td>{$row['line_5']}</td>";
							//Allows the user to remove an item from the queue
							echo "<td><a href='scripts/remove_from_queue.php?tag_id={$row['tag_id']}&remove=1'>Remove from Queue</a></td>";
						//print_r ();
						echo "</tr>";
					}
				}
				
				include "scripts/close_connection.php";
			?>
			</table>
			<?php
			if(mysql_num_rows($results) == 0) {
				//If no results, don't show the print queue.
			}
			else {
			?>
				<a href="scripts/print_queue.php?print_queue=1">Print Queue</a>
			<?php
				}
			?>
			<p>After a tag is printed. It will be removed from the Queue, this does not delete the tag. To delete tags, you must delete them from the inventory section in Clangity.</p>
		</div>
			<?php
			include "scripts/footer.php";
			?>
	</div>
</body>
</html>
