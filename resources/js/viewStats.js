async function load() {
    const info = await getInfo();

    fillMostCommonStations(info);

    fillTripsPerMonth(info);
    
    fillTotalSpent(info);

    fillTripsByTimeOfDay(info);
}

load();