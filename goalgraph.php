<?php

require_once 'db_connect.php';

$sql = "SELECT 
    t.id,
    t.name,
    SUM(CASE WHEN m.home_team = t.id THEN m.home_score ELSE m.away_score END) AS gf,
    SUM(CASE WHEN m.home_team = t.id THEN m.away_score ELSE m.home_score END) AS ga
FROM teams t
JOIN matches m ON m.home_team = t.id OR m.away_team = t.id
GROUP BY t.id
ORDER BY t.name ASC
";

$res = $conn->query($sql);
$teams = [];

while ($row = $res->fetch_assoc()) {
    $teams[] = $row;
}
?>

<title>GF/GA Graph | ScoutBoard</title>
<?php include "header.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<div class="analytics-container3">
    <h2 class="analytics-title">Goals For / Goals Against Graph</h2>
    <div id="chartWrapper">
        <canvas id="gfGaChart"></canvas>
    </div>
</div>

<script>

// LOGOS
const teamLogos = {
    3245: "images/logo_3245.png",
    3251: "images/logo_3251.png",
    3250: "images/logo_3250.png",
    3248: "images/logo_3248.png",
    3252: "images/logo_3252.png",
    7004: "images/logo_7004.png",
    3241: "images/logo_3241.png",
    5063: "images/logo_5063.png",
    267459: "images/logo_267459.png",
    120224: "images/logo_120224.png",
    5062: "images/logo_5062.png",
    6342: "images/logo_6342.png",
    3265: "images/logo_3265.png",
    6347: "images/logo_6347.png"
};

const teamData = <?= json_encode($teams, JSON_UNESCAPED_UNICODE) ?>;

const avgGF = teamData.reduce((a,b)=>a+Number(b.gf),0) / teamData.length;
const avgGA = teamData.reduce((a,b)=>a+Number(b.ga),0) / teamData.length;


// Load images FIRST
function loadImages() {
    return Promise.all(
        teamData.map(t =>
            new Promise(resolve => {
                const img = new Image();
                img.src = teamLogos[t.id];
                img.onload = () => { t.img = img; resolve(); };
            })
        )
    );
}

// Custom plugin 
const logoPlugin = {
    id: 'logoPlugin',
    afterDatasetsDraw(chart, args, opts) {
        const ctx = chart.ctx;

        chart.data.datasets[0].data.forEach((point, i) => {
            const meta = chart.getDatasetMeta(0);
            const pos = meta.data[i].getProps(['x', 'y'], true);

            const team = teamData[i];
            if (!team.img) return;

            const size = 28;
            ctx.drawImage(team.img, pos.x - size/2, pos.y - size/2, size, size);
        });
    }
};


// Prepare chart
loadImages().then(() => {

const ctx = document.getElementById("gfGaChart").getContext("2d");

new Chart(ctx, {
    type: "scatter",
    data: {
        datasets: [
            {
                label: "Teams",
                data: teamData.map(t => ({
                    x: Number(t.gf),
                    y: Number(t.ga),
                    label: t.name
                })),
                pointRadius: 0,
                pointHitRadius:10,
                backgroundColor: "transparent"
            },

            // Horizontal avg (GA)
            {
                type: "line",
                data: [{ x: 0, y: avgGA }, { x: 45, y: avgGA }],
                borderColor: "#ffffff55",
                borderDash: [6,4],
                borderWidth: 1,
                pointRadius: 0
            },

            // Vertical avg (GF)
            {
                type: "line",
                data: [{ x: avgGF, y: 0 }, { x: avgGF, y: 45 }],
                borderColor: "#ffffff55",
                borderDash: [6,4],
                borderWidth: 1,
                pointRadius: 0
            }
        ]
    },
    options: {
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => {
                        const t = ctx.raw;
                        return `${t.label} — GF: ${t.x} | GA: ${t.y}`;
                    }
                }
            }
        },
        scales: {
            x: {
                title: { display: true, text: "Goals For (GF)", color: "#fff" },
                ticks: { color: "#fff" },
                grid: { color: "rgba(255,255,255,0.15)" }
            },
            y: {
                title: { display: true, text: "Goals Against (GA)", color: "#fff" },
                ticks: { color: "#fff" },
                grid: { color: "rgba(255,255,255,0.15)" }
            }
        }
    },
    plugins: [logoPlugin]
});

});
</script>

</body>
</html>