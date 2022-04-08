<?php
require "header.php";
?>

<head>
    <link rel="icon" href="img/favicon.ico" type="image/ico">
    <link rel="stylesheet" href="css/main-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="emojilib/css/emoji.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Jost&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/0639910d01.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.plot.ly/plotly-2.11.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
</head>

<body>
    <div id="stocks">
        <div class="stockDiv">
            <a href="./stock.php?company=GOOGL" class="link" target="_blank">
                <p>GOOGL - Google|USD</p>
                <div class="currency">
                    <h3 class="cena">---</h3>
                </div>
                <div class="dcchange">
                </div>
            </a>
        </div>
        <div class="stockDiv">
            <a href="./stock.php?company=NFLX" class="link" target="_blank">
                <p>NFLX - Netflix|USD</p>
                <div class="currency">
                    <h3 class="cena">---</h3>
                </div>
                <div class="dcchange">
                </div>
            </a>
        </div>
        <div class="stockDiv">
            <a href="./stock.php?company=AMZN" class="link" target="_blank">
                <p>AMZN - Amazon|USD</p>
                <div class="currency">
                    <h3 class="cena">---</h3>
                </div>
                <div class="dcchange">
                </div>
            </a>
        </div>
        <div class="stockDiv">
            <a href="./stock.php?company=FB" class="link" target="_blank">
                <p>FB - Meta|USD</p>
                <div class="currency">
                    <h3 class="cena">---</h3>
                </div>
                <div class="dcchange">
                </div>
            </a>
        </div>
        <div class="stockDiv">
            <a href="./stock.php?company=AAPL" class="link" target="_blank">
                <p>AAPL - Apple|USD</p>
                <div class="currency">
                    <h3 class="cena">---</h3>
                </div>
                <div class="dcchange">
                </div>
            </a>
        </div>
        <div class="stockDiv">
            <a href="./stock.php?company=TSLA" class="link" target="_blank">
                <p>TSLA - Tesla|USD</p>
                <div class="currency">
                    <h3 class="cena"></i>---</h3>
                </div>
                <div class="dcchange">
                </div>
            </a>
        </div>

    </div>

    <div class="ankety">
        <div class="anketa">
            <?php
            echo '<h3>Do you think the stock market will go up in 2022?</h3>
              <div class="progress">
                  <form action="php/includes/anketa.inc.php">
                      <button type="submit" name="anketa-submit">
                          <div class="progress-bar progress-bar-striped bg-success" id="yespolly" role="progressbar" style="width: 22.3%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Yes</div>
                      </button>
                  </form>
                  </div>
              <div class="progress" style="height: 32px;">
                  <div class="progress-bar progress-bar-striped bg-success" id="nopolly" role="progressbar" style="width: 72.4%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">No</div>
              </div>';
            ?>
        </div>

        <div class="anketa">
            <?php
            echo '<h3>Do you think the stock market will go up in 2022?</h3>
              <div class="progress">
                  <form action="php/includes/anketa.inc.php">
                      <button type="submit" name="anketa-submit">
                          <div class="progress-bar progress-bar-striped bg-success" id="yespolly" role="progressbar" style="width: 22.3%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Yes</div>
                      </button>
                  </form>
                  </div>
              <div class="progress">
                  <div class="progress-bar progress-bar-striped bg-success" id="nopolly" role="progressbar" style="width: 72.4%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">No</div>
              </div>';
            ?>
        </div>
    </div>

    <div id="content">
        <?php

        if (isset($_SESSION['userId'])) {

            echo '<div id="pridaniPrispevkuTlacitko">
            <h3 id="pridejsvujnapad">What is on your mind?</h3><a href="addpost.php"><button class="btn btn-success" id="tlacitkopridani">Add Post</button></a>
            </div>';
        } else {
            echo '<div id="pridaniPrispevku">
            <h3 id="info-warning">You must first log in or <a href="signup.php">register</a> to add a post.</h3>
            </div>
            ';
        }

        include "./php/models/API.php";
        ?>

    </div>

    <div id="posts">

        <?php
        $mysqli = new mysqli('localhost', 'root', '', 'ezstonks');
        $sql = "SELECT idPosts, idUsers, topic, postText, date ,likes FROM posty ORDER BY date DESC;";
        if ($stmt = $mysqli->prepare($sql)) {
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($id, $date, $user, $topic, $text, $likes);
                    while ($row = $stmt->fetch()) {

                        echo '<div class="prispevek">';
                        echo '<div class="content">';
                        if (isset($_SESSION['userUId'])) {
                            if ($user == $_SESSION['userUId']) {
                                echo '
                        <div id="formystlacitkama">
                        <form method="post" action="changepost.php" id="formsbuttonama1">
                        <button type="submit" name="zmenitPost" value=' . $id . ' class="btn btn-warning btn-sm" id="meniciTlacitko">Změnit</button>
                        </form>
                        
                        <form method="post" id="formsbuttonama2">
                        <button type="submit" name="odstranitPost" value=' . $id . ' class="btn btn-danger btn-sm" id="odstranovaciTlacitko">Odstranit příspěvek</button>
                        </form>
                        </div>  
                        ';
                            }
                        }
                        echo '<h3 class="jmenouzivatele">' . $topic . '</h3>
                        <p class="textuzivatele">' . $text . '</p>          
                        <h3 class="datum">Přidáno: ' . $date . '</h3>
                        <h3 class="autor">Autor: ' . $user . '</h3>
                        </div>
                        </div>';
                    }
                }
            }
        } else {
            echo $mysqli->error;
        }

        if (isset($_POST['odstranitPost'])) {
            $sql = "DELETE FROM posty WHERE idPosts =" . $_POST['odstranitPost'] . " ";
            $res = $mysqli->query($sql);
            $page = $_SERVER['PHP_SELF'];
            echo '<meta http-equiv="Refresh" content="0;' . $page . '">';
        }
        ?>


    </div>

    <div id="chat">
        <div id="users" style="display: none;">
            <div class="user">USER 1</div>
        </div>
        <p>Online messages</p>
    </div>


    <script src="emojilib/js/config.js"></script>
    <script src="emojilib/js/util.js"></script>
    <script src="emojilib/js/jquery.emojiarea.js"></script>
    <script src="emojilib/js/emoji-picker.js"></script>
    <script src="js/jquery.timeago.js"></script>
    <script src="js/API.js"></script>
    <script type="text/javascript">
        var isReply = false,
            commentID = 0,
            max = <?php echo $numComments ?>;
        var idNews = $(".hiddenval").attr('value');


        $(document).ready(function() {
            $("#addComment, #addReply").on('click', function() {
                var comment;

                if (!isReply)
                    comment = $("#mainComment").val();
                else
                    comment = $("#replyComment").val();

                if (comment.length > 3) {
                    $.ajax({
                        url: 'index.php',
                        method: 'POST',
                        dataType: 'text',
                        data: {
                            addComment: 1,
                            comment: comment,
                            isReply: isReply,
                            commentID: commentID,
                            idNews: idNews
                        },
                        success: function(response) {
                            max++;
                            $("#numComments").text(max + " Comments");

                            if (!isReply) {
                                $(".userComments").prepend(response);
                                $("#mainComment").val("");
                            } else {
                                commentID = 0;
                                $("#replyComment").val("");
                                $(".replyRow").hide();
                                $('.replyRow').parent().next().prepend(response);
                            }

                            calcTimeAgo();
                        }
                    });
                } else
                    alert('Error: Check your input!');
            });
            getAllComments(0, max);
            getAllUserReactions();
        });

        function getAllUserReactions() {
            $.ajax({
                url: 'index.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    getUserReactions: 1
                },
                success: function(response) {
                    for (var i = 0; i < response.length; i++) {
                        $('i[onclick="react(this,' + response[i].commentID + ', \'' + response[i].type + '\')"]').each(function() {
                            if (response[i].isReply === $(this).attr('data-isReply'))
                                $(this).css('color', 'blue');
                        });
                    }
                }
            });
        }

        function reply(caller) {
            commentID = $(caller).attr('data-commentID');
            $(".replyRow").insertAfter($(caller));
            $('.replyRow').show();
        }

        function calcTimeAgo() {
            $('.timeago').each(function() {
                var timeAgo = $.timeago($(this).attr('data-date'));
                $(this).text(timeAgo);
                $(this).removeClass('timeago');
            });
        }

        function react(caller, commentID, type) {
            $.ajax({
                url: 'index.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    react: 1,
                    commentID: commentID,
                    type: type,
                    isReply: $(caller).attr('data-isReply')
                },
                success: function(response) {
                    if (response.status === 'updated') {
                        if (type === 'up')
                            $(caller).next().css('color', '');
                        else
                            $(caller).prev().css('color', '');
                    }

                    $(caller).css('color', 'blue');
                }
            });
        }

        function getAllComments(start, max) {
            if (start > max) {
                calcTimeAgo();
                return;
            }

            $.ajax({
                url: 'index.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    getAllComments: 1,
                    start: start
                },
                success: function(response) {
                    $(".userComments").append(response);
                    getAllComments((start + 20), max);
                }
            });
        }

        $(function() {
            window.emojiPicker = new EmojiPicker({
                emojiable_selector: '[data-emojiable=true]',
                assetsPath: 'emojilib/img/',
                popupButtonClasses: 'fa fa-smile-o'
            });
            window.emojiPicker.discover();
        });
    </script>
</body>

<?php
require "footer.php";
?>