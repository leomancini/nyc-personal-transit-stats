<?php
    header("Access-Control-Allow-Origin: *");

    require ('functions/parseTripHistory.php');
    require ('functions/generateStats.php');

    $tripHistoryFile = 'data/trip-history.csv';
    $tripHistory = parseTripHistory($tripHistoryFile);

    $stats = generateStats($tripHistory);

    if ($_GET['debug']) { echo '<pre>'; }

    echo json_encode([
        'stats' => $stats,
        'tripHistory' => $tripHistory
    ]);

    if ($_GET['debug']) { echo '</pre>'; }
?>