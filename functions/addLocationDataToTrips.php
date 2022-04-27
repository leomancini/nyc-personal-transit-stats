<?php
    function preg_grep_keys($pattern, $input, $flags = 0) {
        // From https://www.php.net/manual/en/function.preg-grep.php#111673
        return array_intersect_key($input, array_flip(preg_grep($pattern, array_keys($input), $flags)));
    }

    function sortByLevenshtein($a, $b) {
        if ($a['levenshtein'] == $b['levenshtein']) {
            return 0;
        }

        return ($a['levenshtein'] < $b['levenshtein']) ? -1 : 1;
    }

    function addLocationDataToTrips($tripHistory) {
        $subwayStationsLocationData = getSubwayStationsLocationsData();

        $subwayStationsLocationDataFormatted = [];

        foreach ($subwayStationsLocationData as &$subwayStationLocationData) {
            $subwayStationsLocationDataFormatted[$subwayStationLocationData->properties->name] = [
                'name' => $subwayStationLocationData->properties->name,
                'line' => $subwayStationLocationData->properties->line,
                'coordinates' => $subwayStationLocationData->geometry->coordinates
            ];
        }

        foreach ($tripHistory as &$trip) {
            $stationName = $trip['station'];
            $stationName = str_replace('34 St - 11 Av', '34 St - Hudson Yards', $stationName);
            $stationName = str_replace('14 St - Union Sq', 'Union Sq - 14th St', $stationName);

            $stationName = str_replace('/', ' ', $stationName);
            $stationName = str_replace(' / ', ' ', $stationName);
            $stationName = str_replace(' - ', ' ', $stationName);
            $stationName = str_replace('-', ' ', $stationName);

            $stationNameComponents = explode(' ', $stationName);
            $stationNameComponents = array_slice($stationNameComponents, 0, 2);

            $matchedStations = preg_grep_keys("/^(?=.*".implode(')(?=.*', $stationNameComponents).").*$/", $subwayStationsLocationDataFormatted);

            foreach ($matchedStations as $matchKey => $matchValue) {
                $matchedStations[$matchKey]['levenshtein'] = levenshtein($matchKey, $stationName);
            }

            $sortedMatchedStations = $matchedStations;
    
            usort($sortedMatchedStations, 'sortByLevenshtein');

            $trip['bestStationMatch'] = $sortedMatchedStations[0];
            
            unset($trip['bestStationMatch']['levenshtein']);
        }

        return $tripHistory;
    }
?>