async function renderMap(info, options) {
    let mapPoints = info.mapPoints;

    mapboxgl.accessToken = 'pk.eyJ1IjoibGVvbWFuY2luaSIsImEiOiJjbDA1ZGVteG8xd25qM2pwa3c1dWk1cHRwIn0.qzsxXTieaF8TeauDH1oJkw';

    let maxBounds = null;
    if (options && options.limitBounds) {
        maxBounds = [[-74.3782997, 40.3098117], [-73.5627043, 41.1567828]];
    }

    const map = new mapboxgl.Map({
        container: 'map',
        style: {
            version: 8,
            sources: {},
            layers: [
                {
                    id: 'background',
                    type: 'background',
                    paint: { 'background-color': '#F4F4F4' }
                }
            ]
        },
        center: [-73.92601644070272, 40.748047211371016],
        maxBounds: maxBounds,
        zoom: 10.25,
        maxZoom: 10.75,
        minZoom: 9.25
    });

    const camera = map.getFreeCameraOptions();           
    camera.setPitchBearing(0, 31);
    map.setFreeCameraOptions(camera);

    map.on('load', () => {
        map.addSource('nyc-map', {
            'type': 'image',
            'url': 'resources/images/map/nyc.png',
            'coordinates': [
                [-74.1644204, 40.9388546],
                [-73.6904097, 40.9388546],
                [-73.6904097, 40.5239126],
                [-74.1644204, 40.5239126]
            ]
        });


        map.addLayer({
            'id': 'nyc-map',
            'type': 'raster',
            'source': 'nyc-map'
        });
            
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