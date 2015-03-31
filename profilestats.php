<?php 
	require('php/header.inc') 
?>
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

		<form >
			<select name="season_choice" id="season_choice">
				<option value="SEASON2015">Saison 5</option>
				<option value="SEASON2014">Saison 4</option>
				<option value="SEASON3">Saison 3</option>
			</select>
		</form>
		
		<?php 
			$summoners = searchSummoner($_SESSION['summoner_url_name']);
			if($summoners === false){
				echo "Pas d'invocateur avec le pseudo ".$summoner_name. ". <a href=index.php alt=accueil>Chercher à nouveau ?</a>";
			}else{
				$summoner_id = $summoners->$summoner_url_name->id;
				echo "<a href=profile.php alt=accueil>Retour</a>";
		 		echo "<h1>".$summoner_name."</h1>";
		?>
			<div id="game-stats">
				<?php
			 		// Summoner stats
					$seasons_stats = getStats($summoner_id);

					if(isset($seasons_stats->playerStatSummaries)){
						$playerStatSummaries = $seasons_stats->playerStatSummaries;

						foreach ($playerStatSummaries as $season_stat) {

							$mode   = $season_stat->playerStatSummaryType;
							$wins   = $season_stat->wins;
							$losses = isset($season_stat->losses) ? $season_stat->losses : "Undefined";
							$total  = ($losses != "Undefined") ? $wins + $losses : "" ;
							$percent = ($wins != 0 && $total != 0) ? ($wins / $total) * 100 : 0;

							$in_game_stats = $season_stat->aggregatedStats;
							
							$total_champions = isset($in_game_stats->totalChampionKills) ? $in_game_stats->totalChampionKills : "0";
							$total_minions   = isset($in_game_stats->totalMinionKills) ? $in_game_stats->totalMinionKills : "Nc";
							$total_turrets   = isset($in_game_stats->totalTurretsKilled) ? $in_game_stats->totalTurretsKilled : "Nc";
							$total_assists   = isset($in_game_stats->totalAssists) ? $in_game_stats->totalAssists : "0";
				?>
						<div class="mode-stats">
							<div class="game-mode">
								<p>Mode de jeu : <?= $mode ?> </p> 
							</div>
							<div class="ratio">
								<div class="win">
									<p>Victoire : <?= $wins ?></p>
								</div>
								<div class="losses">
									<p>Défaites : <?= $losses ?></p>
								</div>
								<div class="difference">
				<?php
									if($losses != "Undefined"){
				?>
									<progress max="<?php echo $total; ?>" value="<?php echo $wins; ?>">
										<p style="width:80%" data-value="<?php echo $wins; ?>">Victoires</p>
										<div class="progress-bar">
											<span style="width: <?php echo $percent; ?>%"><?php echo $percent; ?>%</span>
										</div>
									</progress>
				<?php
									}
				?>
								</div>
							</div>
							<div class="season-details">
								<ul>
									<li>
									<p><?= $total_champions ?> Tués</p>
									</li>
									<li>
										<p><?= $total_assists ?> Assistances</p>
									</li>
									<li>
										<img src="assets/minionhead.png" alt="minion">
										<p><?= $total_minions ?> Minions</p> 
									</li>
									<li>
										<img src="assets/turret.png" alt="tower">
										<p><?= $total_turrets ?> Tourelles</p>
									</li>
								</ul>
							</div>
						</div>
				<?php
						}
					}else{
						echo 'Identifiant invalide<a href="index.php">Retour</a>';
					}
			}
		?>
			</div>
		</div>
		
		<!-- <script src="js/jquery-1.11.2.min.js"></script> -->
		<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
		<script src="js/script.js"></script>
		<script src="js/seasonsstats.js"></script>
	</body>
</html>
	