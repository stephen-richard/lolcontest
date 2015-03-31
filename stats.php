<?php 
	require('php/header.inc');
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
			<div class="game-stats">
				<?php
					$summoners = searchSummoner($_SESSION['summoner_url_name']);

					// $summoners = getCurl("http://euw.api.pvp.net/api/lol/euw/v1.4/summoner/by-name/".$summoner_name."?");
					if($summoners === false){
						echo "Pas d'invocateur avec le pseudo ".$summoner_name. ". <a href=index.php alt=accueil>Chercher à nouveau ?</a>";
					}else{
						foreach($summoners as $summoner) {
							$summoner_id = $summoner->id;
							$summoner_level = $summoner->summonerLevel;

							$games_played = getGame($summoner_id);
					 		// echo "<h1>L'invocateur : ".$summoner_name." n'existe pas.</h1><br/>";
					 		echo "<a href=index.php alt=accueil>Retour à l'accueil</a>";
					 		echo "<h1>".$summoner_name."</h1>";
							// var_dump($games_played);
							
							if($games_played){
								$game_played = $games_played->games;
							
								foreach ($game_played as $game) {
									// var_dump($game);
									$game_mode = $game->gameMode;
									$sub_type = $game->subType;
									$date = $game->createDate;
									
									$championId = $game->championId;
									$games_stats = $game->stats;
									$game_duration = $games_stats->timePlayed;
									$win = $games_stats->win;
									$golds = $games_stats->goldEarned;
									
									?>
									
									<div class="match">
										<div class="game-general">
											<ul>
												<li>
													<p>Mode</p>
													<p><?php if($sub_type == "NONE") echo "Perso"; else echo $sub_type; ?></p>
												</li>
												<li>
													<p>Date</p>
													<p><?php echo date('d/m/Y',substr((string)$date,0,-3)); ?></p>
												</li>
												<li>
													<p>Durée</p>
													<p><?php echo gmDate('H:i:s',$game_duration)." minutes"; ?></p>
												</li>
												<li>
													<p>Résultat</p>
													<?php if($win == 1)echo '<p class="win">Victoire <br/>'; else echo '<p class="defeat">Défaite </p><br/>'; ?>
												</li>
											</ul>
										</div>
									<?php
									
									$get_champion = getChampion($championId);
									$champion_name = $get_champion->name;
									$champion_image = $get_champion->image->full;

									?>
										<div class="game-details">
											<ul>
												<li>
													<img src="assets/champion/<?= $champion_image ?>" alt="<?php echo $champion_name; ?>">
													<p><?php echo $champion_name; ?></p>
												</li>
												<li>
													<div class="kills">
														<p><?php if(isset($games_stats->championsKilled)){ $kills = $games_stats->championsKilled; echo $kills." ";}else echo "0"; ?>Tués</p>
													</div>
													<div class="assists">
														<p><?php if(isset($games_stats->assists)){ $assists = $games_stats->assists; echo $assists." ";}else echo "0"; ?>Assistances</p>
													</div>
													<div class="deaths">
														<p><?php if(isset($games_stats->numDeaths)){ $deaths = $games_stats->numDeaths; echo $deaths." ";}else echo "0"; ?>Morts</p>
													</div>
												</li>
												<li>
													<div class="golds">
														<p>Gold<?php echo " ".$golds; ?></p>
													</div>
													<div class="minions">
														<p>Minions<?php if(isset($games_stats->minionsKilled)){$minions_killed = $games_stats->minionsKilled;echo " ".$minions_killed;} else echo " 0"; ?></p>
													</div>
												</li>
												<li>
													<button class="savoir-plus">En voir plus</button>
												</li>
											</ul>
										</div>

										<div class="game-players hidden">
										<?php 

											$team = $games_stats->team;

											if(isset($game->fellowPlayers)){
												$players = $game->fellowPlayers;
												foreach ($players as $fellow_player) {
													$fellow_player_id = $fellow_player->summonerId;
													$fellow_champ_id = $fellow_player->championId;

													$fellow_player_team = $fellow_player->teamId;

													$fellow_champ_infos = getChampion($fellow_champ_id);
													$fellow_champ_name = $fellow_champ_infos->name;
													$fellow_champ_img = $fellow_champ_infos->image->full;

													$champ_with = array();
													$champ_versus = array();

													$fellow_player_infos = getSummoner($fellow_player_id);

													foreach ($fellow_player_infos as $fellow_player) {
														if(empty($fellow_player->name)){
															echo "<p>Pas d'informations pour ce joueur.</p>";
														}else{
															$fellow_player_name = $fellow_player->name;
															$fellow_player_id = $fellow_player->id;
															$summoner_name = $fellow_player_name;

															$trim_fellow_player_name = strtolower(str_replace(' ', '', $fellow_player_name));

															$count = 1;
															if($fellow_player_team == $team){
																$team_assign_with = 'with';
																$team_with = array();
																$with = "";
															?>
															<div class="width">With : 
																<?php echo "<a href=stats.php?pseudo=".$trim_fellow_player_name." class=".$team_assign_with.">".$trim_fellow_player_name."</a>"; ?>
															</div>
															<?php
																// ,"<img src=assets/champion/".$fellow_champ_img." alt=".$fellow_champ_name."/>"
															}else{
																$team_assign_versus = 'versus';
																$versus = "";
															?>
															<div class="versus">Versus : 
																<?php echo "<a href=stats.php?pseudo=".$trim_fellow_player_name." class=".$team_assign_versus.">".$trim_fellow_player_name."</a>"; ?>
															</div>
															<?php
															}
														}
													}
												}
											}else{
												?>
													<p>Pas d'autres joueurs en partie personnalisée</p>
												<?php
											}
											?>
										</div>
									</div>
									<?php
								}
							}else{
									echo 'Identifiant invalide<a href="index.php">Retour</a>';
							}
					 	}
					}
				 ?>
			</div>
		</div>
		
		<!-- <script src="js/jquery-1.11.2.min.js"></script> -->
		<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
		<script src="js/script.js"></script>
	</body>
</html>
	