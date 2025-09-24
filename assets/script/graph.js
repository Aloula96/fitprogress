// get the ellement div that contains the data
const chartDiv = document.querySelector("#graph");

// fetch the value of the attribute 'data-score'
const statsJson = chartDiv.dataset.stats;
console.log(statsJson);

// Analyse the JSON string into a JavaScript object
const objStats = JSON.parse(statsJson);

console.log(objStats);
// Create arrays to store the weight and dates

const weights = [];
const dates = [];

// Loop through the JSON object and push the data into the arrays
objStats.forEach((item) => {
    weights.push(item.weightValue);
    dates.push(item.recordedAt);
});

// Configuration the options of graph ApexCharts
let options = {
    series: [
        {
            name: "Poids",
            data: weights,
        },
    ],
    chart: {
        height: 350,
        type: "line",
        zoom: {
            enabled: false,
        },
    },
    dataLabels: {
        enabled: true,
    },
    stroke: {
        curve: "smooth",
    },
    title: {
        text: "Date",
        align: "left",
    },
    grid: {
        row: {
            colors: ["#f3f3f3", "transparent"],
            opacity: 0.5,
        },
    },
    xaxis: {
        categories: dates,
    },
};

// Créer l'instance du graphique
var graph = new ApexCharts(document.querySelector("#graph"), options);

// Rendre le graphique
graph.render();
