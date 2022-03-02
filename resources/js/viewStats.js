async function load() {
    const info = await getInfo();

    fillMostCommonStations(info);

    fillTripsPerMonth(info);
    
    fillTotalSpent(info);

    fillTripsByTimeOfDay(info);

    renderMap(info, { limitBounds: true });

    showCards();
}

load();