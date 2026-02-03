<?php

$teams = json_decode(file_get_contents("teams.json"), true);

// SORT 
usort($teams, function($a, $b) {
    return strcmp($a["name"], $b["name"]);
});
?>


<title>Clubs | ScoutBoard</title>

<?php include "header.php"; ?>
<div class="teams-container">

  <h2 style="text-align: center;">Clubs List</h2>

  <input type="text" class="teams-search" id="teamSearch" placeholder="Search Club...">

  <div id="teamsList"></div>

  <div class="pagination" id="pagination"></div>

</div>

<script>
const teams = <?php echo json_encode($teams); ?>;
const pageSize = 25;

let currentPage = 1;
let filteredTeams = teams;

// create list 
function renderTeams() {
  const start = (currentPage - 1) * pageSize;
  const end = start + pageSize;
  const list = filteredTeams.slice(start, end);

  let html = "";
  list.forEach(team => {
    html += `
        <a href="club.php?id=${team.id}" class="team-item">
        <strong>${team.name}</strong><br>
        <span>${team.country ?? "-"}</span>
    `;
  });

  document.getElementById("teamsList").innerHTML = html;
  renderPagination();
}

// PAGINATION 
function renderPagination() {
  const totalPages = Math.ceil(filteredTeams.length / pageSize);
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
  renderTeams();
}

// search
document.getElementById("teamSearch").addEventListener("input", function() {
  const q = this.value.toLowerCase();
  filteredTeams = teams.filter(t =>
    t.name.toLowerCase().includes(q) ||
    (t.country && t.country.toLowerCase().includes(q))
  );
  currentPage = 1;
  renderTeams();
});

renderTeams();
</script>

</body>
</html>
