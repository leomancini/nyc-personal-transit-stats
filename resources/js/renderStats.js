function renderRow(fields) {
    let row = document.createElement('div');
    row.classList = 'row';

    if (fields.index === 0) { row.classList.add('large'); }

    if (fields.label) {
        let label = document.createElement('div');
        label.classList = 'label';
        label.innerText = fields.label;
        row.appendChild(label);
    }

    if (fields.primaryText) {
        let text = document.createElement('div');
        text.classList = 'text';
        if (fields.fullWidth === true) { text.classList.add('fullWidth'); }
        text.innerText = fields.primaryText;
        row.appendChild(text);
    }

    if (fields.secondaryText) {
        let secondaryText = document.createElement('div');
        secondaryText.classList = 'secondaryText';
        secondaryText.innerText = fields.secondaryText;
        row.appendChild(secondaryText);
    }

    if (fields.number) {
        let number = document.createElement('div');
        number.classList = 'number';
        if (fields.prominentNumber === true) { number.classList.add('prominent'); }
        number.innerText = fields.number;
        row.appendChild(number);
    }

    return row;
}

function fillMostCommonStations(info) {
    const card = document.querySelector('.card#most-common-stations');
    const content = card.querySelector('.content');

    let index = 0;

    for (let station in info.stats.stationsByFrequency) {
        let stationName = station;
        let count = info.stats.stationsByFrequency[station];

        let row = renderRow({
            index,
            primaryText: stationName,
            number: count
        });

        // Only show stations with frequency more than 1
        // Show a maximum of 5 stations
        if (count > 1 && index <= 5) {
            content.appendChild(row);
        }

        index++;
    }
}

function fillTripsPerMonth(info) {
    const card = document.querySelector('.card#trips-per-month');
    const content = card.querySelector('.content');

    let highestNumberOfTrips = Math.max(...Object.values(info.stats.numberOfTripsPerMonth));

    index = 0;
    
    for (let yearMonth in info.stats.numberOfTripsPerMonth) {
        let barLabelPair = document.createElement('span');
        barLabelPair.classList = 'barLabelPair';
        const date = {
            year: yearMonth.split('-')[0],
            month: yearMonth.split('-')[1]
        }

        if (index > 0 && date.month === '12') {
            let divider = document.createElement('div');
            divider.classList = 'divider';
            content.appendChild(divider);
        }

        if (index === 0 || date.month === '12') {
            let yearLabel = document.createElement('div');
            yearLabel.classList = 'yearLabel';
            yearLabel.innerText = date.year;
            content.appendChild(yearLabel);
        }

        let monthLabel = document.createElement('div');
        monthLabel.classList = 'monthLabel';
        monthLabel.innerText = new Date(date.year, date.month - 1, '1', '12', '00', '00').toLocaleDateString('en-US', { month: 'long' });
        barLabelPair.appendChild(monthLabel);

        let number = document.createElement('div');
        number.classList = 'number';
        number.innerText = info.stats.numberOfTripsPerMonth[yearMonth];
        barLabelPair.appendChild(number);

        let bar = document.createElement('div');
        bar.classList = 'bar';

        let count = info.stats.numberOfTripsPerMonth[yearMonth];
        let filled = document.createElement('span');
        filled.classList = 'filled';
        filled.style.width = `${count/highestNumberOfTrips * 100}%`;
        if (count/highestNumberOfTrips * 100 === 0) { filled.classList.add('zero'); }
        bar.appendChild(filled);
        
        barLabelPair.appendChild(bar);
        
        content.appendChild(barLabelPair);

        index++;
    }
}

function fillTripsByTimeOfDay(info) {
    const card = document.querySelector('.card#trips-by-time-of-day');
    const content = card.querySelector('.content');

    let trips = [
        {
            label: 'Earliest',
            data: info.stats.tripsByTimeOfDay[0]
        },
        {
            label: 'Latest',
            data: info.stats.tripsByTimeOfDay[info.stats.tripsByTimeOfDay.length - 1]
        }
    ];

    for (let tripIndex in trips) {
        let trip = trips[tripIndex];
        let datetime = new Date(Date.parse(trip.data.datetime.replace(/-/g, '/')));

        let row = renderRow({
            fullWidth: true,
            label: trip.label,
            primaryText: `${datetime.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric' })} on ${datetime.toLocaleDateString('en-US', { day: 'numeric', year: 'numeric', month: 'short' })}`,
            secondaryText: trip.data.station
        });

        content.appendChild(row);
    }
}

function fillTotalSpent(info) {
    const card = document.querySelector('.card#total-spent');
    const content = card.querySelector('.content');

    index = 0;

    for (let type in info.stats.totalSpent) {
        let count = info.stats.totalSpent[type];

        let row = renderRow({
            index,
            primaryText: type.charAt(0).toUpperCase() + type.slice(1),
            number: count.toLocaleString('en-US', {
                style: 'currency',
                currency: 'USD',
            }),
            prominentNumber: true
        });

        content.appendChild(row);

        index++;
    }
}