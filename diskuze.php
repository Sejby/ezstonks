<?php
require "./header.php";

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link href="emojilib/css/emoji.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/0639910d01.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/diskuze-style.css">
    <title>ezstonks - Discussion</title>
</head>

<body>
    <div id="hlaska">
        <?php
        if (isset($_SESSION['userId'])) {

            echo '<div id="pridaniPrispevkuTlacitko">
        <h3 id="pridejsvujnapad">Add your comment</h3>
    </div>';
        } else {
            echo '<div id="pridaniPrispevku">
        <h3 id="info-warning">You must first log in or <a href="signup.php">register</a> to add a post.</h3>
    </div>
    ';
        }
        ?>
    </div>
    <div id="text">
        <h1>Discussion: <?php $_GET["headline"] ?></h1>
        <h3 id="numComments"><?php echo $numComments ?> Comments</h3>
    </div>

    <div class="container" style="margin-top:50px;">
        <div class="row">
            <div class="col-md-12">
                <textarea class="form-control" id="mainComment" placeholder="Add Comment" data-emojiable="true" data-emoji-input="unicode" cols="30" rows="2"></textarea><br>
                <button style="float:right" class="btn-primary btn" onclick="isReply = false;" id="addComment">Add Comment</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="userComments">

                </div>
            </div>
        </div>
    </div>

    <div class="row replyRow" style="display:none">
        <div class="col-md-12">
            <textarea class="form-control" id="replyComment" placeholder="Add Public Comment" data-emojiable="true" data-emoji-input="unicode" cols="30" rows="2"></textarea><br>
            <button style="float:right" class="btn-primary btn" onclick="isReply = true;" id="addReply">Add Reply</button>
            <button style="float:right" class="btn-default btn" onclick="$('.replyRow').hide();">Close</button>
        </div>
    </div>




    <script src="emojilib/js/config.js"></script>
    <script src="emojilib/js/util.js"></script>
    <script src="emojilib/js/jquery.emojiarea.js"></script>
    <script src="emojilib/js/emoji-picker.js"></script>
    <script src="js/jquery.timeago.js"></script>
    <script type="text/javascript">
        var idNews = <?php echo $_GET["id"] ?>;
        getComments(0, max, idNews);
        getAllUserReactions();
        var isReply = false,
            commentID = 0,
            max = <?php echo $numComments ?>,
            userId = <?php if (isset($_SESSION["userId"])) {
                            echo $_SESSION["userId"];
                        } else {
                            echo 'null';
                        } ?>;

        console.log(idNews);

        $(document).ready(function() {

            $("#addComment, #addReply").on('click', function() {
                var comment;

                if (!isReply) {
                    comment = $("#mainComment").val();
                } else {
                    console.log('reply');
                    comment = $("#replyComment").val();
                }
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
                                $('.emoji-wysiwyg-editor').empty();
                            } else {
                                commentID = 0;
                                $("#replyComment").val("");
                                $(".replyRow").hide();
                                $('.emoji-wysiwyg-editor').empty();
                                $('.replyRow').parent().next().append(response);
                            }

                            calcTimeAgo();
                        }
                    });
                } else
                    alert('Error: Check your comment length!');
            });

            getAllUserReactions();
        });

        function removeComment(value) {
            $.ajax({
                url: 'index.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    removeComment: 1,
                    commentID: value,
                    userId: userId
                },
                success: function(response) {
                    max--;
                    window.location.reload();
                }
            });
        }

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
            console.log('klik');
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

        function getComments(start, max, idNews) {
            if (start > max) {
                calcTimeAgo();
                return;
            }

            $.ajax({
                url: 'index.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    getComments: 1,
                    start: start,
                    idZpravy: idNews
                },
                success: function(response) {
                    $(".userComments").append(response);
                    getComments((start + 20), max, idNews);
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

        function closeFunction() {
            $('.replyRow').hide();
        }


        function toggleComments(val) {
            var id = val.getAttribute('data-id');
            $('#' + id).toggle();
        }
    </script>
</body>

</html>

<?php
require "./footer.php";
?>