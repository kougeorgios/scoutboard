<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ScoutBoard</title>
  <link rel="stylesheet" href="styles3.css" />
  <meta name="color-scheme" content="dark light" />
</head>
<body>
<header class="header">
  <nav class="nav container">
    <div class="logo">
      <div class="logo-badge">SB</div>
      <a href="index.php" style="color:inherit; text-decoration:none">ScoutBoard</a>
    </div>
    <form class="searchbar" action="search.php" method="get" role="search">
      <input type="search" name="q"  placeholder="Enter your search term" aria-label="Search" required/>
      <button type="submit">Search</button>
    </form>
    <?php if (basename($_SERVER['PHP_SELF']) !== 'index.php'): ?>
      <a class="btn" href="index.php">Home</a>
    <?php endif; ?>
  </nav>
</header>
<nav class="subnav">
  <ul class="subnav-list">
    <li><a href="leagues.php" class="subnav-link">Competitions</a></li>
    <li><a href="teams.php" class="subnav-link">Clubs</a></li>
    <li class="dropdown">
      <a class="subnav-link dropdown-btn">Greek Super League Analytics ▾</a>

      <ul class="dropdown-menu">
        <li><a href="standing.php">Standings</a></li>
        <li><a href="homeaway.php">Home / Away</a></li>
        <li><a href="monthlystats.php">Monthly Stats</a></li>
        <li><a href="momentumgraph.php">Momentum Graph</a></li>
        <li><a href="goalgraph.php">Goal Graph</a></li>
      </ul>
    </li>
  </ul>
</nav>
