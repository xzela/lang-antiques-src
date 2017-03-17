<?php

//check login
include 'scripts/utils/check_login.php';
//Get item info 
include 'scripts/get_item_info.php';


?>

<html>
<head>
	<title>Inventory - Edit Materials for item: <?php echo $idn; ?> - <?php echo $title; ?></title>
	<link href="styles/styles.css" rel="stylesheet" type="text/css" >
	<script language="javascript" type="text/javascript" src="scripts/ajax_engine.js"></script>
	<script language="javascript" type="text/javascript" src="scripts/pseudolink_class.js"></script>
	<script language="javascript" type="text/javascript" src="scripts/util_class.js"></script>
	<script language="javascript" type="text/javascript" src="scripts/materials/materials.js"></script>
	<script language="javascript" type="text/javascript">
	//Set the item_id here...
	var item_id = <?php echo $id; ?>;
	
	/* <![CDATA[ */
	function parseXML() {
		if (http_request.readyState == 4) {
			if (http_request.status == 200) {
				var xmldoc = http_request.responseXML;
				var root = xmldoc.getElementsByTagName('root').item(0);
				var locat = root.getAttribute('location')
				if (locat == "saved") {
					//do nothing
				}
				else
				document.getElementById(locat).innerHTML = "";

				for (var iNode = 0; iNode < root.childNodes.length; iNode++) {
					var node = root.childNodes.item(iNode);
					for (i = 0; i < node.childNodes.length; i++) {
						var sibl = node.childNodes.item(i);
						//alert(sibl.length);
						var len = parseInt(sibl.childNodes.length / 2);
						var obj = new Object();
						for (x = 0; x < sibl.childNodes.length; x++) {
							var sibl2 = sibl.childNodes.item(x);
							var sibl3;
							if (sibl2.childNodes.length > 0) {
								if (locat == "material") {
									sibl3 = sibl2.childNodes.item(0);
									obj.names = sibl3.data;
									obj.id = sibl2.getAttribute('id');
									obj.karat = sibl2.getAttribute('karat');
									//alert(.names);
									addMats(locat, obj);
								}
							}
						}
					}
				}
			}
			else {
				alert(http_request.status + http_request.responseText + ' There was a problem with the request. Please try again.');
			}
		}
	}
	function addMats (locat, obj) {
		var dv = document.getElementById(locat);
		var item = new PseudoLink();
		item.setFields(obj.id + "ma", "appendMaterial('" + obj.id + "ma','" + obj.names + "', '" + obj.karat + "')", obj.names);
		dv.appendChild(item.show());
		dv.innerHTML = dv.innerHTML;
	}

	/*]]>*/
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
				<h2>Edit Materials for item: <?php echo $title;?></h2>
				<ul id="sub_menu">
					<li><a href='edit_item.php?item_id=<?php echo $id ?>'>&lt;&lt; Back to Item</a></li>
				</ul>
				<h3 class="item">Materials</h3>	
				<p>Add Materials. You can search for materials by typing their names in the search box below. If you are unsure on how to spell a specific material, you can click 'Show All' to see all of them.</p>
				<div class="item_section">
					<b>Material:</b> <input title="Start typing a material and it will display below" id='mat_input' type='text' onkeyup='javascript:getMaterials(0);'> <a id="mats_link" href='javascript:getMaterials(1)'>Show all</a>
					<div id="material">
					</div>
					<div id="mats_karat">							
					</div>
					Applied Materials:
					<div id="applied_mats">
					<?php
						include 'scripts/open_connection.php';
						$output = "";
						$getMats = "SELECT item_id, item_material.material_id, materials.material_name, karats FROM item_material, materials WHERE item_id = $id AND item_material.material_id = materials.material_id ORDER BY materials.material_name ASC";
						$result = mysql_query($getMats);
						for($x = 0 ; $x < mysql_num_rows($result) ; $x++){
							$row = mysql_fetch_assoc($result);
							if ($row['karats'] == 1) {
								$getKarats = "SELECT * from item_material_karat WHERE item_id = $id AND material_id = $row[material_id]";
								$kresult = mysql_query($getKarats);
								while($krow = mysql_fetch_array($kresult)) {
									$output .= "\t\t<a id='" . $row['material_id'] . "mb' class='warnpseudo' href='javascript:removeMaterial(\"{$row['material_id']}mb\", \"{$row['material_name']}\", \"{$krow['karat']}\")'>" . $row['material_name'] . "(" . $krow['karat'] ."k), </a>\n";
								}
							}
							else {
								//$xml_output .= "\t\t<id>" . $row['label_id'] . "</id>\n";
								$output .= "<a id='" . $row['material_id'] . "mb' class='warnpseudo' href='javascript:removeMaterial(\"{$row['material_id']}mb\", \"{$row['material_name']}\")'>" . $row['material_name'] . ", </a>";
							}
						}
						echo $output;		
						include 'scripts/close_connection.php';
					?>
					</div>
				</div>
			</div>
			<?php
			include "scripts/footer.php";
			?>
</div>
</body>
</html>
