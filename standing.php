<?php

require_once 'db_connect.php';

$sql = "SELECT 
    t.id AS team_id,
    t.name AS team_name,
    COUNT(m.id) AS matches_played,
    SUM(CASE WHEN m.home_team = t.id THEN m.home_score WHEN m.away_team = t.id THEN m.away_score ELSE 0 END) AS goals_for,
    SUM(CASE WHEN m.home_team = t.id THEN m.away_score WHEN m.away_team = t.id THEN m.home_score ELSE 0 END) AS goals_against,
    SUM(CASE WHEN m.home_team = t.id THEN m.home_score - m.away_score WHEN m.away_team = t.id THEN m.away_score - m.home_score ELSE 0 END) AS goal_difference,
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
    SUM(
        CASE 
            WHEN (m.home_team = t.id AND m.home_score > m.away_score) OR 
                 (m.away_team = t.id AND m.away_score > m.home_score) THEN 3
            WHEN m.home_score = m.away_score THEN 1
            ELSE 0
        END
    ) AS points
FROM teams t
LEFT JOIN matches m ON (t.id = m.home_team OR t.id = m.away_team) AND m.home_score IS NOT NULL AND m.away_score IS NOT NULL
GROUP BY t.id, t.name
ORDER BY points DESC, goal_difference DESC, goals_for DESC";

$result = $conn->query($sql);

?>

<title>GR Super League Standing | Scoutboard</title>
<?php include "header.php"; ?>

<div class="analytics-container">
<h3 class="analytics-title">Greek Super League Standing</h3>

<table class="analytics-table">
<thead>
<tr>
  <th>#</th>
  <th>Team</th>
  <th>P</th>
  <th>W</th>
  <th>D</th>
  <th>L</th>
  <th>GF</th>
  <th>GA</th>
  <th>GD</th>
  <th>Pts</th>
</tr>
</thead>

<tbody>

<?php
$position = 1;
while ($row = $result->fetch_assoc()):
?>
<tr class='clickable-row' data-href='club.php?id=<?= $row['team_id'] ?>'>
  <td><?= $position++; ?></td>
  <td><?= htmlspecialchars($row['team_name']) ?></td>
  <td><?= $row["matches_played"]; ?></td>
  <td><?= $row["wins"]; ?></td>
  <td><?= $row["draws"]; ?></td>
  <td><?= $row["losses"]; ?></td>
  <td><?= $row["goals_for"]; ?></td>
  <td><?= $row["goals_against"]; ?></td>
  <td><?= $row["goal_difference"]; ?></td>
  <td><strong><?= $row["points"]; ?></strong></td>
</tr>

<?php endwhile; ?>

</tbody>
</table>

</div>

<script>
document.querySelectorAll(".clickable-row").forEach(row => {
    row.addEventListener("click", () => {
        window.location.href = row.dataset.href;
    });
});
</script>

</body>
</html>
