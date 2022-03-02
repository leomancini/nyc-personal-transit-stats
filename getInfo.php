<?php
    header('Access-Control-Allow-Origin: *');

    require ('functions/getSubwayStationsLocationsData.php');
    require ('functions/parseTripHistory.php');
    require ('functions/generateStats.php');
    require ('functions/addLocationDataToTrips.php');

    $tripHistoryFile = 'data/trip-history.csv';
    $tripHistory = parseTripHistory($tripHistoryFile);
    $tripHistoryWithLocationData = addLocationDataToTrips($tripHistory);

    $stats = generateStats($tripHistory);

    if ($_GET['debug']) { echo '<pre>'; }

    $visitedStations = [
        'names' => [],
        'locations' => []
    ];

    foreach($tripHistoryWithLocationData as $tripKey => $tripValue) {
        array_push($visitedStations['names'], $tripHistoryWithLocationData[$tripKey]['bestStationMatch']['name']);
        array_push($visitedStations['locations'], [
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Point',
                'coordinates' => $tripHistoryWithLocationData[$tripKey]['bestStationMatch']['coordinates']
            ]
        ]);
    }

    $subwayStationsLocationData = getSubwayStationsLocationsData();

    $notVisitedStations = [
        'names' => [],
        'locations' => []
    ];

    foreach ($subwayStationsLocationData as &$subwayStationLocationData) {
        // Don't add stations that have been visited
        if (!in_array($subwayStationLocationData->properties->name, $visitedStations['names'])) {
            array_push($notVisitedStations['locations'], [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => $subwayStationLocationData->geometry->coordinates
                ]
            ]);
        }
    }

    echo json_encode([
        'stats' => $stats,
        'tripHistory' => $tripHistoryWithLocationData,
        'mapPoints' => [
            'visited' => [
                'type' => 'geojson',
                'data' => [
                    'type' => 'FeatureCollection',
                    'features' => $visitedStations['locations']
                ]
            ],
            'notVisited' => [
                'type' => 'geojson',
                'data' => [
                    'type' => 'FeatureCollection',
                    'features' => $notVisitedStations['locations']
                ]
            ]
        ]
    ]);

    if ($_GET['debug']) { echo '</pre>'; }
?>