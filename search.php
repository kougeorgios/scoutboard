<?php
    require_once "config.php";

    include "header.php";

    if (isset($_GET['q']) && !empty($_GET['q'])) {
        $query = urlencode(trim($_GET['q']));


        $responseData = getCachedApi(
            "search_{$query}", 
            "https://sofascore.p.rapidapi.com/search?q=$query&type=all&page=0", 
            2
        );

        if ($responseData && isset($responseData['results'])) {
            $searchresults = $responseData['results'];
            $players = [];
            $teams = [];
            $leagues = [];
            $managers = [];
        
            foreach ($searchresults as $re) {

                $type = $re['type'] ?? '';

                $allowedTypes = ['player', 'team', 'uniqueTournament', 'manager'];
                if (!in_array($type, $allowedTypes)) continue;

                // Check for football
                $isFootball = false;
                if ($type === 'team' && ($re['entity']['sport']['id'] ?? 0) == 1) {
                    $isFootball = true;
                } elseif ($type === 'player' && ($re['entity']['team']['sport']['id'] ?? 0) == 1) {
                    $isFootball = true;
                } elseif ($type === 'uniqueTournament' && ($re['entity']['category']['sport']['id'] ?? 0) == 1) {
                    $isFootball = true;
                } elseif ($type === 'manager' && ($re['entity']['sport']['id'] ?? 0) == 1) {
                    $isFootball = true;
                }

                if (!$isFootball) continue;

                switch ($type) {
                    case 'player': $players[] = $re; break;
                    case 'team': $teams[] = $re; break;
                    case 'uniqueTournament': $leagues[] = $re; break;
                    case 'manager': $managers[] = $re; break;
                }
            }
        }
    }

?>


<div class="search-results">

<!-- players -->
  <?php if (!empty($players)): ?>
    <div class="search-table-box">
    <h3>Player Results</h3>
    <div class="table-wrapper">
    <table class="search-table">
      <thead>
        <tr>
          <th>Player</th>
          <th>Position</th>
          <th>Country</th>
          <th>Club</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($players as $p): 
          $id = $p['entity']['id'];
          $playerimg = getPlayerImg($id);
          $name = $p['entity']['name'] ?? '';
          $position = $p['entity']['position'] ?? '-';
          $club = $p['entity']['team']['name'] ?? 'No team';
          $clubId = $p['entity']['team']['id'] ?? '';
          $country = $p['entity']['country']['name'] ?? '';
        ?>
          <tr>
            <td><img src="<?= $playerimg ?>" class="club-logo">
            <a href="player.php?id=<?= $id ?>" class="club-link"><?= htmlspecialchars($name) ?></a></td>
            <td><?= $position ?></td>
            <td><?= htmlspecialchars($country) ?></td>
            <td>
              <?php if ($club !== 'No team') : ?>
                <a href="club.php?id=<?= $clubId ?>" class="club-link"><?= htmlspecialchars($club) ?></a>
              <?php else: ?> <span><strong><?= htmlspecialchars($club) ?></strong></span> <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
    </div>
  <?php endif; ?>



<!-- teams -->
  <?php if (!empty($teams)): ?>
    <div class="search-table-box">
    <h3>Clubs Results</h3>
    <table class="search-table">
      <thead>
        <tr>
          <th>Club</th>
          <th>Country</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($teams as $t):
          $id = $t['entity']['id'];
          $logo = getClubLogo($id);
          $name = $t['entity']['name'] ?? '';
          $country = $t['entity']['country']['name'] ?? 'International';
        ?>
          <tr>
            <td><img src="<?= $logo ?>" class="club-logo">
            <a href="club.php?id=<?= $id ?>" class="club-link"><?= htmlspecialchars($name) ?></a></td>
            <td><?= htmlspecialchars($country) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  <?php endif; ?>


  <!-- competitions -->
  <?php if (!empty($leagues)): ?>
    <div class="search-table-box">
    <h3>Competitions Results</h3>
    <table class="search-table">
      <thead>
        <tr>
          <th>Competition</th>
          <th>Country</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($leagues as $l):
          $id = $l['entity']['id'];
          $complogo = getCompLogo($id);
          $name = $l['entity']['name'] ?? '';
          $country = $l['entity']['category']['country']['name'] ?? 'International';
        ?>
          <tr>
            <td><img src="<?= $complogo ?>" class="club-logo">
            <a href="competition.php?id=<?= $id ?>" class="club-link"><?= htmlspecialchars($name) ?></a></td>
            <td><?= htmlspecialchars($country) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  <?php endif; ?>


  <!-- coaches -->
  <?php if (!empty($managers)): ?>
    <div class="search-table-box">
    <h3>Managers Results</h3>
    <div class="table-wrapper">
    <table class="search-table">
      <thead>
        <tr>
          <th>Coach</th>
          <th>Country</th>
          <th>Club</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($managers as $m):
          $id = $m['entity']['id'];
          $coachimg = getCoachImg($id);
          $name = $m['entity']['name'] ?? '';
          $club = $m['entity']['team']['name'] ?? 'No team';
          $clubId = $m['entity']['team']['id'] ?? '';
          $country = $m['entity']['country']['name'] ?? '-';
        ?>
          <tr>
            <td><img src="<?= $coachimg ?>" class="club-logo">
            <a href="manager.php?id=<?= $id ?>" class="club-link"><?= htmlspecialchars($name) ?></a></td>
            <td><?= htmlspecialchars($country) ?></td>
            <td>
              <?php if ($club !== 'No team') : ?>
                <a href="club.php?id=<?= $clubId ?>" class="club-link"><?= htmlspecialchars($club) ?></a>
              <?php else: ?> <span><strong><?= htmlspecialchars($club) ?></strong></span> <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
    </div>
  <?php endif; ?>


</div>


<footer class="footer">ScoutBoard • Search</footer>

