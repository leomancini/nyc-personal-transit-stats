<?php
    function getFrequency($array) {
        $arrayFrequency = array_count_values($array);
        arsort($arrayFrequency);

        return $arrayFrequency;
    }

    function generateStats($tripHistory) {
        $stats = [];

        // Get most common stations
        $stations = [];
        foreach ($tripHistory as &$trip) {
            array_push($stations, $trip['station']);
        }

        $stats['stationsByFrequency'] = getFrequency($stations);


        // Get total value of fares
        $fares = [
            'subway' => [],
            'bus' => []
        ];

        foreach ($tripHistory as &$trip) {
            if ($trip['type'] === 'Subway') {
                array_push($fares['subway'], str_replace('$', '', $trip['fare']));
            }

            if ($trip['type'] === 'Bus') {
                array_push($fares['bus'], str_replace('$', '', $trip['fare']));
            }
        }

        $totalSubwayFares = array_sum($fares['subway']);
        $totalBusFares = array_sum($fares['bus']);
        
        $stats['totalSpent'] = [
            'subway' => $totalSubwayFares,
            'bus' => $totalBusFares,
            'total' => $totalSubwayFares + $totalBusFares
        ];


        // Get number of trips
        $tripCounts = [
            'subway' => [],
            'bus' => []
        ];

        foreach ($tripHistory as &$trip) {
            if ($trip['type'] === 'Subway') {
                array_push($tripCounts['subway'], 1);
            }

            if ($trip['type'] === 'Bus') {
                array_push($fares['bus'], 1);
            }
        }

        $totalSubwayTrips = array_sum($tripCounts['subway']);
        $totalBusTrips = array_sum($tripCounts['bus']);
        
        $stats['totalNumberOfTrips'] = [
            'subway' => $totalSubwayTrips,
            'bus' => $totalBusTrips,
            'total' => $totalSubwayTrips + $totalBusTrips
        ];


        // Get number of trips per month
        $yearMonthPairs = [];

        foreach ($tripHistory as &$trip) {
            $timestamp = strtotime($trip['datetime']);
            array_push($yearMonthPairs, date('Y-m', $timestamp));
        }
        
        $numberOfTripsPerMonth = array_count_values($yearMonthPairs);

        $begin = new DateTime('2021-09-10');
        $now = new DateTime();
    
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($begin, $interval, $now);
        
        $everyYearMonthPair = [];
    
        foreach ($period as $date) {
            if ($numberOfTripsPerMonth[$date->format('Y-m')]) {
                $everyYearMonthPair[$date->format('Y-m')] = $numberOfTripsPerMonth[$date->format('Y-m')];
            } else {
                $everyYearMonthPair[$date->format('Y-m')] = 0;
            }
        }

        $everyYearMonthPair = array_reverse($everyYearMonthPair);

        $stats['numberOfTripsPerMonth'] = $everyYearMonthPair;


        // Sort trips by time of day
        $tripsByTimeOfDay = $tripHistory;

        function sortTimestamps($a, $b) {
            $timestampA = explode(' ', $a['datetime'])[1];
            $timestampB = explode(' ', $b['datetime'])[1];
            
            return ($timestampA < $timestampB) ? -1 : 1;
        }

        usort($tripsByTimeOfDay, 'sortTimestamps');

        $stats['tripsByTimeOfDay'] = $tripsByTimeOfDay;
        

        return $stats;
    }
?>