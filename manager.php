<?php


  require_once "config.php";

  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = urlencode($_GET['id']);


      $responseDataInfo = getCachedApi(
        "coachinfos_{$id}", 
        "https://sofascore.p.rapidapi.com/managers/detail?managerId=$id", 
        86400
      );

      if ($responseDataInfo && isset($responseDataInfo['manager'])) {
        $infos = $responseDataInfo['manager'];

        $name = $infos['name'] ?? 'N/A';
        $coachimg = getCoachImg($id);
        $dateBirth = $infos['dateOfBirthTimestamp'] ?? 'Unknown';
        $country = $infos['country']['name'] ?? 'N/A';
        $preferdformation = $infos['preferredFormation'] ?? 'Unknown';
        $clubname = $infos['team']['name'] ?? 'Unemployed';
        $clubid = $infos['team']['id'] ?? 'N/A';
        $clublogo = getClubLogo($clubid);
        $compname = $infos['team']['primaryUniqueTournament']['name'] ?? '';
        $compid = $infos['team']['primaryUniqueTournament']['id'] ?? '';
        $matches = $infos['performance']['total'] ?? 'No matches';
        $wins = $infos['performance']['wins'] ?? '';
      }


      $responseData = getCachedApi(
        "coachcareer_{$id}", 
        "https://sofascore.p.rapidapi.com/managers/get-career-history?managerId=$id", 
        86400
      );

      // CLUBS
      if ($responseData && isset($responseData['careerHistory'])) {
        $clubss = $responseData['careerHistory'];
        $clubssData = [];


        foreach ($clubss as $cl) {
            $clnam = $cl['team']['name'] ?? 'N/A';
            $clid = $cl['team']['id'] ?? 'N/A';
            $cllogo = getClubLogo($clid);
            $clmatches = $cl['performance']['total'] ?? 'No data';
            $clwins = $cl['performance']['wins'] ?? '';
            $cldraws= $cl['performance']['draws'] ?? '';
            $cllosses = $cl['performance']['losses'] ?? '';
            $starttime = $cl['startTimestamp'] ?? 'N/A';
            $endtime = $cl['endTimestamp'] ?? 'Now';

            $clubssData[] = [
                'clnam' => $clnam,
                'clid' => $clid,
                'cllogo' => $cllogo,
                'clmatches' => $clmatches,
                'clwins' => $clwins,
                'cldraws' => $cldraws,
                'cllosses' => $cllosses,
                'starttime' => $starttime,
                'endtime' => $endtime
            ];

        }
      }


          // NEXT FIXTURES
      $responseDataFix = getCachedApi(
        "nextfixturesManager_{$id}", 
        "https://sofascore.p.rapidapi.com/managers/get-next-matches?managerId=$id&pageIndex=0", 
        86400
      );     

      if ($responseDataFix && isset($responseDataFix['events'])) {
      $fix = $responseDataFix['events'];
      $fixData =[];

      $count = 0;
      foreach ($fix as $fixn) {

        if ($count > 6) break;

        $matchdate = $fixn['startTimestamp'];
        $hometeam = $fixn['homeTeam']['name'];
        $hometeamID = $fixn['homeTeam']['id'];
        $awayteam = $fixn['awayTeam']['name'];
        $awayteamID = $fixn['awayTeam']['id'];
        $compn = $fixn['tournament']['uniqueTournament']['name'];
        $compnid = $fixn['tournament']['uniqueTournament']['id'];


        $fixData[] = [
            'matchdate' => $matchdate,
            'hometeam' => $hometeam,
            'hometeamID' => $hometeamID,
            'awayteam' => $awayteam,
            'awayteamID' => $awayteamID,
            'compn' => $compn,
            'compnid' => $compnid
          ];

          $count++;
      }
    }


      // PREVIOUS FIXTURES
      $responseDataFixl = getCachedApi(
        "prevfixturesManager_{$id}", 
        "https://sofascore.p.rapidapi.com/managers/get-last-matches?managerId=$id&pageIndex=0", 
        86400
      );

      if ($responseDataFixl && isset($responseDataFixl['events'])) {
      $fixl = $responseDataFixl['events'];
      $fixDatalast =[];

      $total = count($fixl);
      $limit = min(7, $total);


      for ($i = $total - $limit; $i < $total; $i++) {
        $fixt = $fixl[$i];

        $matchdatel = $fixt['startTimestamp'];
        $hometeaml = $fixt['homeTeam']['name'];
        $hometeamlID = $fixt['homeTeam']['id'];
        $awayteaml = $fixt['awayTeam']['name'];
        $awayteamlID = $fixt ['awayTeam']['id'];
        $compl = $fixt['tournament']['uniqueTournament']['name'];
        $complid = $fixt['tournament']['uniqueTournament']['id'];
        $homescore = $fixt['homeScore']['current'];
        $awayscore = $fixt['awayScore']['current'];


        $fixDatalast[] = [
            'matchdatel' => $matchdatel,
            'hometeaml' => $hometeaml,
            'hometeamlID' => $hometeamlID,
            'awayteaml' => $awayteaml,
            'awayteamlID' => $awayteamlID,
            'compl' => $compl,
            'complid' => $complid,
            'homescore' => $homescore,
            'awayscore' => $awayscore
          ];

      } 
    }

  }


    else {
    echo "There is not Manager...";
  }

?>

<title><?php echo isset($name) ? $name : "Manager Profile"; ?> | ScoutBoard</title>
<?php include "header.php"; ?>

<main class="container">
  <div class="breadcrumbs">Manager / Profile</div>

  <div class="profile">
    <aside class="avatar">
      <img src="<?= $coachimg ?>" 
           alt="<?= htmlspecialchars($name) ?>" 
           class="avatar-img">
    </aside>


    <section class="panel player-info">
      <div class="info-left">
        <h1 style="margin-top:0">
          <?= htmlspecialchars($name) ?> 
        </h1>
        <p>Country: <strong><?= $country ?></strong></p>
        <p>Date of Birth: <strong><?= date("d/m/Y", (int)($dateBirth)) ?></strong></p>
        <p>Preferd Formation: <strong><?= $preferdformation ?></strong></p>
        <p>Matches: <strong><?= $matches ?></strong></p>
        <p>Wins: <strong><?= $wins ?></strong></p>
      </div>

      <div class="info-right">
        <img src="<?= $clublogo ?>"  class="club-logo-big">
        <h3>
          <a href="club.php?id=<?= $clubid ?>" class="white-link">
          <?= htmlspecialchars($clubname) ?>
          </a>
        </h3>
        <h3>
          <a href="competition.php?id=<?= $compid ?>" class="white-link">
          <?= htmlspecialchars($compname) ?>
          </a>
        </h3>
      </div>
    </section>
  </div> 

  <div class="tab-content active" id="Coaching Career">
    <?php if (!empty($clubssData)): ?>
      <h2>Coaching Career</h2>
      <div class="table-wrapper">
      <table class="stats-table">
      <thead>
       <tr>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Club</th>
        <th class="center">Matches</th>
        <th class="center">Wins</th>
        <th class="center">Draws</th>
        <th class="center">Losses</th>
       </tr>
      </thead>
    <tbody>
      <?php foreach ($clubssData as $c): ?>
        <tr>
          <td><?= date("d M Y", (int)$c['starttime']) ?></td>
          <td><?= date("d M Y", (int)$c['endtime']) ?></td>
          <td>
            <img src="<?= $c['cllogo'] ?>" class="club-logo">
             <a href="club.php?id=<?= $c['clid'] ?>" class="club-link">
               <?= $c['clnam'] ?>
             </a>
          </td>
          <td class="center"><?= $c['clmatches'] ?></td>
          <td class="center"><?= $c['clwins'] ?></td>
          <td class="center"><?= $c['cldraws'] ?></td>
          <td class="center"><?= $c['cllosses'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>
 </div>


 <?php else: ?>

  <div class="stats-row">
  <!-- Previous Matches -->
  <div class="stats-box">
    <?php if (!empty($fixDatalast)): ?>
    <h3>Previous Matches</h3>
    <div class="table-wrapper">
    <table class="stats-table">
      <thead>
        <tr>
          <th>Match Date</th>
          <th>Competition</th>
          <th>Home Team</th>
          <th>Away Team</th>
          <th>Score</th>
        </tr>
      </thead>
      <tbody>
        <?php $fixDatalast = array_reverse($fixDatalast); ?>
        <?php foreach ($fixDatalast as $m): ?>
          <tr>
            <td><?= date("d/m/Y", (int)$m['matchdatel']) ?></td>
            <td>
              <a href="competition.php?id=<?= $m['complid'] ?>" class="club-link">
               <?= htmlspecialchars($m['compl']) ?>
              </a>
            </td>
            <td>
              <a href="club.php?id=<?= $m['hometeamlID'] ?>" class="club-link">
               <?= htmlspecialchars($m['hometeaml']) ?>
              </a>
            </td>
            <td>
              <a href="club.php?id=<?= $m['awayteamlID'] ?>" class="club-link">
               <?= htmlspecialchars($m['awayteaml']) ?>
              </a>
            </td>
            <td><?= htmlspecialchars($m['homescore']) ?> - <?= htmlspecialchars($m['awayscore']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <?php else: ?>
         <h3>No data for last matches</h3>
      <?php endif; ?>
    </table>
  </div>
  </div>

  <!-- Upcoming Matches -->
  <div class="stats-box">
    
    <h3>Upcoming Matches</h3>
    <div class="table-wrapper">
    <table class="stats-table">
     <?php if (!empty($fixData)): ?>
      <thead>
        <tr>
          <th>Match Date</th>
          <th>Competition</th>
          <th>Home Team</th>
          <th>Away Team</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($fixData as $m): ?>
          <tr>
            <td><?= date("d/m/Y", (int)$m['matchdate']) ?></td>
            <td>
              <a href="competition.php?id=<?= $m['compnid'] ?>" class="club-link">
               <?= htmlspecialchars($m['compn']) ?>
              </a>
            </td>
            <td>
              <a href="club.php?id=<?= $m['hometeamID'] ?>" class="club-link">
               <?= htmlspecialchars($m['hometeam']) ?>
              </a>
            </td>
            <td>
              <a href="club.php?id=<?= $m['awayteamID'] ?>" class="club-link">
               <?= htmlspecialchars($m['awayteam']) ?>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <?php else: ?>
         <h3>No data for upcoming matches</h3>
      <?php endif; ?>
    </table>
  </div>
  </div>
  </div>
 <?php endif; ?>

  </div>
  </main>

  <footer class="footer">ScoutBoard • Manager Profile</footer>

  </body>
</html>