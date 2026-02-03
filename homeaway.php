<?php

require_once 'db_connect.php';

$sql = "SELECT 
    t.id AS team_id,
    t.name AS team_name,

    -- HOME
    SUM(CASE WHEN m.home_team = t.id AND m.home_score IS NOT NULL THEN 1 ELSE 0 END) AS home_played,
    SUM(CASE WHEN m.home_team = t.id AND m.home_score > m.away_score THEN 1 ELSE 0 END) AS home_wins,
    SUM(CASE WHEN m.home_team = t.id AND m.home_score = m.away_score THEN 1 ELSE 0 END) AS home_draws,
    SUM(CASE WHEN m.home_team = t.id AND m.home_score < m.away_score THEN 1 ELSE 0 END) AS home_losses,
    SUM(CASE WHEN m.home_team = t.id THEN m.home_score ELSE 0 END) AS home_score_for,
    SUM(CASE WHEN m.home_team = t.id THEN m.away_score ELSE 0 END) AS home_score_against,
    SUM(
        CASE 
            WHEN m.home_team = t.id AND m.home_score > m.away_score THEN 3
            WHEN m.home_team = t.id AND m.home_score = m.away_score THEN 1
            ELSE 0
        END
    ) AS home_points,

    -- AWAY
    SUM(CASE WHEN m.away_team = t.id AND m.away_score IS NOT NULL THEN 1 ELSE 0 END) AS away_played,
    SUM(CASE WHEN m.away_team = t.id AND m.away_score > m.home_score THEN 1 ELSE 0 END) AS away_wins,
    SUM(CASE WHEN m.away_team = t.id AND m.away_score = m.home_score THEN 1 ELSE 0 END) AS away_draws,
    SUM(CASE WHEN m.away_team = t.id AND m.away_score < m.home_score THEN 1 ELSE 0 END) AS away_losses,
    SUM(CASE WHEN m.away_team = t.id THEN m.away_score ELSE 0 END) AS away_score_for,
    SUM(CASE WHEN m.away_team = t.id THEN m.home_score ELSE 0 END) AS away_score_against,
    SUM(
        CASE 
            WHEN m.away_team = t.id AND m.away_score > m.home_score THEN 3
            WHEN m.away_team = t.id AND m.away_score = m.home_score THEN 1
            ELSE 0
        END
    ) AS away_points

FROM teams t
LEFT JOIN matches m ON (m.home_team = t.id OR m.away_team = t.id)
GROUP BY t.id, t.name";

$result = $conn->query($sql);

$homeTable = [];
$awayTable = [];

while ($r = $result->fetch_assoc()) {
    $homeTable[] = [
        "team_id" => $r["team_id"],
        "team_name" => $r["team_name"],
        "played" => $r["home_played"],
        "wins" => $r["home_wins"],
        "draws" => $r["home_draws"],
        "losses" => $r["home_losses"],
        "gf" => $r["home_score_for"],
        "ga" => $r["home_score_against"],
        "gd" => $r["home_score_for"] - $r["home_score_against"],
        "points" => $r["home_points"]
    ];

    $awayTable[] = [
        "team_id" => $r["team_id"],
        "team_name" => $r["team_name"],
        "played" => $r["away_played"],
        "wins" => $r["away_wins"],
        "draws" => $r["away_draws"],
        "losses" => $r["away_losses"],
        "gf" => $r["away_score_for"],
        "ga" => $r["away_score_against"],
        "gd" => $r["away_score_for"] - $r["away_score_against"],
        "points" => $r["away_points"]
    ];
}

usort($homeTable, fn($a, $b) => $b["points"] <=> $a["points"] ?: $b["gd"] <=> $a["gd"]);
usort($awayTable, fn($a, $b) => $b["points"] <=> $a["points"] ?: $b["gd"] <=> $a["gd"]);

$conn->close();
?>


<title>Home / Away Table | ScoutBoard</title>
<?php include "header.php"; ?>


<div class="analytics-container2">

    <div class="dual-tables">

        <!-- HOME TABLE -->
        <div class="table-half">
            <h3 class="analytics-title">Home Table</h3>

            <table class="analytics-table">
                <thead>
                <tr>
                    <th>Team</th>
                    <th>Matches</th>
                    <th style="text-align: center;">Wins</th>
                    <th style="text-align: center;">Draws</th>
                    <th style="text-align: center;">Losses</th>
                    <th style="text-align: center;">Goals</th>
                    <th style="text-align: center;">Points</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($homeTable as $t): ?>
                    <tr onclick="window.location='club.php?id=<?= $t['team_id'] ?>'">
                        <td><?= $t['team_name'] ?></td>
                        <td style="text-align: center;"><?= $t['played'] ?></td>
                        <td style="text-align: center;"><?= $t['wins'] ?></td>
                        <td style="text-align: center;"><?= $t['draws'] ?></td>
                        <td style="text-align: center;"><?= $t['losses'] ?></td>
                        <td style="text-align: center;"><?= $t['gf'] ?> - <?= $t['ga'] ?></td>
                        <td style="text-align: center;"><?= $t['points'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- AWAY TABLE -->
        <div class="table-half">
            <h3 class="analytics-title">Away Table</h3>

            <table class="analytics-table">
                <thead>
                <tr>
                    <th>Team</th>
                    <th>Matches</th>
                    <th style="text-align: center;">Wins</th>
                    <th style="text-align: center;">Draws</th>
                    <th style="text-align: center;">Losses</th>
                    <th style="text-align: center;">Goals</th>
                    <th style="text-align: center;">Points</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($awayTable as $t): ?>
                    <tr onclick="window.location='club.php?id=<?= $t['team_id'] ?>'">
                        <td><?= $t['team_name'] ?></td>
                        <td style="text-align: center;"><?= $t['played'] ?></td>
                        <td style="text-align: center;"><?= $t['wins'] ?></td>
                        <td style="text-align: center;"><?= $t['draws'] ?></td>
                        <td style="text-align: center;"><?= $t['losses'] ?></td>
                        <td style="text-align: center;"><?= $t['gf'] ?> - <?= $t['ga'] ?></td>
                        <td style="text-align: center;"><?= $t['points'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

</body>
</html>