<?php
include 'scripts/utils/check_login.php';
include 'scripts/get_item_info.php';

$id = $_REQUEST['item_id'];

$message = "";

if (isset($_REQUEST['item_id']) && isset($_REQUEST['add_stone_id']) && isset($_REQUEST['template_type'])) {

	if ($_REQUEST['template_type'] == 1) {
	
		include 'scripts/open_connection.php';
		$insertStone = "INSERT INTO stone_info (item_id, gem_type_id) VALUES ('$_REQUEST[item_id]', '$_REQUEST[add_stone_id]')";
		$results = mysql_query($insertStone) or die ("Error is: " . mysql_error());
		$stone_id = mysql_insert_id();
		header("Location: edit_stone.php?item_id=$id&stone_id=$stone_id");
		include 'scripts/close_connection.php';
	}
	if ($_REQUEST['template_type'] == 2) {
	
		include 'scripts/open_connection.php';
		$insertStone = "INSERT INTO pearl_info (item_id, p_type_id) VALUES ('$_REQUEST[item_id]', '$_REQUEST[add_stone_id]')";
		$results = mysql_query($insertStone) or die ("Error is: " . mysql_error());
		$stone_id = mysql_insert_id();
		header("Location: edit_pearl.php?item_id=$id&pearl_id=$stone_id");

		include 'scripts/close_connection.php';
	}
	
	if ($_REQUEST['template_type'] == 3) {
	
		include 'scripts/open_connection.php';
		$insertStone = "INSERT INTO diamond_info (item_id, d_type_id) VALUES ('$_REQUEST[item_id]', '$_REQUEST[add_stone_id]')";
		$results = mysql_query($insertStone) or die ("Error is: " . mysql_error());
		$stone_id = mysql_insert_id();
		header("Location: edit_diamond.php?item_id=$id&diamond_id=$stone_id");

		include 'scripts/close_connection.php';
	}
	if ($_REQUEST['template_type'] == 4) {
	
		include 'scripts/open_connection.php';
		$insertStone = "INSERT INTO jadeite_info (item_id, j_type_id) VALUES ('$_REQUEST[item_id]', '$_REQUEST[add_stone_id]')";
		$results = mysql_query($insertStone) or die ("Error is: " . mysql_error());
		$stone_id = mysql_insert_id();
		header("Location: edit_jadeite.php?item_id=$id&jadeite_id=$stone_id");

		include 'scripts/close_connection.php';
	}
	
	if ($_REQUEST['template_type'] == 5) {
	
		include 'scripts/open_connection.php';
		$insertStone = "INSERT INTO opal_info (item_id, o_type_id) VALUES ('$_REQUEST[item_id]', '$_REQUEST[add_stone_id]')";
		$results = mysql_query($insertStone) or die ("Error is: " . mysql_error());
		$stone_id = mysql_insert_id();
		header("Location: edit_opal.php?item_id=$id&opal_id=$stone_id");

		include 'scripts/close_connection.php';
	}

	//header("Location: select_gemstone.php?item_id=$id");
	
	
}

if (isset($_REQUEST['item_id']) && isset($_REQUEST['remove_stone_id']) && isset($_REQUEST['template_id'])) {
	if ($_REQUEST['template_id'] == 1) {
	
		include 'scripts/open_connection.php';
		$removeStone = "DELETE FROM stone_info WHERE item_id = $_REQUEST[item_id] AND gem_id = $_REQUEST[remove_stone_id]";
		$results = mysql_query($removeStone) or die ("Error is: " . mysql_error());
		include 'scripts/close_connection.php';
	}
	if ($_REQUEST['template_id'] == 2) {
	
		include 'scripts/open_connection.php';
		$removeStone = "DELETE FROM pearl_info WHERE item_id = $_REQUEST[item_id] AND p_id = $_REQUEST[remove_stone_id]";
		$results = mysql_query($removeStone) or die ("Error is: " . mysql_error());
		include 'scripts/close_connection.php';
	}
	if ($_REQUEST['template_id'] == 3) {
	
		include 'scripts/open_connection.php';
		$removeStone = "DELETE FROM diamond_info WHERE item_id = $_REQUEST[item_id] AND d_id = $_REQUEST[remove_stone_id]";
		$results = mysql_query($removeStone) or die ("Error is: " . mysql_error());
		include 'scripts/close_connection.php';
	}
	if ($_REQUEST['template_id'] == 4) {
	
		include 'scripts/open_connection.php';
		$removeStone = "DELETE FROM jadeite_info WHERE item_id = $_REQUEST[item_id] AND j_id = $_REQUEST[remove_stone_id]";
		$results = mysql_query($removeStone) or die ("Error is: " . mysql_error());
		include 'scripts/close_connection.php';
	}
	
	if ($_REQUEST['template_id'] == 5) {
	
		include 'scripts/open_connection.php';
		$removeStone = "DELETE FROM opal_info WHERE item_id = $_REQUEST[item_id] AND o_id = $_REQUEST[remove_stone_id]";
		$results = mysql_query($removeStone) or die ("Error is: " . mysql_error());
		include 'scripts/close_connection.php';
	}

	header("Location: select_gemstone.php?item_id=$id");
}

?>

<html>
<head>
	<title>Inventory - Select Gemstone for item: <?php echo $idn; ?> - <?php echo $title; ?></title>
	<link href="styles/styles.css" rel="stylesheet" type="text/css" >
	<script language="javascript" type="text/javascript">
	</script>
	<script language="Javascript" type="text/javascript">
		//neato color functions.
		function highlight(foo) {
			foo.style.backgroundColor = "#ffd";
		}
		function lowlight(foo) {
			foo.style.backgroundColor = "#fff";
		}
	</script>	
	<style>
	#info dl {
		clear:both;
		width:400px;
		height:8em;
		margin:2em auto;
	}

	#info dt {
		margin-bottom:1em; 
		font-weight:bold;
	}

	#info dd {
		width:20%; 
		float:left; 
		margin: 10px 0 10px 0;
	}
	#info li {
		list-style-type: none;
		padding: 0;
		margin: 0;
	}
	</style>
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
				<h2>Select Gemstone for item: <?php echo $idn; ?> - <?php echo $title; ?></h2>
				<ul id="sub_menu">
					<li><a href="edit_item.php?item_id=<?php echo $id; ?>">&lt;&lt; Back to Item</a></li>
				</ul>
				<div>
					<?php echo $message; ?>
					<p>Here are all of the Gemstones that are currently applied to this item. If you wish to add another gemstone, simply click on the Name of the gemstone below.</p>
					<div>
						<table class="list_table">
							<tr>
								<th nowrap>Item Number</td>
								<th>Stone</th>
								<th width="10%">Weight</th>
								<th width="10%">Quantity</th>
								<th align="center" width="70%">Notes</th>
								<th width="10%">Options</th>
							</tr>
							<tr style="font-weight: bold;">
								<td colspan="6" class="title" style='background-color: #efefef'><img src='../images/icons/ruby.png' /> Gemstones:</td>
							</tr>
							<?php
								include 'scripts/open_connection.php';
								$getAppliedStones = "SELECT gem_id, item_id, gem_type_id, gem_carat, gem_quantity, gem_notes, stone_type.template_type, stone_type.stone_name FROM stone_info, stone_type WHERE item_id = $id AND stone_info.gem_type_id = stone_type.stone_id ORDER BY stone_type.stone_name";
								$aStoneResults = mysql_query($getAppliedStones);
								while($row = mysql_fetch_array($aStoneResults)) {
									echo "<tr onmouseover=\"javascript:highlight(this);\" onmouseout=\"javascript:lowlight(this)\">";
									echo "<td>$idn</td>".
										"<td><a href='edit_stone.php?item_id=$id&stone_id={$row['gem_id']}'>{$row['stone_name']}</a></td>".
										"<td>{$row['gem_carat']}</td>". 
										"<td>{$row['gem_quantity']}</td>".
										"<td>";
											$summary = $row['gem_notes'];
											$limit = 100;
											if (strlen($summary) > $limit) {
												$summary = substr($summary, 0, strrpos(substr($summary, 0, $limit), ' ')) . " ... <br />[<a href='edit_stone.php?item_id=$id&stone_id={$row['gem_id']}'>read more</a>]";
											}
											echo $summary;
										echo "</td>" .
										"<td nowrap class='edit'><a href='edit_stone.php?item_id=$id&stone_id={$row['gem_id']}'>Edit</a> | <a class='red' href='select_gemstone.php?item_id=$id&remove_stone_id={$row['gem_id']}&template_id={$row['template_type']}'>Remove</a></td>";
									echo "</tr>";
								}
								include 'scripts/close_connection.php';										
							?>
							<tr style="font-weight: bold;">
								<td colspan="6" class="title" style='background-color: #efefef'><img src='../images/icons/pearl.png' /> Pearls:</td>
							</tr>
							<?php
								include 'scripts/open_connection.php';
								$getAppliedDiamonds = "SELECT p_id, item_id, p_type_id, p_weight, p_quantity, p_notes, stone_type.stone_name, stone_type.template_type FROM pearl_info, stone_type WHERE item_id = $id AND pearl_info.p_type_id = stone_type.stone_id ORDER BY stone_type.stone_name";
								$diamondResults = mysql_query($getAppliedDiamonds);
								while($row = mysql_fetch_array($diamondResults)) {
									echo "<tr onmouseover=\"javascript:highlight(this);\" onmouseout=\"javascript:lowlight(this)\">";
									echo "<td>$idn</td>".
										"<td ><a href='edit_pearl.php?item_id=$id&pearl_id={$row['p_id']}'>{$row['stone_name']}</a></td>".
										"<td>{$row['p_weight']}</td>". 
										"<td>{$row['p_quantity']}</td>".
										"<td>";
											$summary = $row['p_notes'];
											$limit = 100;
											if (strlen($summary) > $limit) {
												$summary = substr($summary, 0, strrpos(substr($summary, 0, $limit), ' ')) . " ... [<a href='edit_pearl.php?item_id=$id&pearl_id={$row['p_id']}'>read more</a>]";
											}
											echo $summary;
										echo "</td>" .
										"<td nowrap class='edit'><a href='edit_pearl.php?item_id=$id&pearl_id={$row['p_id']}'>Edit</a> | <a class='red' href='select_gemstone.php?item_id=$id&remove_stone_id={$row['p_id']}&template_id={$row['template_type']}'>Remove</a></td>";
									echo "</tr>";
								}
								include 'scripts/close_connection.php';										
							?>
							<tr style="font-weight: bold;">
								<td colspan="6" class="title" style='background-color: #efefef'><img src='../images/icons/diamond.png' /> Diamonds:</td>
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
									echo "<td>$idn</td>".
										"<td ><a href='edit_diamond.php?item_id=$id&diamond_id={$row['d_id']}'>Diamond</a></td>".
										"<td>{$row['d_carats']}</td>". 
										"<td>{$row['d_quantity']}</td>".
										"<td>";
											$summary = $row['d_notes'];
											$limit = 100;
											if (strlen($summary) > $limit) {
												$summary = substr($summary, 0, strrpos(substr($summary, 0, $limit), ' ')) . " ... [<a href='edit_diamond.php?item_id=$id&diamond_id={$row['d_id']}'>read more</a>]";
											}
											echo $summary;
										echo "</td>" .
										"<td nowrap class='edit'><a href='edit_diamond.php?item_id=$id&diamond_id={$row['d_id']}'>Edit</a> | <a class='red' href='select_gemstone.php?item_id=$id&remove_stone_id={$row['d_id']}&template_id={$row['template_type']}'>Remove</a></td>";
									echo "</tr>";
								}
								echo "<tr>" .
									"<td colspan='2' style='text-align: right;'>Totals: </td>" .
									"<td>" . number_format($t_carats,2) . "</td>" .
									"<td colspan='3'>" . $t_quantity . "</td>" .
									"</tr>";
								include 'scripts/close_connection.php';										
							?>							
							<tr style="font-weight: bold;">
								<td colspan="6" class="title" style='background-color: #efefef'><img src='../images/icons/jadeite.png' /> Jadeite:</td>
							</tr>						
							<?php
								include 'scripts/open_connection.php';
								$getAppliedOpals = "SELECT j_id, item_id, j_type_id, j_carat, j_quantity, j_notes, stone_type.stone_name, stone_type.template_type FROM jadeite_info, stone_type WHERE item_id = $id AND jadeite_info.j_type_id = stone_type.stone_id ORDER BY stone_type.stone_name";
								$opalResults = mysql_query($getAppliedOpals) or die("Error in MySQL: " . mysql_error());
								while($row = mysql_fetch_array($opalResults)) {
									echo "<tr onmouseover=\"javascript:highlight(this);\" onmouseout=\"javascript:lowlight(this)\">";
									echo "<td>$idn</td>".
										"<td ><a href='edit_jadeite.php?item_id=$id&jadeite_id={$row['j_id']}'>{$row['stone_name']}</a></td>".
										"<td>{$row['j_carat']}</td>". 
										"<td>{$row['j_quantity']}</td>".
										"<td>";
											$summary = $row['j_notes'];
											$limit = 100;
											if (strlen($summary) > $limit) {
												$summary = substr($summary, 0, strrpos(substr($summary, 0, $limit), ' ')) . " ... [<a href='edit_pearl.php?item_id=$id&pearl_id={$row['j_id']}'>read more</a>]";
											}
											echo $summary;
										echo "</td>" .
										"<td nowrap class='edit'><a href='edit_jadeite.php?item_id=$id&jadeite_id={$row['j_id']}'>Edit</a> | <a class='red' href='select_gemstone.php?item_id=$id&remove_stone_id={$row['j_id']}&template_id={$row['template_type']}'>Remove</a></td>";
									echo "</tr>";
								}
								include 'scripts/close_connection.php';										
							?>							
							<tr style="font-weight: bold;">
								<td colspan="6" class="title" style='background-color: #efefef'><img src='../images/icons/opal.png' /> Opals:</td>
							</tr>						
							<?php
								include 'scripts/open_connection.php';
								$getAppliedOpals = "SELECT o_id, item_id, o_type_id, o_carat, o_quantity, o_notes, stone_type.stone_name, stone_type.template_type FROM opal_info, stone_type WHERE item_id = $id AND opal_info.o_type_id = stone_type.stone_id ORDER BY stone_type.stone_name";
								$opalResults = mysql_query($getAppliedOpals) or die("Error in MySQL: " . mysql_error());
								while($row = mysql_fetch_array($opalResults)) {
									echo "<tr onmouseover=\"javascript:highlight(this);\" onmouseout=\"javascript:lowlight(this)\">";
									echo "<td>$idn</td>".
										"<td ><a href='edit_opal.php?item_id=$id&opal_id={$row['o_id']}'>{$row['stone_name']}</a></td>".
										"<td>{$row['o_carat']}</td>". 
										"<td>{$row['o_quantity']}</td>".
										"<td>";
											$summary = $row['o_notes'];
											$limit = 100;
											if (strlen($summary) > $limit) {
												$summary = substr($summary, 0, strrpos(substr($summary, 0, $limit), ' ')) . " ... [<a href='edit_pearl.php?item_id=$id&pearl_id={$row['o_id']}'>read more</a>]";
											}
											echo $summary;
										echo "</td>" .
										"<td nowrap class='edit'><a href='edit_opal.php?item_id=$id&opal_id={$row['o_id']}'>Edit</a> | <a class='red' href='select_gemstone.php?item_id=$id&remove_stone_id={$row['o_id']}&template_id={$row['template_type']}'>Remove</a></td>";
									echo "</tr>";
								}
								include 'scripts/close_connection.php';										
							?>

							</table>						
					</div>					
					<div id="list_gems">
					<h3>Gemstones</h3>
					<p>Here is a list of all of the known Gemstone within your database. </p>
			
						<dl id="info">
							<dt></dt>
						<?php
							include 'scripts/open_connection.php';
							$getStones = "SELECT stone_id, stone_name, template_type FROM stone_type ORDER BY stone_name ASC";
							$stoneresults = mysql_query($getStones);
							$i = 0;
							while ($lrow = mysql_fetch_array($stoneresults)) {
								//echo "$i % 15 = " . $i%15 . "<br />";
								$stone_name = $lrow['stone_name'];
								if ($lrow['template_type'] == 1) {
									$stone_name = "<img src='../images/icons/ruby.png' /> " . $lrow['stone_name'];
								}
								else if ($lrow['template_type'] == 2) {
									$stone_name = "<img src='../images/icons/pearl.png' /> " . $lrow['stone_name'];
								}
								else if ($lrow['template_type'] == 3) {
									$stone_name = "<img src='../images/icons/diamond.png' /> " . $lrow['stone_name'];
								}
								else if ($lrow['template_type'] == 4) {
									$stone_name = "<img src='../images/icons/jadeite.png' /> " . $lrow['stone_name'];
								}
								else if ($lrow['template_type'] == 5) {
									$stone_name = "<img src='../images/icons/opal.png' /> " . $lrow['stone_name'];
								}

								
								if ($i%15 == 0) {
									echo "<dd><ul><li><a class='green' href='select_gemstone.php?item_id=$id&add_stone_id={$lrow['stone_id']}&template_type={$lrow['template_type']}'>{$stone_name}</a></li> ";
								}
								else if ($i%15 == 14) {
									echo "<li><a class='green' href='select_gemstone.php?item_id=$id&add_stone_id={$lrow['stone_id']}&template_type={$lrow['template_type']}'>{$stone_name}</a></li></ul></dd> ";
								}
								else {
								
									echo "<li><a class='green' href='select_gemstone.php?item_id=$id&add_stone_id={$lrow['stone_id']}&template_type={$lrow['template_type']}'>{$stone_name}</a></li> ";
								}
								$i++;
							} 
							//echo 9%8;
							include 'scripts/close_connection.php';						
						?>
						</dl>
					</div>
					

				</div>
			</div>
			<?php
			include "scripts/footer.php";
			?>
</div>
</body>
</html>
