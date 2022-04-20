<?php
session_start();

if (isset($_POST["addpost-submit"])) {

    $tema = $_POST['tema'];
    $text = $_POST['text'];
    $userId = $_SESSION["userId"];
    $username = $_SESSION["userUId"];

    include "../models/dbh.class.php";
    include "../models/addpost.class.php";
    include "../controllers/addpost.control.php";
    $addpost = new AddPostControl($tema, $text, $userId, $username);

    $addpost->getPost();

    header("Location: /ooppro/index.php?addpost=success");
    exit();
}
