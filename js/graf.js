const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

const StockSymbol = urlParams.get('company');


function fetchStock() {
	const pointerToThis = this;
	console.log(pointerToThis);
	const API_KEY = 'PU0BNT9UUX1IV9VL';
	let API_Call = `https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=${StockSymbol}&outputsize=compact&apikey=${API_KEY}`;
	let stockChartXValuesFunction = [];
	let stockChartYValuesFunction = [];

	let stockChartXValue = [];
	let stockChartYValue = [];

	fetch(API_Call)
		.then(
			function (response) {
				return response.json();
			}
		)
		.then(
			function (data) {
				for (var key in data['Time Series (Daily)']) {
					stockChartXValuesFunction.push(key);
					stockChartYValuesFunction.push(data['Time Series (Daily)'][key]['1. open']);
				}

				stockChartXValue = stockChartXValuesFunction;
				stockChartYValue = stockChartYValuesFunction;
			}
		).then(function () {
			data = [{
				x: stockChartXValue,
				y: stockChartYValue,
				type: 'scatter',
				mode: 'lines+markers',
				marker: { color: 'red' },
			}],

				layout = {
					width: 1400, height: 650, title: 'Stock price development in time (last 100 days)'
				};

			Plotly.newPlot('graf', data, layout);

			var cena = stockChartYValue[0];
			let cislo = parseFloat(cena);
			var cenaDiv = document.getElementById('cena');
			cenaDiv.textContent = ("Current price: " + cislo.toFixed(2) + " $")
			console.log(cena);
		})
}

$(document).ready(function () {
	fetchStock();
});


