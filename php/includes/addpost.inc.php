<?php
session_start();

if (isset($_POST["addpost-submit"])) {

    $tema = $_POST['tema'];
    $text = $_POST['text'];
    $user = $_SESSION["userId"];

    include "../classes/dbh.class.php";
    include "../classes/addpost.class.php";
    include "../controls/addpost.control.php";
    $addpost = new AddPostControl($tema, $text, $user);

    $addpost->getPost();

    header("Location: /ooppro/index.php?addpost=success");
    exit();
}
