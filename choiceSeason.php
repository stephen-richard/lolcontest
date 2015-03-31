<?php 
	require('php/header.inc');

		$summoner_name = $_SESSION['summoner_name'];
		$season_choice = $_GET['season_choice'];

		// Summoner id
		$summoner = searchSummoner($summoner_name);
		$summoner_id = $summoner->$summoner_name->id;

		// Summoner stats
		$seasons_stats = getSeason($summoner_id, $season_choice);
		$playerStatSummaries = $seasons_stats->playerStatSummaries;

		foreach ($playerStatSummaries as $season_stat) {

			$mode    = $season_stat->playerStatSummaryType;
			$wins    = $season_stat->wins;
			$losses  = isset($season_stat->losses) ? $season_stat->losses : "Undefined";
			$total   = ($losses != "Undefined") ? $wins + $losses : "" ;
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
?>