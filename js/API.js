// const api_url =
//    "https://api.marketaux.com/v1/news/all?&language=en&api_token=4zI0g4o0oSaNAN0XEO4OawWR28sIr08V3RZIV6Uq";

const symbol_url = "https://finnhub.io/api/v1/quote?symbol=";
const token = "&token=c74iekaad3iboasscsig";



/*
function randomDate(start, end) {
    return new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime()));
}
*/
/*

async function handleNews() {
    const response = await fetch(api_url);
    const data = await response.json();


    for (var i = 0; i < 4; i++) {
        var nadpis = data[i].headline;
        var text = data[i].summary;
        var img = data[i].image;
        var date = randomDate(new Date(2022, 1, 1), new Date())

        var zprava = document.createElement("div");
        var titulek = document.createElement("h1");
        var obsah = document.createElement("div");
        var image_url = document.createElement("img");
        var textik = document.createElement("p");
        var wrapper = document.createElement("div");
        var commentSection = document.createElement('div');
        var datum = document.createElement('h3');

        datum.innerHTML = date.toLocaleDateString();
        datum.className = "datum";

        commentSection.className = "commentSection";
        obsah.className = "obsah";
        zprava.className = "zprava";
        zprava.appendChild(obsah);

        wrapper.className = "wrapper";
        wrapper.appendChild(image_url);

        obsah.appendChild(wrapper);
        obsah.appendChild(titulek);
        obsah.appendChild(image_url);
        obsah.appendChild(textik);
        obsah.appendChild(datum);
        zprava.appendChild(commentSection);

        commentSection.innerHTML = '<p>View comments: <i class="arrow right"></i></p>';

        document.getElementById("content").appendChild(zprava);


        titulek.innerHTML = nadpis;
        image_url.src = img;
        textik.innerHTML = text;
    }
}

/*
async function handleStockPrices() {
    const response = await fetch(api_url_stocks);
    const data = await response.json();

    var cena = data["c"];
    var zmena = data["dc"];
}
*/

// handleNews();



const symbols = [
    "GOOGL",
    "NFLX",
    "AMZN",
    "FB",
    "AAPL",
    "TSLA"
];

function update() {
    setInterval(function () {
        Promise.all([
            fetch(symbol_url + symbols[0] + token),
            fetch(symbol_url + symbols[1] + token),
            fetch(symbol_url + symbols[2] + token),
            fetch(symbol_url + symbols[3] + token),
            fetch(symbol_url + symbols[4] + token),
            fetch(symbol_url + symbols[5] + token)

        ]).then(function (responses) {
            return Promise.all(responses.map(function (response) {
                return response.json();
            }));
        }).then(function (data) {
            var dp = document.getElementsByClassName('dcchange');
            var cisla = document.getElementsByClassName('cena');


            for (let i = 0; i < 6; i++) {
                var x = document.createElement('p');
                dp[i].appendChild(x);
                x.className = "percent";

                var sipka = document.createElement('i');
                sipka.className = "fa-solid";
                var clearDiv = document.createElement('div');
                clearDiv.style = "clear: both";
                var icko = document.createElement('i');
                icko.className = "arrow";
                lol = document.getElementsByClassName('percent');
                lol[i].appendChild(icko);
                dp[i].appendChild(sipka);
                dp[i].appendChild(clearDiv);


                var pole = data[i];
                var cena = pole['c'];
                var percentChange = pole['dp'];

                if (percentChange > 0) {
                    sipka.classList.remove("fa-arrow-trend-down");
                    sipka.classList.add("fa-arrow-trend-up");
                    icko.classList.remove("down");
                    icko.classList.add("up");
                    cisla[i].classList.add('green');
                    cisla[i].classList.remove('red');
                    dp[i].classList.remove("red");
                    dp[i].classList.add("green");
                }
                else if (percentChange < 0) {
                    sipka.classList.remove("fa-arrow-trend-up");
                    sipka.classList.add("fa-arrow-trend-down");
                    cisla[i].classList.remove('green');
                    cisla[i].classList.add('red');
                    icko.classList.add("down");
                    icko.classList.remove("up");
                    dp[i].classList.remove("green");
                    dp[i].classList.add("red");
                }

                document.getElementsByClassName("cena")[i].innerHTML = cena + "$";
                document.getElementsByClassName("percent")[i].innerHTML = percentChange + "%";
            }

        }).catch(function (error) {
            console.log(error);
        });
    }(), 60000);

}

update();

