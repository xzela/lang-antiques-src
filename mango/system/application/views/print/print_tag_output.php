<html>
	<head>
		<script type='text/javascript'>
			
		</script>
		<style type='text/css'>
			body {
				font-family: sans-serif;
				font-weight: bold;
				font-size: 5pt;
				margin: 0px;
				padding: 0px;
			}
			#container {
				margin-top: 5px;
				width: 1.8in;
				
			}
			.line {
				display: block;
				height: 9px;
				letter-spacing: .5px;
				margin-left: 111px;
			}
			
			.spacer {
				padding-top: 1px;
			}
			
			.center {
				text-align: center;
			}
		</style>
	</head>
	<body>
		<div id='container'>
			<div class='line'><?php echo $tag_data['line_1']; ?></div>
			<div class='line'><?php echo $tag_data['line_2']; ?></div>
			<div class='line'><?php echo $tag_data['line_3']; ?></div>
			<div class='line'><?php echo $tag_data['line_4']; ?></div>
			<div class='line'><?php echo $tag_data['line_5']; ?></div>
			<div class='line'>&nbsp;</div> <!-- Spacper -->
			<div class='line center'><?php echo $item_data['item_number']; ?></div>
			<div class='line'>&nbsp;</div><!-- Spacper -->
			<div class='line'>&nbsp;</div><!-- Spacper -->
			<div class='line center'>$<?php echo number_format($item_data['item_price'], 2);?></div>
		</div>
	</body>
</html>
