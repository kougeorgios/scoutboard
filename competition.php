<?php

  require_once "config.php";

  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = urlencode($_GET['id']);

    function ordinal($number) {
      if (empty($number)) {
        return "";
      }
      $suffix = "th";
      if (!in_array(($number % 100), [11, 12, 13])) {
        switch ($number % 10) {
            case 1:  $suffix = "st"; break;
            case 2:  $suffix = "nd"; break;
            case 3:  $suffix = "rd"; break;
        }
    }
    return $number . $suffix;
    }


    // COMPETITION INFO
      $responseDataInfo = getCachedApi(
        "compinfo_{$id}", 
        "https://sofascore.p.rapidapi.com/tournaments/detail?tournamentId=$id", 
        604800
      );


      if ($responseDataInfo && isset($responseDataInfo['uniqueTournament'])) {
        $infos = $responseDataInfo['uniqueTournament'];

        $name = $infos['name'] ?? 'N/A';
        $logo = getCompLogo($id);
        $tiernum = $infos['tier'] ?? '';
        $tier = ordinal($tiernum);
        $country = $infos['category']['country']['name'] ?? 'International';
        $titleholder = $infos['titleHolder']['name'] ?? '';
        $titleholderId = $infos['titleHolder']['id'] ?? 'N/A';
        $titleholderImg = getClubLogo($titleholderId);
        $mosttitles = $infos['mostTitlesTeams']['0']['name'] ?? '';
        $mosttitlesId = $infos['mostTitlesTeams']['0']['id'] ?? '';
        $mosttitlesnum = $infos['mostTitles'] ?? 'No';
        $starttime = $infos['startDateTimestamp'] ?? 'N/A';
        $endtime = $infos['endDateTimestamp'] ?? 'N/A';
        $lowerdivision = $infos['lowerDivisions']['0']['name'] ?? '';
        $lowerdivisionId = $infos['lowerDivisions']['0']['id'] ?? '';
        $upperdivision = isset($infos['upperDivisions']['0']['name']) ? $infos['upperDivisions']['0']['name']: '';
        $upperdivisionId = isset($infos['upperDivisions']['0']['id']) ? $infos['upperDivisions']['0']['id']: '';   
      }


    
    function getLastSeason($id) {

      $data= getCachedApi(
        "complastiseason_{$id}", 
        "https://sofascore.p.rapidapi.com/tournaments/get-seasons?tournamentId=$id", 
        2628000
      );


      if ($data && isset($data['seasons']['0']['id'])) {
        return $data['seasons']['0']['id'];
      }

      return null;
    }

      $lastseasonId = getLastSeason($id);

      // STANDINGS 

      $responseStandings = getCachedApi(
        "standings_{$id}_{$lastseasonId}", 
        "https://sofascore.p.rapidapi.com/tournaments/get-standings?tournamentId=$id&seasonId=$lastseasonId&type=total", 
        86400
      );


      if ($responseStandings && isset($responseStandings['standings']['0']['rows'])) {
        $standings = $responseStandings['standings']['0']['rows'];
        $standingsData = [];

        foreach ($standings as $st) {

          $club = $st['team']['name'] ?? 'N/A';
          $clubId = $st['team']['id'] ?? 'N/A';
          $clubImg = getClubLogo($clubId);
          $position = $st['position'] ?? 'N/A';
          $matches = $st['matches'] ?? 'N/A';
          $wins = $st['wins'] ?? 'N/A';
          $losses = $st['losses'] ?? 'N/A';
          $draws = $st['draws'] ?? 'N/A';
          $points = $st['points'] ?? 'N/A';
          $goaldif = $st['scoreDiffFormatted'] ?? 'N/A';


          $standingsData[] = [
            'club' => $club,
            'clubId' => $clubId,
            'position' => $position,
            'matches' => $matches,
            'wins' => $wins,
            'losses' => $losses,
            'draws' => $draws,
            'points' => $points,
            'clubImg' => $clubImg,
            'goaldif' => $goaldif
          ];
        }
      }



    // TOP PLAYERS

    $topplayersData = getCachedApi(
        "compTopPlayers_{$id}_{$lastseasonId}", 
        "https://sofascore.p.rapidapi.com/tournaments/get-top-players?tournamentId=$id&seasonId=$lastseasonId", 
        86400
    );


    if ($topplayersData && isset($topplayersData['topPlayers']['rating'])) {
      $players = $topplayersData['topPlayers']['rating'];
      $playerData = [];
      

      $count = 0;
      foreach ($players as $player) {
        if ($count > 9) break;
        $playerName = isset($player['player']['name']) ? $player['player']['name'] : 'N/A';
        $playerPosition = isset($player['player']['position']) ? $player['player']['position'] : 'N/A';
        $rating= isset($player['statistics']['rating']) ? $player['statistics']['rating'] : 'N/A';
        $playerid = $player['player']['id'];
        $playerimg= getPlayerImg($playerid);
        $clubnam = isset($player['team']['name']) ? $player['team']['name'] : 'N/A';
        $clubid = isset($player['team']['id']) ? $player['team']['id'] : 'N/A';
        $clublogo = getClubLogo($clubid);
        $apps = isset($player['statistics']['appearances']) ? $player['statistics']['appearances'] : 'N/A';
        


       $playerData[] = [
          'playerid' => $playerid,
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

    
    if (isset($topplayersData['topPlayers']['goals'])) {
      $playersgol = $topplayersData['topPlayers']['goals'];
      $playerDatagol = [];
      

      $count = 0;
      foreach ($playersgol as $playergol) {
        if ($count > 9) break;
        $playerNamegol = isset($playergol['player']['name']) ? $playergol['player']['name'] : 'N/A';
        $playerPositiongol = isset($playergol['player']['position']) ? $playergol['player']['position'] : 'N/A';
        $goals= isset($playergol['statistics']['goals']) ? $playergol['statistics']['goals'] : 'N/A';
        $playeridgol = $playergol['player']['id'];
        $playerimggol= getPlayerImg($playeridgol);
        $clubnamgol = isset($playergol['team']['name']) ? $playergol['team']['name'] : 'N/A';
        $clubidgol = isset($playergol['team']['id']) ? $playergol['team']['id'] : 'N/A';
        $clublogogol = getClubLogo($clubidgol);
        $appsgol = isset($playergol['statistics']['appearances']) ? $playergol['statistics']['appearances'] : 'N/A';


       $playerDatagol[] = [
          'playeridgol' => $playeridgol,
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


  }

    else {
    echo "No competition...";
  }

?>

<title><?php echo isset($name) ? $name : "Competition Profile"; ?> | ScoutBoard</title>
<?php include "header.php"; ?>

<main class="container">
  <div class="breadcrumbs">Competition / Profile</div>

  <div class="profile">
    <aside class="avatar">
      <img src="<?= $logo ?>" 
           alt="<?= htmlspecialchars($name) ?>" 
           class="avatar-img">
    </aside>


    <section class="panel player-info">
      <div class="info-left">
        <h1 style="margin-top:0">
          <?= htmlspecialchars($name) ?> 
        </h1>
        <p>Country:<strong> <?= $country ?></strong></p>
        <?php if (!empty($tier)): ?>
         <p>Tier:<strong> <?= $tier ?> Tier </strong></p>
        <?php endif; ?>
        <p>Start Date:<strong> <?= date("d/m/Y", (int)($starttime)) ?></strong></p>
        <p>End Date:<strong> <?= date("d/m/Y", (int)($endtime)) ?></strong></p>
        <p>
          Most Titled Club:
          <strong>
          <a href="club.php?id=<?= $mosttitlesId ?>" class="club-link">
            <?= htmlspecialchars($mosttitles) ?>
          </a>, <?= $mosttitlesnum ?> Titles
          </strong>
        </p>
        <?php if (!empty($lowerdivision)): ?>
         <p>
          Lower Division:
          <strong>
            <a href="competition.php?id=<?= $lowerdivisionId ?>" class="club-link">
             <?= htmlspecialchars($lowerdivision) ?>
            </a>
            </strong>
         </p>
         <?php endif; ?>
        <?php if (!empty($upperdivision)): ?>
          <p>
            Upper Division:
            <strong>
              <a href="competition.php?id=<?= $upperdivisionId ?>" class="club-link">
                <?= htmlspecialchars($upperdivision) ?>
              </a>
            </strong>
          </p>
        <?php endif; ?>
      </div>

      <div class="info-right">
        <h3>Title Holder</h3>
        <img src="<?= $titleholderImg ?>"  class="club-logo-big">
        <h3>
          <a href="club.php?id=<?= $titleholderId ?>" class="white-link">
           <?= htmlspecialchars($titleholder) ?>
          </a>
        </h3>
      </div>
    </section>

  </div> 


    <!-- Tabs -->
  <div class="tabs">
    <button class="tab-button active" onclick="openTab('Standings')">Standing</button>
    <button class="tab-button" onclick="openTab('Top Players')">Top Players</button>
  </div>

  <div class="tab-content" id="Standings">
    <h2>Standing</h2>
    <div class="table-wrapper">
    <table class="stats-table">
      <thead>
        <tr>
          <th>Position</th>
          <th>Club</th>
          <th class="center">Matches</th>
          <th class="center">Wins</th>
          <th class="center">Losses</th>
          <th class="center">Draws</th>
          <th class="center">Goal Difference</th>
          <th class="center">Points</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($standingsData as $s): ?>
          <tr>
            <td>#<?= $s['position'] ?></td>
            <td>
             <img src="<?= $s['clubImg'] ?>" class="club-logo">
             <a href="club.php?id=<?= $s['clubId'] ?>" class="club-link">
               <?= $s['club'] ?>
             </a>
            </td>
            <td class="center"><?= $s['matches'] ?></td>
            <td class="center"><?= $s['wins'] ?></td>
            <td class="center"><?= $s['losses'] ?></td>
            <td class="center"><?= $s['draws'] ?></td>
            <td class="center"><?= $s['goaldif'] ?></td>
            <td class="center"><?= $s['points'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  </div>

  <div class="tab-content hidden" id="Top Players">
    <div class="stats-row">
    <div class="table-wrapper">
  <!-- TOP RATINGS -->
  <div class="stats-box">
    <h3>Top Ratings</h3>
    <table class="stats-table">
      <thead>
        <tr>
          <th>Player</th>
          <th>Club</th>
          <th class="center">Position</th>
          <th class="center">Apps</th>
          <th>Rating</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($playerData as $p): ?>
          <tr>
            <td>
              <img src="<?= $p['playerimg'] ?>"
              class="player-photo">
              <a href="player.php?id=<?= $p['playerid'] ?>" class="club-link">
                <?= $p['playerName'] ?>
              </a>
            </td>
            <td>
              <img src="<?= $p['clublogo'] ?>"
              class="club-logo">
              <a href="club.php?id=<?= $p['clubid'] ?>" class="club-link">
                <?= $p['clubnam'] ?>
              </a>
            </td>
            <td class="center"><?=$p['playerPosition']?></td>
            <td class="center"><?=$p['apps']?></td>
            <td><span class="rating-badge"><?= number_format((float)$p['rating'], 2) ?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>

  <!-- TOP SCORERS -->
  <div class="stats-box">
    <h3>Top Scorers</h3>
    <table class="stats-table">
      <thead>
        <tr>
          <th>Player</th>
          <th>Club</th>
          <th class="center">Position</th>
          <th class="center">Apps</th>
          <th class="center">Goals</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($playerDatagol as $p): ?>
          <tr>
            <td>
              <img src="<?= $p['playerimggol'] ?>"
              class="player-photo">
              <a href="player.php?id=<?= $p['playeridgol'] ?>" class="club-link">
                <?= $p['playerNamegol'] ?>
              </a>
            </td>
            <td>
              <img src="<?= $p['clublogogol'] ?>"
              class="club-logo">
              <a href="club.php?id=<?= $p['clubidgol'] ?>" class="club-link">
                <?= $p['clubnamgol'] ?>
              </a>
            </td>
            <td class="center"><?=$p['playerPositiongol']?></td>
            <td class="center"><?=$p['appsgol']?></td>
            <td class="center"><?=$p['goals']?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  </div>
</div>


  </div>
</main>

<footer class="footer">ScoutBoard • Competition Profile</footer>

<script src="scripts.js"></script>

</body>
</html>





