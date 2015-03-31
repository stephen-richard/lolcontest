<?php 
	//Peut être remplacé par E_WARNING, E_PARSE, E_NOTICE, E_ERROR
	error_reporting(E_ALL); 
	ini_set("display_errors", 1);

	session_start();

	$summoner_name = $_GET['pseudo'];
	$summoner_url_name = strtolower(str_replace(' ', '', $summoner_name));
	$_SESSION['summoner_name'] = $summoner_name;
	$_SESSION['summoner_url_name'] = $summoner_url_name;

	// $summoner_id = "36079026";
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

	function searchSummoner($summoner_name) {
		$request_summoner = getCurl("http://euw.api.pvp.net/api/lol/euw/v1.4/summoner/by-name/".$summoner_name."?", 60*60);
		if(empty($request_summoner)){
			return false;
		}else{
			return $request_summoner;
		}
	}

	function getSummoner($summoner_id){
		return getCurl("http://euw.api.pvp.net/api/lol/euw/v1.4/summoner/".$summoner_id."?", 60*30);
	}

	function getStats($summoner_id){
		return getCurl("http://euw.api.pvp.net/api/lol/euw/v1.3/stats/by-summoner/".$summoner_id."/summary?season=SEASON2015&", 60*60);
	}
	function getSeason($summoner_id, $season_choice){
		return getCurl("http://euw.api.pvp.net/api/lol/euw/v1.3/stats/by-summoner/".$summoner_id."/summary?season=".$season_choice."&", 60*60);
	}
	function getGame($summoner_id){
		return getCurl("http://euw.api.pvp.net/api/lol/euw/v1.3/game/by-summoner/".$summoner_id."/recent?", 60*30);
	}

	function getChampion($championId){
		return getCurl("http://global.api.pvp.net/api/lol/static-data/euw/v1.2/champion/".$championId."?locale=fr_FR&champData=image&", 60*60*24*2);
	}

	function getChallengers(){
		return getCurl("http://euw.api.pvp.net/api/lol/euw/v2.5/league/challenger?type=RANKED_SOLO_5x5&", 60*60*24);
	}

?>