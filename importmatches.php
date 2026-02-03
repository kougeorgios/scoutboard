<?php

require_once 'db_connect.php';

$apiKey = "aac4bd6784msh180d77ca164e275p18d20cjsn7601185db110";
$apiHost = "sofascore.p.rapidapi.com";

$seasonTarget = "78175"; // Super League Greece 25/26


// ΦΕΡΝΟΥΜΕ ΤΙΣ ΟΜΑΔΕΣ ΑΠΟ ΤΗΝ ΒΑΣΗ

$teamsQuery = $conn->query("SELECT id FROM teams");

if ($teamsQuery->num_rows === 0) {
    die("⚠ Δεν βρέθηκαν ομάδες στον πίνακα teams.");
}

$totalInserted = 0;
$totalSkipped = 0;


// 2) LOOP ΣΕ ΚΑΘΕ ΟΜΑΔΑ ΚΑΙ ΚΛΗΣΗ ΤΟΥ API
while ($team = $teamsQuery->fetch_assoc()) {

    $teamId = $team["id"];
    echo "<hr><strong>Ομάδα: $teamId</strong><br>";

    $url = "https://sofascore.p.rapidapi.com/teams/get-last-matches?teamId=$teamId&pageIndex=0";

    $options = [
        "http" => [
            "header" => [
                "x-rapidapi-key: $apiKey",
                "x-rapidapi-host: $apiHost"
            ]
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if (!$response) {
        echo "⚠ Δεν βρέθηκαν δεδομένα για την ομάδα $teamId<br>";
        continue;
    }

    $json = json_decode($response, true);

    if (!isset($json["events"])) {
        echo "⚠ Δεν υπάρχουν παιχνιδια για την ομάδα $teamId<br>";
        continue;
    }

    $events = $json["events"];

    // 3) ΕΠΕΞΕΡΓΑΣΙΑ ΚΑΘΕ ΑΓΩΝΑ
    foreach ($events as $match) {

        // Ελεγχος season
        if (($match["season"]["id"] ?? "") != $seasonTarget) {
            continue;
        }

        $matchId = $match["id"];

        // Ελεγχος αν υπάρχει ήδη στον πίνακα
        $check = $conn->query("SELECT id FROM matches WHERE id = $matchId");
        if ($check->num_rows > 0) {
            $totalSkipped++;
            continue;
        }

        $homeTeamId = $match["homeTeam"]["id"];
        $awayTeamId = $match["awayTeam"]["id"];

        $homeScore = $match["homeScore"]["current"] ?? null;
        $awayScore = $match["awayScore"]["current"] ?? null;

        $timestamp = $match["startTimestamp"] ?? null;
        $date = $timestamp ? date("Y-m-d", (int)$timestamp) : null;

        $homeScoreVal = is_null($homeScore) ? "NULL" : $homeScore;
        $awayScoreVal = is_null($awayScore) ? "NULL" : $awayScore;
        $dateVal = is_null($date) ? "NULL" : "'$date'";

        // εισαγωγη των αγωνων
        $sql = "
        INSERT INTO matches (id, match_date, home_team, away_team, home_score, away_score)
        VALUES ($matchId, $dateVal, $homeTeamId, $awayTeamId, $homeScoreVal, $awayScoreVal)
        ";

        if ($conn->query($sql) === TRUE) {
            $totalInserted++;
            echo "✔ Προστέθηκε ο αγώνας $matchId<br>";
        } else {
            echo "❌ Σφάλμα στον αγώνα $matchId: " . $conn->error . "<br>";
        }
    }
}

echo "<hr><h3>Ολοκληρώθηκε!</h3>";
echo "➜ Νέοι αγώνες: $totalInserted<br>";
echo "➜ Ήδη υπήρχαν: $totalSkipped<br>";

$conn->close();
?>
