<?php
// Load competitions.json
$leagues = json_decode(file_get_contents("competitions.json"), true);

// sort
usort($leagues, function($a, $b) {
    $countryA = $a['country'] ?? '';
    $countryB = $b['country'] ?? '';

    if ($countryA === $countryB) {
        return strcmp($a['name'], $b['name']);
    }

    return strcmp($countryA, $countryB);
});
?>

<title> Competitions | ScoutBoard </title>
<?php include "header.php"; ?>

<div class="leagues-container">

  <h2 style="text-align: center;">Competitions List</h2>

  <input type="text" class="leagues-search" id="leagueSearch" placeholder="Search Competition...">

  <div id="leaguesList"></div>

  <div class="pagination" id="pagination"></div>

</div>

<script>
const leagues = <?php echo json_encode($leagues); ?>;
const pageSize = 25;

let currentPage = 1;
let filteredLeagues = leagues;

// create list
function renderLeagues() {
  const start = (currentPage - 1) * pageSize;
  const end = start + pageSize;
  const list = filteredLeagues.slice(start, end);

  let html = "";
  list.forEach(l => {

    html += `
      <a href="competition.php?id=${l.id}" class="league-item">
          <strong>${l.name}</strong><br>
          <span>${l.country ?? "-"}</span>
      </a>
    `;
  });

  document.getElementById("leaguesList").innerHTML = html;
  renderPagination();
}

// pagination
function renderPagination() {
  const totalPages = Math.ceil(filteredLeagues.length / pageSize);
  let html = "";

  for (let i = 1; i <= totalPages; i++) {
    html += `
      <button class="${i === currentPage ? "active" : ""}" onclick="goToPage(${i})">${i}</button>
    `;
  }

  document.getElementById("pagination").innerHTML = html;
}

function goToPage(page) {
  currentPage = page;
  renderLeagues();
}

// search
document.getElementById("leagueSearch").addEventListener("input", function () {
  const q = this.value.toLowerCase();
  filteredLeagues = leagues.filter(l =>
    l.name.toLowerCase().includes(q) ||
    (l.country && l.country.toLowerCase().includes(q))
  );
  currentPage = 1;
  renderLeagues();
});

renderLeagues();
</script>

</body>
</html>
