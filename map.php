<?php require('secrets.php') ;?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>Map</title>
        <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no'>
        <link href='https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css' rel='stylesheet'>
        <script src='https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js'></script>
        <style>
            body { margin: 0; padding: 0; }
            #map { position: absolute; top: 0; bottom: 0; width: 100%; }
        </style>
</head>
<body>
    <div id='map'></div>
    <script src='resources/js/functions.js'></script>

    <script>
        async function load() {
            let mapPoints = await getInfo({ mapPoints: true });

            mapboxgl.accessToken = '<?php echo $_SECRETS['MAPBOX_ACCESS_TOKEN']; ?>';

            const map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/leomancini/cl05yhjio001t14l3xbaxfzg4',
                center: [-73.92, 40.73],
                zoom: 10.5
            });

            const camera = map.getFreeCameraOptions();           
            camera.setPitchBearing(0, 31);
            map.setFreeCameraOptions(camera);

            map.on('load', () => {
                map.loadImage('resources/images/map/station.png', (error, image) => {
                    if (error) throw error;
                    map.addImage('station', image);
                    map.addSource('stations', mapPoints.notVisited);

                    map.addLayer({
                        'id': 'stations',
                        'type': 'symbol',
                        'source': 'stations',
                        'layout': {
                            'icon-size': 0.5,
                            'icon-image': 'station',
                        }
                    });

                    map.loadImage('resources/images/map/station-visited.png', (error, image) => {
                        if (error) throw error;
                        map.addImage('station-visited', image);
                        map.addSource('stations-visited', mapPoints.visited);

                        map.addLayer({
                            'id': 'stations-visited',
                            'type': 'symbol',
                            'source': 'stations-visited',
                            'layout': {
                                'icon-size': 0.5,
                                'icon-image': 'station-visited',
                            }
                        });
                    });
                });
            });
        }

        load();
    </script>

    </body>
</html>