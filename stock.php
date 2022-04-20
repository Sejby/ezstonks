<?php
require "./header.php";

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.stock.min.js"></script>
    <script src="https://cdn.plot.ly/plotly-2.11.1.min.js"></script>
    <link rel="stylesheet" href="./css/stock-style.css">
    <title>ezstonks - Graf</title>
</head>

<body>
    <div id="container">
        <div id="text">
            <h1>
                <?php
                switch ($_GET["company"]) {
                    case 'GOOGL':
                        echo 'Google (NASDAQ: GOOGL)';
                        break;
                    case 'NFLX':
                        echo 'Netflix (NASDAQ: NFLX)';
                        break;
                    case 'AMZN':
                        echo 'Amazon (NASDAQ: AMZN)';
                        break;
                    case 'FB':
                        echo 'Meta Inc. (NASDAQ: FB)';
                        break;
                    case 'AAPL':
                        echo 'Apple (NASDAQ: AAPL)';
                        break;
                    case 'TSLA':
                        echo 'Tesla (NASDAQ: TSLA)';
                        break;
                }
                ?>
            </h1>
            <h3 id="cena">---</h3>
        </div>
        <div id="graf"></div>
    </div>
    <script src="./js/graf.js"></script>
</body>

</html>

<?php
require "./footer.php";
?>