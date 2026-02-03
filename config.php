<?php

    define("RAPIDAPI_KEY", "aac4bd6784msh180d77ca164e275p18d20cjsn7601185db110");

    function getCachedApi($cacheKey, $url, $ttl = 600) {
    $cacheFile = __DIR__ . "/cache/{$cacheKey}.json";

    // αν υπάρχει cache και δεν έχει λήξει
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $ttl)) {
        return json_decode(file_get_contents($cacheFile), true);
    }

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
	      CURLOPT_MAXREDIRS => 10,
	      CURLOPT_TIMEOUT => 30,
	      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: sofascore.p.rapidapi.com",
            "x-rapidapi-key: " . RAPIDAPI_KEY
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return null;
    }

    file_put_contents($cacheFile, $response);

    return json_decode($response, true);
    }


    function getPlayerImg($playerid) {
    $filePath = __DIR__ . "/images/player_$playerid.png"; 
    
    // Αν υπάρχει ήδη το logo
    if (file_exists($filePath)) {
        return "images/player_$playerid.png";
    }

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://sofascore.p.rapidapi.com/players/get-image?playerId=" . $playerid,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
          "x-rapidapi-host: sofascore.p.rapidapi.com",
		      "x-rapidapi-key: " . RAPIDAPI_KEY
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err || !$response) {
        return "images/player_default.png";
    }

    file_put_contents($filePath, $response);

    return "images/player_$playerid.png";
    }


    function getClubLogo($clubId) {

    $filePath = __DIR__ . "/images/logo_$clubId.png";
    
    // Αν υπάρχει ήδη το logo
    if (file_exists($filePath)) {
        return "images/logo_$clubId.png";
    }

      $curl_clublogo = curl_init();

      curl_setopt_array($curl_clublogo, [
        CURLOPT_URL => "https://sofascore.p.rapidapi.com/teams/get-logo?teamId=" . $clubId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
		      "x-rapidapi-host: sofascore.p.rapidapi.com",
		      "x-rapidapi-key: " . RAPIDAPI_KEY
        ],
      ]);

      $response_clublogo = curl_exec($curl_clublogo);
      $err_clublogo = curl_error($curl_clublogo);
      curl_close($curl_clublogo);

      if ($err_clublogo || !$response_clublogo) {
        return "images/logo_default.png";
      }

      file_put_contents($filePath, $response_clublogo);

      return "images/logo_$clubId.png";
    }



    function getCompLogo($compId) {

        $filePath = __DIR__ . "/images/comp_$compId.png";
    
        // Αν υπάρχει ήδη το logo
        if (file_exists($filePath)) {
            return "images/comp_$compId.png";
        }


      $curl_clogo = curl_init();

      curl_setopt_array($curl_clogo, [
        CURLOPT_URL => "https://sofascore.p.rapidapi.com/tournaments/get-logo?tournamentId=" . $compId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
		      "x-rapidapi-host: sofascore.p.rapidapi.com",
		      "x-rapidapi-key: " . RAPIDAPI_KEY
        ],
      ]);

      $response_clogo = curl_exec($curl_clogo);
      $err_clogo = curl_error($curl_clogo);
      curl_close($curl_clogo);


      if ($err_clogo || !$response_clogo) {
        return "images/comp_default.png";
      }

      file_put_contents($filePath, $response_clogo);

      return "images/comp_$compId.png";
    }


    function getCoachImg($id) {

    $filePath = __DIR__ . "/images/coach_$id.png";

    if (file_exists($filePath)) {
        return "images/coach_$id.png";
    }

      $curl_coachimg = curl_init();

      curl_setopt_array($curl_coachimg, [
        CURLOPT_URL => "https://sofascore.p.rapidapi.com/managers/get-image?managerId=" . $id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
		      "x-rapidapi-host: sofascore.p.rapidapi.com",
		      "x-rapidapi-key: " . RAPIDAPI_KEY
        ],
      ]);

      $response_coachimg = curl_exec($curl_coachimg);
      $err_coachimg = curl_error($curl_coachimg);
      curl_close($curl_coachimg);

      if ($err_coachimg || !$response_coachimg) {
        return "images/player_default.png";
      }

      file_put_contents($filePath, $response_coachimg);

      return "images/coach_$id.png";
    }

?>

