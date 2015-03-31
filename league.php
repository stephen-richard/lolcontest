<?php 
	//Peut être remplacé par E_WARNING, E_PARSE, E_NOTICE, E_ERROR
	error_reporting(E_ALL); 
	ini_set("display_errors", 1);

	$curl = curl_init();

	function getCurl ($url, $expire) {
		$key = "713f113e-7596-4dea-8aae-096af6cf6a41";
		$url_request = $url."api_key=".$key;

		global $curl;

		$file = 'cache/'.md5($url_request);
		if(file_exists($file) && fileatime($file) > time()-$expire){
			$data = file_get_contents($file);
		}else{
			curl_setopt($curl, CURLOPT_URL, $url_request);
	 		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
	 		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	 		$data = curl_exec($curl);
	 		file_put_contents($file, $data);

		 	if (curl_errno($curl)) { 
		        print "Error: " . curl_error($curl); 
		    }
		}
		return json_decode($data);
	}
	function getChallengers(){
		return getCurl("http://euw.api.pvp.net/api/lol/euw/v2.5/league/challenger?type=RANKED_SOLO_5x5&", 60*60*24);
	}

	function objectToArray($d) {
		 if (is_object($d)) {
		 // Gets the properties of the given object
		 // with get_object_vars function
		 $d = get_object_vars($d);
		 }
		 
		 if (is_array($d)) {
		 /*
		 * Return array converted to object
		 * Using __FUNCTION__ (Magic constant)
		 * for recursive call
		 */
		 return array_map(__FUNCTION__, $d);
		 }
		 else {
		// Return array
		return $d;
		}
	}

	function compare($a, $b) { // Make sure to give this a more meaningful name!
    	return $b->leaguePoints - $a->leaguePoints;
	}
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
			<!-- <input type="submit" class="button" name="lauch" value="launch" /> -->
			<?php 
				$challenger_request = getChallengers();
				$tier  = $challenger_request->tier;
				$queue = $challenger_request->queue;

			?>
			<h1><?php echo $tier." ".$queue; ?></h1>
			<?php 
				foreach ($challenger_request as $challenger) {
					if(is_array($challenger)){
						usort($challenger, "compare");
						foreach ($challenger as $index => $player) {
							$player_name   = $player->playerOrTeamName;
							$player_points = $player->leaguePoints;
							$player_wins   = $player->wins;
							$player_losses = $player->losses;
							$player_new    = $player->isFreshBlood;
			?>
				<div class="player">
					<ul>
						<li>
							<p><?= $index+1 ?></p>
							<?php 
								$trim_player_name = strtolower(str_replace(' ', '', $player_name));
								echo "<a href=profilestats.php?pseudo=$trim_player_name> $player_name </a>" 
							?>
							<p><?= "Points ".$player_points ?></p>
						</li>
						<li>
							<p class="win"><?= "Victoires ".$player_wins ?></p>
							<p class="losses"><?= "Défaites ".$player_losses ?></p>
							<p class="new-player"><?= ($player_new == 1) ? "Promotion récente" : "" ?></p>
						</li>
					</ul>
				</div> 
			<?php
						}
					}
				}
			?>
		</div>

		<script src="js/jquery-1.11.2.min.js"></script>
	</body>
</html>