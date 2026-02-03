<?php

require_once 'db_connect.php';

$sql = "SELECT 
        m.id,
        m.match_date,
        m.home_team,
        m.away_team,
        m.home_score,
        m.away_score,
        t1.name AS home_name,
        t2.name AS away_name
    FROM matches m
    JOIN teams t1 ON m.home_team = t1.id 
    JOIN teams t2 ON m.away_team = t2.id
    ORDER BY m.match_date ASC
";

$result = $conn->query($sql);

 $teamColors = [
    3245 => ["#E2001A", "#020000ff"], // Olympiacos FC - κόκκινο
    3251 => ["#000000", "#030000ff"], // PAOK - μαύρο
    3250 => ["#f0f416ff", "#110d01ff"], // AEK Athens - κίτρινο
    5063 => ["#2BB673", "#000704ff"], // APO Levadiakos - πράσινο
    267459 => ["#0054A6", "#040000ff"], // NPS Volos - μπλε
    3248 => ["#009639", "#000703ff"], // Panathinaikos - πράσινο
    3252 => ["#FFDD00", "#070600ff"], // Aris Thessaloniki - κίτρινο
    120224 => ["#014ae6ff", "#000207ff"], // AE Kifisia - μπλε
    7004 => ["#F7D117", "#010004ff"], // Panetolikos - κίτρινo
    5062 => ["#1B75BB", "#00060aff"], // Atromitos Athinon - μπλε
    6342 => ["#0260bdff", "#080800ff"], // Asteras Tripolis (Aktor) - μπλε
    3265 => ["#7A0019", "#050001ff"], // AEL - βυσσινί
    3241 => ["#231F20", "#0e0d0dff"], // OFI Crete - μαύρο
    6347 => ["#DE1A1A", "#030000ff"], // Panserraikos - κόκκινο
];


$momentum = [];
$matchesCount = [];

while ($row = $result->fetch_assoc()) {
    $home = $row["home_team"];
    $away = $row["away_team"];
    $home_score = (int)$row["home_score"];
    $away_score = (int)$row["away_score"];
    $home_name = $row["home_name"];
    $away_name = $row["away_name"];

    if (!isset($momentum[$home])) {
        $momentum[$home] = ["name" => $home_name, "scores" => [], "total" => 0];
        $matchesCount[$home] = 0;
    }
    if (!isset($momentum[$away])) {
        $momentum[$away] = ["name" => $away_name, "scores" => [], "total" => 0];
        $matchesCount[$away] = 0;
    }

    $matchesCount[$home]++;
    $matchesCount[$away]++;

    if ($row["home_score"] !== NULL && $row["away_score"] !== NULL) {
    // αποτελεσματα αγωνων
    if ($home_score > $away_score) {
        $momentum[$home]["total"] += 3;
        $momentum[$away]["total"] += 0;
    } elseif ($home_score < $away_score) {
        $momentum[$home]["total"] += 0;
        $momentum[$away]["total"] += 3;
    } else {
        $momentum[$home]["total"] += 1;
        $momentum[$away]["total"] += 1;
    }

    //αποθηκευση των βαθμων καθε αγωνιστικη
    $momentum[$home]["scores"][] = $momentum[$home]["total"];
    $momentum[$away]["scores"][] = $momentum[$away]["total"];
    } 
    else {
        $momentum[$home]["scores"][] = $momentum[$home]["total"]; 
        $momentum[$away]["scores"][] = $momentum[$away]["total"];
    }
}

$maxTotalScore = 0;
foreach ($momentum as $teamData) {
    // Ελέγχουμε την τελευταία καταγεγραμμένη τιμή scores
    if (!empty($teamData['scores'])) {
        $lastScore = end($teamData['scores']);
        if ($lastScore > $maxTotalScore) {
            $maxTotalScore = $lastScore;
        }
    }
}
$maxTotalScore += 5; // στο γραφημα να εχει κενο 5 μοναδες απο την κορυφη

if ($maxTotalScore == 5) {
    $maxTotalScore = 10;
}

?>


<title>Momentum Graph | ScoutBoard</title>
<?php include "header.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<div class="analytics-container3">
    <h2 class="analytics-title">Momentum Graph</h2>

    <canvas id="momentumChart"></canvas>
</div>

<script>
const teamColors = <?php echo json_encode($teamColors); ?>;
</script>

<script>
const maxChartPoints = <?php echo $maxTotalScore; ?>;
const rawMomentum = <?php echo json_encode($momentum); ?>;
let chart;

function getColor(teamId, index = 0) {
    if (teamColors[teamId]) {
        return teamColors[teamId][index];
    }
    return ["#888888", "#AAAAAA"][index];
}

// Δημιουργια dataset για όλες τις ομάδες
function buildAllTeamsDatasets() {
    return Object.entries(rawMomentum).map(([teamId, t]) => ({
        label: t.name,
        data: t.scores,
        tension: 0.18,
        borderWidth: 2,
        fill: false,
        borderColor: getColor(teamId, 0), 
        backgroundColor: getColor(teamId, 1) + "33"
        
    }));
}

function getMaxMatches() {
    let maxLen = 0;
    Object.values(rawMomentum).forEach(t => {
        if (t.scores.length > maxLen) maxLen = t.scores.length;
    });
    return maxLen;
}

// chart
function renderChart(teamId = null) {
    if (chart) chart.destroy();

    let labels = Array.from({length: getMaxMatches()}, (_, i) => i + 1);
    let datasets= buildAllTeamsDatasets();

    chart = new Chart(document.getElementById("momentumChart"), {
        type: "line",
        data: { labels, datasets },
        options: {
            responsive: true,
            plugins: { 
                legend: { 
                    position: "bottom",
                    labels: { 
                        color: "#fff" 
                    },
                    onClick: (e, legendItem, legend) => {
                        const chart = legend.chart;
                        const metaIndex = legendItem.datasetIndex;
                        const currentState = chart.isDatasetVisible(metaIndex);
                        const visibleDatasets = chart.data.datasets.filter((d, i) => chart.isDatasetVisible(i)).length;
            
                        if (currentState && visibleDatasets === 1) {
                            chart.data.datasets.forEach((dataset, i) => {
                                chart.setDatasetVisibility(i, true);
                            });
                        } else {
                            chart.data.datasets.forEach((dataset, i) => {
                                chart.setDatasetVisibility(i, i === metaIndex);
                            });
                        }

                        chart.update();
                    }
                },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            return 'Round ' + context[0].label;
                        }
                    }
                } 
            },
            scales: {
                x: {
                     title: { display: true, text: "Round", color: "#fff"  },
                     ticks: { color: "#fff" },
                     grid: { color: "rgba(255,255,255,0.15)" } 
                    },
                y: { 
                    title: { display: true, text: "Points", color: "#fff"  },
                    ticks: { color: "#fff" },
                    grid: { color: "rgba(255,255,255,0.15)" },
                    min: 0,
                    max: maxChartPoints
                
                }
            }
        }
    });
}

renderChart();
</script>

</body>
</html>