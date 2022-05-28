<?php
    function parseTripHistory($file) {
        $line = 0;

        $file = fopen($file, 'r');
    
        if ($file !== FALSE) {
            $array = [];
    
            while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
                $numberOfLines = count($data);
    
                $line++;
    
                $arrayItem = [
                    'id' => null,
                    'datetime' => null,
                    'type' => null,
                    'station' => null,
                    'payment' => null,
                    'fare' => null
                ];
    
                if ($line > 1) {
                    for ($key = 0; $key < $numberOfLines; $key++) {
                        $arrayItem['id'] = $data[0];
                        $arrayItem['datetime'] = $data[2];
                        $arrayItem['type'] = $data[3];
                        $arrayItem['station'] = $data[4];
                        $arrayItem['payment'] = $data[5];
                        $arrayItem['fare'] = $data[6];
                    }
    
                    array_push($array, $arrayItem);
                }
            }
    
            fclose($file);
        }
    
        return $array;
    }
?>