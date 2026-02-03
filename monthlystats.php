<?php

require_once 'db_connect.php';

// ΜΗΝΙΑΙΑ ΣΤΑΤΙΣΤΙΚΑ
$sql = "SELECT 
    t.id AS team_id,
    t.name AS team_name,
    MONTH(m.match_date) AS month,
    DATE_FORMAT(m.match_date, '%M') AS month_name,

    COUNT(m.home_score) AS played,

    SUM(
        CASE 
            WHEN (m.home_team = t.id AND m.home_score > m.away_score) OR 
                 (m.away_team = t.id AND m.away_score > m.home_score)
            THEN 1 ELSE 0 
        END
    ) AS wins,

    SUM(CASE WHEN m.home_score = m.away_score THEN 1 ELSE 0 END) AS draws,

    SUM(
        CASE 
            WHEN (m.home_team = t.id AND m.home_score < m.away_score) OR 
                 (m.away_team = t.id AND m.away_score < m.home_score)
            THEN 1 ELSE 0 
        END
    ) AS losses,

    SUM(CASE WHEN m.home_team = t.id THEN m.home_score ELSE m.away_score END) AS score_for,
    SUM(CASE WHEN m.home_team = t.id THEN m.away_score ELSE m.home_score END) AS score_against

FROM teams t
JOIN matches m ON (t.id = m.home_team OR t.id = m.away_team)
WHERE m.match_date IS NOT NULL
GROUP BY t.id, MONTH(m.match_date), DATE_FORMAT(m.match_date, '%M')
ORDER BY month ASC, wins DESC, draws DESC, score_for DESC";

$result = $conn->query($sql);

$monthly = [];
$availableMonths = [];

while ($row = $result->fetch_assoc()) {
    $m = intval($row['month']);
    $monthly[$m][] = $row;

    if (!in_array($m, $availableMonths)) {
        $availableMonths[] = $m;
    }
}

//Η σεζόν ξεκιναει τον αυγουστο
usort($availableMonths, function($a, $b) {
    $seasonOrder = [8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6, 7];
    
    $posA = array_search($a, $seasonOrder);
    $posB = array_search($b, $seasonOrder);
    
    return $posA - $posB;
});

$monthNames = [
    1 => "January",
    2 => "February",
    3 => "March",
    4 => "April",
    5 => "May",
    6 => "June",
    7 => "July",
    8 => "August",
    9 => "September",
    10 => "October",
    11 => "November",
    12 => "December",
];
?>


<title>Monthly Statistics</title>
<?php include "header.php"; ?>

<div class="analytics-container">
    <h2 class="analytics-title">Monthly Statistics</h2>

    <!-- TABS -->
    <div class="tabs2">
        <?php foreach ($availableMonths as $i => $m): ?>
            <button class="tab-button2 <?= $i == 0 ? 'active' : '' ?>" data-tab="month-<?= $m ?>">
                <?= $monthNames[$m] ?>
            </button>
        <?php endforeach; ?>
    </div>

    <?php foreach ($availableMonths as $i => $m): ?>
        <div class="tab-content2 <?= $i == 0 ? 'active' : '' ?>" id="month-<?= $m ?>">


            <table class="analytics-table">
                <thead>
                <tr>
                    <th>Team</th>
                    <th style="text-align: center;">Matches</th>
                    <th style="text-align: center;">Wins</th>
                    <th style="text-align: center;">Draws</th>
                    <th style="text-align: center;">Losses</th>
                    <th style="text-align: center;">GF - GA</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($monthly[$m] as $row): ?>
                    <tr onclick="window.location='club.php?id=<?= $row['team_id'] ?>'">
                        <td><?= $row['team_name'] ?></td>
                        <td style="text-align: center;"><?= $row['played'] ?></td>
                        <td style="text-align: center;"><?= $row['wins'] ?></td>
                        <td style="text-align: center;"><?= $row['draws'] ?></td>
                        <td style="text-align: center;"><?= $row['losses'] ?></td>
                        <td style="text-align: center;"><?= $row['score_for'] ?> - <?= $row['score_against'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    <?php endforeach; ?>

</div>

<script>

document.querySelectorAll(".tab-button2").forEach(btn => {
    btn.addEventListener("click", () => {

        document.querySelectorAll(".tab-button2").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");

        document.querySelectorAll(".tab-content2").forEach(c => c.classList.remove("active"));
        document.getElementById(btn.dataset.tab).classList.add("active");
    });
});
</script>

</body>
</html>