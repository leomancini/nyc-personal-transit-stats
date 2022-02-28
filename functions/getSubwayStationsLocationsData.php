<?php
    function getSubwayStationsLocationsData() {
        $subwayStationsLocationsDataFile = 'data/subway-stations.geojson';
        $subwayStationsLocationsDataRequest = fopen($subwayStationsLocationsDataFile, 'r') or die('Unable to open file!');

        $subwayStationsLocationDataJSON = fread($subwayStationsLocationsDataRequest, filesize($subwayStationsLocationsDataFile));
        $subwayStationsLocationData = json_decode($subwayStationsLocationDataJSON)->features;

        fclose($subwayStationsLocationsDataRequest);

        return $subwayStationsLocationData;
    }
?>