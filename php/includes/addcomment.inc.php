<?php

$loggedIn = false;

if (isset($_SESSION['userId']) || isset($_SESSION['userUId'])) {
    $loggedIn = true;
}

$conn = new mysqli('localhost', 'root', '', 'ezstonks');

function createCommentRow($data, $isReply = false)
{
    global $conn;

    if ($isReply)
        $isReply = 'yes';
    else
        $isReply = 'no';

    $response = '
            <div id="comment_' . $data['id'] . '" class="comment">
                <div class="user">' . $data['uidUsers'] . ' <span class="time timeago" data-date="' . $data['createdOn'] . '"></span></div>
                <div class="userComment">' . $data['comment'] . '</div>
                <div class="reply">
                    <i class="fas fa-thumbs-up" data-isReply="' . $isReply . '" onclick="react(this,' . $data['id'] . ', \'up\')"></i>
                    <i class="fas fa-thumbs-down" data-isReply="' . $isReply . '" onclick="react(this,' . $data['id'] . ', \'down\')"></i>
                    <a href="javascript:void(0)" data-commentID="' . $data['id'] . '" onclick="reply(this)">REPLY</a>
                </div>
                <div class="replies">';

    $sql = $conn->query("SELECT replies.id, uidUsers, comment, replies.createdOn FROM replies INNER JOIN users ON replies.userID = users.idUsers WHERE replies.commentID = '" . $data['id'] . "' ORDER BY replies.id DESC LIMIT 1");
    while ($dataR = $sql->fetch_assoc())
        $response .= createCommentRow($dataR, true);

    $response .= '
                </div>
            </div>
        ';

    return $response;
}

if (isset($_POST['getUserReactions'])) {
    $reactions = [];
    $sql = $conn->query("SELECT commentID, type, isReply FROM reactions WHERE user.id =  ${$_SESSION['userId']}");
    while ($data = $sql->fetch_assoc())
        $reactions[] = array("commentID" => $data['commentID'], "type" => $data['type'], "isReply" => $data['isReply']);

    exit(json_encode($reactions));
}

if (isset($_POST['getAllComments'])) {
    $start = $conn->real_escape_string($_POST['start']);

    $response = "";
    $sql = $conn->query("SELECT comments.id, uidUsers, comment, comments.createdOn FROM comments INNER JOIN users ON comments.userID = users.idUsers ORDER BY comments.id DESC LIMIT $start, 20");
    while ($data = $sql->fetch_assoc())
        $response .= createCommentRow($data);

    exit($response);
}

if (isset($_POST['react'])) {
    if ($loggedIn == true) {
        $commentID = $conn->real_escape_string($_POST['commentID']);
        $type = $conn->real_escape_string($_POST['type']);
        $isReply = $conn->real_escape_string($_POST['isReply']);

        $sql = $conn->query("SELECT id FROM reactions WHERE commentID='$commentID' && userID='" . $_SESSION['userId'] . "'");
        if ($sql->num_rows > 0) {
            $status = "updated";
            $conn->query("UPDATE reactions SET type='$type' WHERE commentID='$commentID' && userID='" . $_SESSION['userId'] . "'");
        } else {
            $status = "inserted";
            $conn->query("INSERT INTO reactions (isReply,type,commentID, userID) VALUES ('$isReply','$type', '$commentID', '" . $_SESSION['userId'] . "')");
        }

        exit(json_encode(array('status' => $status)));
    } else {
        echo '<script type=text/javascript>alert("You must first register or login!")</script>';
        exit();
    }
}

if (isset($_POST['addComment'])) {
    if ($loggedIn == true) {
        $comment = $conn->real_escape_string($_POST['comment']);
        $isReply = $conn->real_escape_string($_POST['isReply']);
        $commentID = $conn->real_escape_string($_POST['commentID']);
        


        if ($isReply != 'false') {
            $conn->query("INSERT INTO replies (comment, commentID, userID, createdOn) VALUES ('$comment', '$commentID', '" . $_SESSION['userId'] . "', NOW())");
            $sql = $conn->query("SELECT replies.id, uidUsers, comment, replies.createdOn FROM replies INNER JOIN users ON replies.userID = users.idUsers ORDER BY replies.id DESC LIMIT 1");
        } else {
            $conn->query("INSERT INTO comments (userID, comment, createdOn, idNews) VALUES ('" . $_SESSION['userId'] . "','$comment',NOW(), $idZpravy)");
            $sql = $conn->query("SELECT comments.id, uidUsers, comment, comments.createdOn FROM comments INNER JOIN users ON comments.userID = users.idUsers ORDER BY comments.id DESC LIMIT 1");
        }

        $data = $sql->fetch_assoc();
        exit(createCommentRow($data));
    } else {
        echo '<script type=text/javascript>alert("You must first register or login!")</script>';
        exit();
    }
}

$sqlNumComments = $conn->query("SELECT id FROM comments");
$numComments = $sqlNumComments->num_rows;
