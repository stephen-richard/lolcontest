<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>League of Legends stats</title>
		<link rel="stylesheet" href="index.css">
	</head>
	<body>
		<?php 
			require('php/menu.inc');
		?>
		
		<div class="content">
			<h1>Match history</h1>
			<!-- <input type="submit" class="button" name="lauch" value="launch" /> -->
			<div class="search">
				<form action="stats.php" method="get">
				<input type="text" name="pseudo" placeholder="Enter summoner name" id="summoner-name">
				<input type="submit" class="search-summoner" value="Search">
			</form>
			</div>
		</div>

		<script src="js/jquery-1.11.2.min.js"></script>
	</body>
</html>
