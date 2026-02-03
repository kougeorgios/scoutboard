<?php

  require_once "config.php";
  error_reporting(E_ERROR | E_PARSE); 


  //TOP PLAYERS PREMIER LEAGUE
      $responseData = getCachedApi(
        "topplayersPr_{17}", 
        "https://sofascore.p.rapidapi.com/tournaments/get-top-players?tournamentId=17&seasonId=76986", 
        86400
      );

    if ($responseData && isset($responseData['topPlayers']['rating'])) {
      $players = $responseData['topPlayers']['rating'];
      $playerData = [];
      

      $pre_logo = getCompLogo(17);

      $count = 0;
      foreach ($players as $player) {
        if ($count > 6) break;
        $playerName = isset($player['player']['name']) ? $player['player']['name'] : 'N/A';
        $playerPosition = isset($player['player']['position']) ? $player['player']['position'] : 'N/A';
        $rating= isset($player['statistics']['rating']) ? $player['statistics']['rating'] : 'N/A';
        $id_p = $player['player']['id'];
        $playerimg= getPlayerImg($id_p);
        $clubnam = isset($player['team']['name']) ? $player['team']['name'] : 'N/A';
        $clubid = isset($player['team']['id']) ? $player['team']['id'] : 'N/A';
        $clublogo = getClubLogo($clubid);
        $apps = isset($player['statistics']['appearances']) ? $player['statistics']['appearances'] : 'N/A';
        


       $playerData[] = [
          'id' => $id_p,
          'playerName' => $playerName,
          'clubid' => $clubid,
          'rating' => $rating,
          'playerPosition' => $playerPosition,
          'apps' => $apps,
          'clubnam' => $clubnam,
          'clublogo' => $clublogo,
          'playerimg' => $playerimg
        ];

        $count++;
      }
    }

    
    if (isset($responseData['topPlayers']['goals'])) {
      $playersgol = $responseData['topPlayers']['goals'];
      $playerDatagol = [];
      

      $count = 0;
      foreach ($playersgol as $playergol) {
        if ($count > 6) break;
        $playerNamegol = isset($playergol['player']['name']) ? $playergol['player']['name'] : 'N/A';
        $playerPositiongol = isset($playergol['player']['position']) ? $playergol['player']['position'] : 'N/A';
        $goals= isset($playergol['statistics']['goals']) ? $playergol['statistics']['goals'] : 'N/A';
        $id_pgol = $playergol['player']['id'];
        $playerimggol= getPlayerImg($id_pgol);
        $clubnamgol = isset($playergol['team']['name']) ? $playergol['team']['name'] : 'N/A';
        $clubidgol = isset($playergol['team']['id']) ? $playergol['team']['id'] : 'N/A';
        $clublogogol = getClubLogo($clubidgol);
        $appsgol = isset($playergol['statistics']['appearances']) ? $playergol['statistics']['appearances'] : 'N/A';


       $playerDatagol[] = [
          'idgol' => $id_pgol,
          'playerNamegol' => $playerNamegol,
          'clubidgol' => $clubidgol,
          'goals' => $goals,
          'playerPositiongol' => $playerPositiongol,
          'appsgol' => $appsgol,
          'clubnamgol' => $clubnamgol,
          'clublogogol' => $clublogogol,
          'playerimggol' => $playerimggol
        ];

        $count++;
      }
    }



    // TOP PLAYERS LA LIGA
      $responseDatala = getCachedApi(
        "topplayersLa_{8}", 
        "https://sofascore.p.rapidapi.com/tournaments/get-top-players?tournamentId=8&seasonId=77559", 
        86400
      );
    

    if ($responseDatala && isset($responseDatala['topPlayers']['rating'])) {
      $plerla = $responseDatala['topPlayers']['rating'];
      $playerDatala = [];
      

      $la_logo = getCompLogo(8);

      $count = 0;
      foreach ($plerla as $plla) {
        if ($count > 6) break;
        $playerNamela = isset($plla['player']['name']) ? $plla['player']['name'] : 'N/A';
        $playerPositionla = isset($plla['player']['position']) ? $plla['player']['position'] : 'N/A';
        $ratingla = isset($plla['statistics']['rating']) ? $plla['statistics']['rating'] : 'N/A';
        $id_pla = $plla['player']['id'];
        $playerimgla = getPlayerImg($id_pla);
        $clubnamla = isset($plla['team']['name']) ? $plla['team']['name'] : 'N/A';
        $clubidla = isset($plla['team']['id']) ? $plla['team']['id'] : 'N/A';
        $clublogola = getClubLogo($clubidla);
        $appsla = isset($plla['statistics']['appearances']) ? $plla['statistics']['appearances'] : 'N/A';
        


       $playerDatala[] = [
          'idla' => $id_pla,
          'playerNamela' => $playerNamela,
          'clubidla' => $clubidla,
          'ratingla' => $ratingla,
          'playerPositionla' => $playerPositionla,
          'appsla' => $appsla,
          'clubnamla' => $clubnamla,
          'clublogola' => $clublogola,
          'playerimgla' => $playerimgla
        ];

        $count++;
      }
    }

    
    if (isset($responseDatala['topPlayers']['goals'])) {
      $plergolla = $responseDatala['topPlayers']['goals'];
      $playerDatagolla = [];
      

      $count = 0;
      foreach ($plergolla as $plgolla) {
        if ($count > 6) break;
        $playerNamegolla = isset($plgolla['player']['name']) ? $plgolla['player']['name'] : 'N/A';
        $playerPositiongolla = isset($plgolla['player']['position']) ? $plgolla['player']['position'] : 'N/A';
        $goalsla= isset($plgolla['statistics']['goals']) ? $plgolla['statistics']['goals'] : 'N/A';
        $id_pgolla = $plgolla['player']['id'];
        $playerimggolla= getPlayerImg($id_pgolla);
        $clubnamgolla = isset($plgolla['team']['name']) ? $plgolla['team']['name'] : 'N/A';
        $clubidgolla = isset($plgolla['team']['id']) ? $plgolla['team']['id'] : 'N/A';
        $clublogogolla = getClubLogo($clubidgolla);
        $appsgolla = isset($plgolla['statistics']['appearances']) ? $plgolla['statistics']['appearances'] : 'N/A';


       $playerDatagolla[] = [
          'idgolla' => $id_pgolla,
          'playerNamegolla' => $playerNamegolla,
          'clubidgolla' => $clubidgolla,
          'goalsla' => $goalsla,
          'playerPositiongolla' => $playerPositiongolla,
          'appsgolla' => $appsgolla,
          'clubnamgolla' => $clubnamgolla,
          'clublogogolla' => $clublogogolla,
          'playerimggolla' => $playerimggolla
        ];

        $count++;
      }

  }

?>

<title>Home | ScoutBoard</title>
<?php include "header.php"; ?>


<div class="tables-row">
  <!-- Top by Rating -->
  <div class="table-box">
    <h3>Top Ratings Premier League</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Player</th>
          <th>Club</th>
          <th>Position</th>
          <th>Apps</th>
          <th>Rating</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($playerData as $p): ?>
          <tr>
            <td>
              <img src="<?= $p['playerimg'] ?>"
              class="player-photo">
              <a href="player.php?id=<?= $p['id'] ?>" class="player-link">
                <?= $p['playerName'] ?>
              </a>
            </td>
            <td>
              <img src="<?= $p['clublogo'] ?>"
              class="club-logo">
              <a href="club.php?id=<?= $p['clubid'] ?>" class="player-link">
                <?= $p['clubnam'] ?>
              </a>
            </td>
            <td><?=$p['playerPosition']?></td>
            <td><?=$p['apps']?></td>
            <td><span class="rating-badge"><?= number_format((float)$p['rating'], 2) ?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Logo -->
  <div class="league-logo">
    <img src="<?= $pre_logo ?>" alt="League Logo">
  </div>

  <!-- Top by Goals -->
  <div class="table-box">
    <h3>Top Scorers Premier League</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Player</th>
          <th>Club</th>
          <th>Position</th>
          <th>Apps</th>
          <th>Goals</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($playerDatagol as $p): ?>
          <tr>
            <td>
              <img src="<?= $p['playerimggol'] ?>"
              class="player-photo">
              <a href="player.php?id=<?= $p['idgol'] ?>" class="player-link">
                <?= $p['playerNamegol'] ?>
              </a>
            </td>
            <td>
              <img src="<?= $p['clublogogol'] ?>"
              class="club-logo">
              <a href="club.php?id=<?= $p['clubidgol'] ?>" class="player-link">
                <?= $p['clubnamgol'] ?>
              </a>
            </td>
            <td><?=$p['playerPositiongol']?></td>
            <td><?=$p['appsgol']?></td>
            <td><?=$p['goals']?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>



<div class="tables-row">
  <!-- Top by Rating -->
  <div class="table-box">
    <h3>Top Ratings La Liga</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Player</th>
          <th>Club</th>
          <th>Position</th>
          <th>Apps</th>
          <th>Rating</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($playerDatala as $p): ?>
          <tr>
            <td>
              <img src="<?= $p['playerimgla'] ?>"
              class="player-photo">
              <a href="player.php?id=<?= $p['idla'] ?>" class="player-link">
                <?= $p['playerNamela'] ?>
              </a>
            </td>
            <td>
              <img src="<?= $p['clublogola'] ?>"
              class="club-logo">
              <a href="club.php?id=<?= $p['clubidla'] ?>" class="player-link">
                <?= $p['clubnamla'] ?>
              </a>
            </td>
            <td><?=$p['playerPositionla']?></td>
            <td><?=$p['appsla']?></td>
            <td><span class="rating-badge"><?= number_format((float)$p['ratingla'], 2) ?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Logo -->
  <div class="league-logo">
    <img src="<?= $la_logo ?>" alt="League Logo">
  </div>

  <!-- Top by Goals -->
  <div class="table-box">
    <h3>Top Scorers La Liga</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Player</th>
          <th>Club</th>
          <th>Position</th>
          <th>Apps</th>
          <th>Goals</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($playerDatagolla as $p): ?>
          <tr>
            <td>
              <img src="<?= $p['playerimggolla'] ?>"
              class="player-photo">
              <a href="player.php?id=<?= $p['idgolla'] ?>" class="player-link">
                <?= $p['playerNamegolla'] ?>
              </a>
            </td>
            <td>
              <img src="<?= $p['clublogogolla'] ?>"
              class="club-logo">
              <a href="club.php?id=<?= $p['clubidgolla'] ?>" class="player-link">
                <?= $p['clubnamgolla'] ?>
              </a>
            </td>
            <td><?=$p['playerPositiongolla']?></td>
            <td><?=$p['appsgolla']?></td>
            <td><?=$p['goalsla']?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<footer class="footer">ScoutBoard • <?= date('Y') ?></footer>
