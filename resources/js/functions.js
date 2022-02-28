async function getInfo(options) {
    let dataSource = `getInfo.php?${new URLSearchParams(options)}`;

    if (location.hostname === '127.0.0.1') {
        dataSource = 'http://localhost/nyc-personal-transit-stats/' + dataSource;
    }

    const response = await fetch(dataSource);
    const info = await response.json();

    return info;
}