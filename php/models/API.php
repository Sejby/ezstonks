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
      $datetime = date("Y-m-d H:i:s", $date * 1000);
      $stmt = $this->connect()->prepare("INSERT INTO news (id, nadpis, text, img_url, category, date) VALUES (?,?,?,?,?,?);");
      try {
        if (!$stmt->execute(array($id, $nadpis, $text, $img_url, $category, $datetime))) {
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
    $stmt = $this->connect()->prepare("SELECT * from news");
    if (!$stmt->execute()) {
      $stmt = null;
      header("location: /ooppro/index.php?error=stmtfailed");
      exit();
    }
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $time = date("d/m/Y H:i", strtotime($data[0]["date"]));
    echo '
        <div class="zprava">
          <div class="obsah">
            <div class="wrapper">
            <span class="label label-info">' . $data[0]["category"] . '</span>
              <h1>' . $data[0]["nadpis"] . '</h1>
              <img src="' . $data[0]["img_url"] . '">
              <p class="text">' . $data[0]["text"] . '</p>
              <p class="datum">Added on: ' . $time . '</p>
            </div>
          </div>
          <div class="commentSection">
          <div class="show"><div class="clickToShow"><i class="fa-regular fa-comment"></i> Show discussion</div></div>   
          <input type="hidden" value="' . $data[0]['id'] . '" class="hiddenval">        
          <div class="comments">
          <div class="row">
              <div class="col-md-12">
                  <textarea class="form-control" id="mainComment" placeholder="Add Comment..." cols="30" rows="2" data-emojiable="true" data-emoji-input="unicode"></textarea>
                  <button style="float:right; margin-top: 10px;" class="btn-primary btn" onclick="isReply = false;" id="addComment">Add Comment</button>
              </div>

              <div class="col-md-12">
                  <div class="userComments">
                  </div>
                  <div class="row replyRow" style="display:none">
          <div class="col-md-12">
              <textarea class="form-control" id="replyComment" placeholder="Add Public Comment" cols="30" rows="2" data-emojiable="true" data-emoji-input="unicode"></textarea>
              <button style="float:right; margin-left: 5px;" class="btn-success btn" onclick="isReply = true;" id="addReply">Add Reply</button>
              <button style="float:right" class="btn-default btn" onclick="closeFunction()" > Close </button>
          </div>
      </div>
              </div>
          </div>
      </div>
          </div>
        </div>';
    $stmt = null;
  }

  public function vypisKomentare()
  {
    /* echo '
        <div class="row">
          <div class="col-md-12">
            <h2><b id="numComments">'. $numComments .' Comments</b></h2>
              <div class="userComments">
              </div>
          </div>
        </div>';
        */
  }
}



$API = new API();
$API->sendNewsToDBH($pole);
$API->getNewsFromDBH();



// echo '<h1>' . $pole[0]->headline . '</h1>';