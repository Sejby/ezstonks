<?php
session_start();

if (isset($_POST["addpost-submit"])) {

    $tema = $_POST['tema'];
    $text = $_POST['text'];
    $user = $_SESSION["userId"];

    include "../models/dbh.class.php";
    include "../models/addpost.class.php";
    include "../controllers/addpost.control.php";
    $addpost = new AddPostControl($tema, $text, $user);

    $addpost->getPost();

    header("Location: /ooppro/index.php?addpost=success");
    exit();
}
