<?php

include 'scripts/open_connection.php';


$item_number = "";
$mjr_class_id = "";
$min_class_id = "";
$item_price = "";
$item_name = "";
$item_description = "";
$id = $_REQUEST['item_id'];

$sql = "SELECT * FROM inventory WHERE item_id = '$id'";
$results = mysql_query($sql);

while($row = mysql_fetch_array($results)) {
	$item_id = $row['item_id'];
	$item_number = $row['item_number'];
	$mjr_class_id = $row['mjr_class_id'];
	$min_class_id = $row['min_class_id'];
	$item_price = $row['item_price'];
	$item_name = $row['item_name'];
	$item_description = $row['item_description'];
	$barcode_number = $mjr_class_id . $min_class_id . $item_id;
}

$get_tag = "SELECT * FROM inventory_tag WHERE item_id = $id";
$tag_results = mysql_query($get_tag);

$line_1 = "";
$line_2 = "";
$line_3 = "";
$line_4 = "";
$line_5 = "";

while($tag = mysql_fetch_array($tag_results)) {
	$line_1 = $tag['line_1'];
	$line_2 = $tag['line_2'];
	$line_3 = $tag['line_3'];
	$line_4 = $tag['line_4'];
	$line_5 = $tag['line_5'];
	
}

include 'scripts/close_connection.php';
include 'scripts/php-barcode.php';

//echo padWithZeros($min_class_id, 3);
//echo padWithZeros($id, 4);

?>

<html>
<head>
	<title>Edit Tag</title>
	<link href="styles/styles.css" rel="stylesheet" type="text/css" >
		<script language="Javascript" type="text/javascript">
		//neato color functions.
		function highlight(foo) {
			foo.style.backgroundColor = "#ffd";
		}
		function lowlight(foo) {
			foo.style.backgroundColor = "#fff";
		}
	</script>
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
			<h2>Edit Tag for Item</h2>
			<p>Please fill out any of the fields</p>
			<h3 class="item">Printing Tags</h3>
			<div style="float: left; margin: 5px; padding: 5px; padding-bottom: 0px; background-color: #fff; border: 1px solid #ddd;">
				<?php
				include 'scripts/open_connection.php';
				$getImgs = "SELECT image_id, image_name FROM image_lang WHERE item_id = $id";
				$imgresults = mysql_query($getImgs);
				while($row = mysql_fetch_array($imgresults)) {
					echo "<img src='http://www.langantiques.com/admin/scripts/create_thumb.php?image_id=$row[image_id]&image_type=8' alt='$row[image_name]' />";
				}
				include 'scripts/close_connection.php';
					
				?>
			</div>			
			<p class="item_section" >Please edit the fields as needed.<br/>
				Please note that the fields can only hold 16 characters, that includes spaces. This means you have to keep your input short. You can leave any line blank. 
			</p>
			<p class="item_section">
				<b>Item Name:</b> <?php echo $item_name; ?> <br />
				<b>Item Description:</b> <?php echo $item_description;?>
			</p>

			<form method="POST" action="scripts/print_tag.php?item_id=<?php echo $id;?>" class="item_section">
				Line 1: <input type="text" maxlength="16" name="line_1" value="<?php echo $line_1; ?>" /><br />
				Line 2: <input type="text" maxlength="16" name="line_2" value="<?php echo $line_2; ?>" /><br />
				Line 3: <input type="text" maxlength="16" name="line_3" value="<?php echo $line_3; ?>" /><br />
				Line 4: <input type="text" maxlength="16" name="line_4" value="<?php echo $line_4; ?>" /><br />
				Line 5: <input type="text" maxlength="16" name="line_5" value="<?php echo $line_5; ?>" /><br />
				<input type="submit" name="print_tag" value="Print Tag!">
			</form>

			<p class="item_section" style="background-color: #ffe;">
				<i class="warning">These fields can not be edited</i><br />
				<b>Item Number:</b> <?php echo $item_number; ?> <br />
				<b>Item Price:</b> $<?php echo number_format($item_price, 2); ?><br />
			</p>
			<h3 class="item">Additional Information</h3>
			<h3>Known Gemstones</h3>
					<div>
						<table class="list_table">
							<tr>
								<th nowrap>Item Number</td>
								<th>Stone</th>
								<th width="10%">Weight</th>
								<th width="10%">Quantity</th>
								<th align="center" width="70%">Notes</th>
							</tr>
							<tr style="font-weight: bold;">
								<td colspan="6" class="title" style='background-color: #efefef'><img src='images/icons/ruby.png' /> Gemstones:</td>
							</tr>
							<?php
								include 'scripts/open_connection.php';
								$getAppliedStones = "SELECT gem_id, item_id, gem_type_id, gem_carat, gem_quantity, gem_notes, stone_type.template_type, stone_type.stone_name FROM stone_info, stone_type WHERE item_id = $id AND stone_info.gem_type_id = stone_type.stone_id ORDER BY stone_type.stone_name";
								$aStoneResults = mysql_query($getAppliedStones);
								while($row = mysql_fetch_array($aStoneResults)) {
									echo "<tr onmouseover=\"javascript:highlight(this);\" onmouseout=\"javascript:lowlight(this)\">";
									echo "<td>$item_number</td>".
										"<td nowrap>{$row['stone_name']}</td>".
										"<td>{$row['gem_carat']}</td>". 
										"<td>{$row['gem_quantity']}</td>".
										"<td>{$row['gem_notes']}</td>";
									echo "</tr>";
								}
								include 'scripts/close_connection.php';										
							?>
							<tr style="font-weight: bold;">
								<td colspan="6" class="title" style='background-color: #efefef'><img src='images/icons/pearl.png' /> Pearls:</td>
							</tr>
							<?php
								include 'scripts/open_connection.php';
								$getAppliedDiamonds = "SELECT p_id, item_id, p_type_id, p_weight, p_quantity, p_notes, stone_type.stone_name, stone_type.template_type FROM pearl_info, stone_type WHERE item_id = $id AND pearl_info.p_type_id = stone_type.stone_id ORDER BY stone_type.stone_name";
								$diamondResults = mysql_query($getAppliedDiamonds);
								while($row = mysql_fetch_array($diamondResults)) {
									echo "<tr onmouseover=\"javascript:highlight(this);\" onmouseout=\"javascript:lowlight(this)\">";
									echo "<td>$item_number</td>".
										"<td nowrap>{$row['stone_name']}</td>".
										"<td>{$row['p_weight']}</td>". 
										"<td>{$row['p_quantity']}</td>".
										"<td>{$row['p_notes']}</td>";
									echo "</tr>";
								}
								include 'scripts/close_connection.php';										
							?>
							<tr style="font-weight: bold;">
								<td colspan="6" class="title" style='background-color: #efefef'><img src='images/icons/diamond.png' /> Diamonds:</td>
							</tr>
							<?php
								$t_carats = 0;
								$t_quantity = 0;
								include 'scripts/open_connection.php';
								$getAppliedDiamonds = "SELECT d_id, item_id, d_type_id, d_carats, d_quantity, d_notes, stone_type.template_type, stone_type.stone_name FROM diamond_info, stone_type WHERE item_id = $id AND diamond_info.d_type_id = stone_type.stone_id ORDER BY stone_type.stone_name";
								$diamondResults = mysql_query($getAppliedDiamonds);
								while($row = mysql_fetch_array($diamondResults)) {
									$t_carats = $t_carats + $row['d_carats'];
									$t_quantity = $t_quantity + $row['d_quantity'];
									echo "<tr onmouseover=\"javascript:highlight(this);\" onmouseout=\"javascript:lowlight(this)\">";
									echo "<td>$item_number</td>".
										"<td nowrap>Diamond</td>".
										"<td>{$row['d_carats']}</td>". 
										"<td>{$row['d_quantity']}</td>".
										"<td>{$row['d_notes']}</td>";
									echo "</tr>";
								}
								echo "<tr>" .
									"<td colspan='2' style='text-align: right;'><b>Totals:<b></td>" .
									"<td>" . number_format($t_carats,2) . "</td>" .
									"<td colspan='3'>" . $t_quantity . "</td>" .
									"</tr>";
								include 'scripts/close_connection.php';										
							?>							
							<tr style="font-weight: bold;">
								<td colspan="6" class="title" style='background-color: #efefef'><img src='images/icons/jadeite.png' /> Jadeite:</td>
							</tr>						
							<?php
								include 'scripts/open_connection.php';
								$getAppliedOpals = "SELECT j_id, item_id, j_type_id, j_carat, j_quantity, j_notes, stone_type.stone_name, stone_type.template_type FROM jadeite_info, stone_type WHERE item_id = $id AND jadeite_info.j_type_id = stone_type.stone_id ORDER BY stone_type.stone_name";
								$opalResults = mysql_query($getAppliedOpals) or die("Error in MySQL: " . mysql_error());
								while($row = mysql_fetch_array($opalResults)) {
									echo "<tr onmouseover=\"javascript:highlight(this);\" onmouseout=\"javascript:lowlight(this)\">";
									echo "<td>$item_number</td>".
										"<td nowrap>{$row['stone_name']}</td>".
										"<td>{$row['j_carat']}</td>". 
										"<td>{$row['j_quantity']}</td>".
										"<td>{$row['j_notes']}</td>";
									echo "</tr>";
								}
								include 'scripts/close_connection.php';										
							?>							
							<tr style="font-weight: bold;">
								<td colspan="6" class="title" style='background-color: #efefef'><img src='images/icons/opal.png' /> Opals:</td>
							</tr>						
							<?php
								include 'scripts/open_connection.php';
								$getAppliedOpals = "SELECT o_id, item_id, o_type_id, o_carat, o_quantity, o_notes, stone_type.stone_name, stone_type.template_type FROM opal_info, stone_type WHERE item_id = $id AND opal_info.o_type_id = stone_type.stone_id ORDER BY stone_type.stone_name";
								$opalResults = mysql_query($getAppliedOpals) or die("Error in MySQL: " . mysql_error());
								while($row = mysql_fetch_array($opalResults)) {
									echo "<tr onmouseover=\"javascript:highlight(this);\" onmouseout=\"javascript:lowlight(this)\">";
									echo "<td>$item_number</td>".
										"<td nowrap>{$row['stone_name']}</td>".
										"<td>{$row['o_carat']}</td>". 
										"<td>{$row['o_quantity']}</td>".
										"<td>{$row['o_notes']}</td>";
									echo "</tr>";
								}
								include 'scripts/close_connection.php';										
							?>

							</table>						
					</div>	
				</div>
			<?php
			include "scripts/footer.php";
			?>
	</div>
</body>
</html>
