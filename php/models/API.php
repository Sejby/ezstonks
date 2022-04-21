<?php

include "dbh.class.php";

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://finnhub.io/api/v1/news?category=general&token=c74iekaad3iboasscsig',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);
$pole = json_decode($response);
curl_close($curl);

class API extends DBH
{

  public function sendNewsToDBH($pole)
  {
    for ($i = 0; $i < 4; $i++) {
      $id = $pole[$i]->id;
      $nadpis = $pole[$i]->headline;
      $text = $pole[$i]->summary;
      $img_url = $pole[$i]->image;
      $category = $pole[$i]->category;
      $date = $pole[$i]->datetime;
      $stmt = $this->connect()->prepare("INSERT INTO news (id, nadpis, text, img_url, category, date) VALUES (?,?,?,?,?,?);");
      try {
        if (!$stmt->execute(array($id, $nadpis, $text, $img_url, $category, $date))) {
          $stmt = null;
          header("location: /ooppro/index.php?error=stmtfailed");
          exit();
        }
      } catch (PDOException $e) {
      }
      $stmt = null;
    }
  }

  public function getNewsFromDBH()
  {
    $stmt = $this->connect()->prepare("SELECT * from news ORDER BY date DESC");
    if (!$stmt->execute()) {
      $stmt = null;
      header("location: /ooppro/index.php?error=stmtfailed");
      exit();
    }
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    for ($i = 0; $i < 4; $i++) {

      $time =  date("h:ia d.m.Y", date($data[$i]["date"]));

      echo '
      <div class="zprava">
        <div class="obsah">
          <div class="wrapper">
            <span class="label label-info">' . $data[$i]["category"] . '</span>
            <h1>' . $data[$i]["nadpis"] . '</h1>
            <img src="' . $data[$i]["img_url"] . '">
            <p class="text">' . $data[$i]["text"] . '</p>
            <p class="datum">Added on: ' . $time . '</p>
          </div>
        </div>
        <div class="commentSection">
         <div class="show">
        <div
        class="clickToShow"
        data-id="' . $data[$i]['id'] . '">
        <form action="./diskuze.php" method="post">
        <a href="./diskuze.php?id=' . $data[$i]['id'] . '&headline= ' . $data[$i]['nadpis'] . '"><i class="fa-regular fa-comment"></i> Show discussion </a>
        <form>
      </div>
    </div>
    <input type="hidden" value="' . $data[$i]['id'] . '" class="hiddenval" />
  </div>
</div>
';
    }
    $stmt = null;
  }
}



$API = new API();
$API->sendNewsToDBH($pole);
$API->getNewsFromDBH();



// echo '<h1>' . $pole[0]->headline . '</h1>';