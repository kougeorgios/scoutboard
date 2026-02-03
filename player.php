<?php

  require_once "config.php";

  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = urlencode($_GET['id']);


    // PLAYER TRANSFERS

      $responseDatatr = getCachedApi(
        "playertransfers_{$id}", 
        "https://sofascore.p.rapidapi.com/players/get-transfer-history?playerId=$id", 
        604800
      );


      if ($responseDatatr && isset($responseDatatr['transferHistory'])) {
        $transfers = $responseDatatr['transferHistory'];

        foreach ($transfers as $transfer) {
          $oldClubName = isset($transfer['transferFrom']['name']) ? $transfer['transferFrom']['name'] : 'No team';
          $oldClubId = isset($transfer['transferFrom']['id']) ? $transfer['transferFrom']['id'] : 'N/A';
          $oldClubImage = getClubLogo($oldClubId);
          $newClubName = isset($transfer['transferTo']['name']) ? $transfer['transferTo']['name'] : 'No team';
          $newClubId = isset ($transfer['transferTo']['id']) ? $transfer['transferTo']['id'] : 'N/A';
          $newClubImage = getClubLogo($newClubId);
          $type = $transfer['type'] ?? 'Unknown';
          $date = isset($transfer['transferDateTimestamp']) ? $transfer['transferDateTimestamp'] : 'N/A';
          $transferfee = $transfer['transferFeeDescription'];


          $transferData[] = [
            'oldClubId' => $oldClubId,
            'newClubId' => $newClubId,
            'oldClubName' => $oldClubName,
            'oldClubImage' => $oldClubImage,
            'newClubName' => $newClubName,
            'newClubImage' => $newClubImage,
            'date' => $date,
            'type' => $type,
            'transferfee' => $transferfee
          ];
        }
      }


    // PLAYER STATS 

      $responseDatast = getCachedApi(
        "playerstats_{$id}", 
        "https://sofascore.p.rapidapi.com/players/get-all-statistics?playerId=$id", 
        259200
      );      
      
      if ($responseDatast && isset($responseDatast['seasons'])) {
        $stats = $responseDatast['seasons'];

        foreach ($stats as $stat) {
          $season = isset($stat['year']) ? $stat['year'] : 'N/A';
          $clubId = $stat['team']['id'] ?? 'N/A';
          $clubImage = getClubLogo($clubId);
          $club = isset($stat['team']['name']) ? $stat['team']['name'] : 'N/A';
          $comp = $stat['uniqueTournament']['name'] ?? 'N/A';
          $compId = $stat['uniqueTournament']['id'] ?? 'N/A';
          $assists = $stat['statistics']['assists'] ?? 'N/A';
          $goals = $stat['statistics']['goals'] ?? 'N/A';
          $rating = isset($stat['statistics']['rating']) ? number_format((float)$stat['statistics']['rating'], 2) : '-';
          $apps = $stat['statistics']['appearances'] ?? 'N/A';


          $statsData[] = [
            'clubId' => $clubId,
            'season' => $season,
            'club' => $club,
            'clubImage' => $clubImage,
            'comp' => $comp,
            'compId' => $compId,
            'assists' => $assists,
            'goals' => $goals,
            'rating' => $rating,
            'apps' => $apps
          ];
        }
      }


    // PLAYER INFO

      $responseDataInfo = getCachedApi(
        "playerinfos_{$id}", 
        "https://sofascore.p.rapidapi.com/players/detail?playerId=$id", 
        604800
      );

      if ($responseDataInfo && isset($responseDataInfo['player'])) {
        $infos = $responseDataInfo['player'];

        $name = isset($infos['name']) ? $infos['name'] : 'N/A';
        $image = getPlayerImg($id);
        $dateBirth = isset($infos['dateOfBirth']) ? $infos['dateOfBirth'] : 'N/A';
        $nationality = isset($infos['country']['name']) ? $infos['country']['name'] : 'N/A';
        $number = isset($infos['jerseyNumber']) ? $infos['jerseyNumber'] : '';
        $club = isset($infos['team']['name']) ? $infos['team']['name'] : 'N/A';
        $clubID = isset($infos['team']['id']) ? $infos['team']['id'] : 'N/A';
        $clublogo = getClubLogo($clubID);
        $competition = isset($infos['team']['primaryUniqueTournament']['name']) ? $infos['team']['primaryUniqueTournament']['name'] : 'N/A';
        $competitionId = isset($infos['team']['primaryUniqueTournament']['id']) ? $infos['team']['primaryUniqueTournament']['id'] : 'N/A';
        $country_comp = isset($infos['team']['country']['name']) ? $infos['team']['country']['name'] : 'N/A';
        $position = isset($infos['positionsDetailed']['0']) ? $infos['positionsDetailed']['0'] : 'Unknown';
        $height = isset($infos['height']) ? $infos['height'] : 'N/A';
        $foot = isset($infos['preferredFoot']) ? $infos['preferredFoot'] : 'Both';
        $marketValue = isset($infos['proposedMarketValueRaw']['value']) ? $infos['proposedMarketValueRaw']['value'] : 0;
      }
    }


  else {
    echo "No Player...";
  }


?>

<title><?php echo isset($name) ? $name : "Player Profile"; ?> | ScoutBoard</title>
<?php include "header.php"; ?>

<main class="container">
  <div class="breadcrumbs">Player / Profile</div>
  
  <div class="profile">
    <aside class="avatar">
      <img src="images/player_<?= $id ?>.png" 
           alt="<?= htmlspecialchars($name) ?>" 
           class="avatar-img">
    </aside>


    <section class="panel player-info">
      <div class="info-left">
        <h1 style="margin-top:0">
          #<?= $number ?> <?= htmlspecialchars($name) ?> 
        </h1>
        <p>Nationality:<strong> <?= $nationality ?></strong></p>
        <p>Position:<strong> <?= $position ?></strong></p>
        <p>Date of Birth:<strong> <?= date("d/m/Y", strtotime($dateBirth)) ?></strong></p>
        <p>Height:<strong> <?= $height ?> cm</strong></p>
        <p>Foot:<strong> <?= $foot ?></strong></p>
        <p>Market Value:<strong> <?= number_format($marketValue, 0, ',', '.') ?>€</strong></p>
      </div>

      <div class="info-right">
        <img src="images/logo_<?= $clubID ?>.png" alt="<?= $club ?>" class="club-logo-big">
        <h3>
          <a href="club.php?id=<?= $clubID ?>" class="white-link">
            <?= htmlspecialchars($club) ?>
          </a>
        </h3>
        <h3>
          <a href="competition.php?id=<?= $competitionid ?>" class="white-link">
            <?= htmlspecialchars($competition) ?>
          </a>
        </h3>
        <p><?= $country_comp ?></p>
      </div>
    </section>

  </div> 

  <!-- Tabs -->
  <div class="tabs">
    <button class="tab-button active" onclick="openTab('stats')">Statistics</button>
    <button class="tab-button" onclick="openTab('transfers')">Transfers</button>
  </div>

  <div class="tab-content" id="stats">
    <h2>Statistics</h2>
    <?php if (!empty($statsData)): ?>
    <div class="table-wrapper">
    <table class="stats-table">
      <thead>
        <tr>
          <th>Season</th>
          <th>Club</th>
          <th>Competition</th>
          <th class="center">Apps</th>
          <th class="center">Rating</th>
          <th class="center">Goals</th>
          <th class="center">Assists</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($statsData as $s): ?>
          <?php if ($s['apps'] >= 5): ?>
          <tr>
            <td><?= htmlspecialchars($s['season']) ?></td>
            <td>
             <img src="images/logo_<?= $s['clubId'] ?>.png"  class="club-logo">
              <a href="club.php?id=<?= $s['clubId'] ?>" class="club-link">
               <?= htmlspecialchars($s['club']) ?>
              </a>
            </td>
            <td>
              <a href="competition.php?id=<?= $s['compId'] ?>" class="club-link">
               <?= $s['comp'] ?>
              </a>
            </td>
            <td class="center"><?= $s['apps'] ?></td>
            <td class="center"><span class="rating-badge"><?= number_format((float)$s['rating'], 2) ?></span></td>
            <td class="center"><?= $s['goals'] ?></td>
            <td class="center"><?= $s['assists'] ?></td>
          </tr>
          <?php endif; ?>
        <?php endforeach; ?>
      </tbody>
          <?php else: ?>
         <h3>No data for this player</h3>
      <?php endif; ?>
    </table>
  </div>
  </div>

  <div class="tab-content hidden" id="transfers">
    <h2>Transfers</h2>
    <?php if (!empty($transferData)): ?>
    <div class="table-wrapper">
    <table class="stats-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>From</th>
          <th class="center">Fee</th>
          <th>To</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($transferData as $t): ?>
        <?php 
          if ($t['oldClubName'] !== 'No team' || $t['newClubName'] !== 'No team') : 
        ?>
        <tr>
          <td><?= date("d M Y", (int)$t['date']) ?></td>
          <td>
            <?php if ($t['oldClubName'] !== 'No team') : ?>
            <img src="images/logo_<?= $t['oldClubId'] ?>.png"  class="club-logo">
            <a href="club.php?id=<?= $t['oldClubId'] ?>" class="club-link">
              <?= htmlspecialchars($t['oldClubName']) ?>
            </a>
            <?php else : ?>
              <span><strong><?= htmlspecialchars($t['oldClubName']) ?></strong></span>
            <?php endif; ?>

          </td>
          <td class="center">
            <?php 
              if ($t['type'] == 1) {
                echo "Loan";
              } elseif ($t['type'] == 2) {
                echo "End Loan";
              } elseif ($t['type'] == 3) {
                echo $t['transferfee'];
              } else {
                echo "-";
              }
            ?>
          </td>
          <td>
            <?php if ($t['newClubName'] !== 'No team') : ?>
            <img src="images/logo_<?= $t['newClubId'] ?>.png"  class="club-logo">
            <a href="club.php?id=<?= $t['newClubId'] ?>" class="club-link">
            <?= htmlspecialchars($t['newClubName']) ?>
            </a>
            <?php else : ?>
              <span><strong>  <?= htmlspecialchars($t['newClubName']) ?></strong></span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endif; ?>
        <?php endforeach; ?>
      </tbody>
          <?php else: ?>
         <h3>No data for this player</h3>
      <?php endif; ?>
    </table>
  </div>
  </div>
</main>


<footer class="footer">ScoutBoard • Player Profile</footer>

<script src="scripts.js"></script>

</body>
</html>
