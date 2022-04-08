<?php

if (isset($_POST["login-submit"])) {

    $uid = $_POST['mailuid'];
    $pwd = $_POST['pwd'];

    include "../models/dbh.class.php";
    include "../models/login.class.php";
    include "../controllers/login.control.php";
    $login = new LoginControl($uid, $pwd);

    $login->loginUser();

    header("Location: /ooppro/index.php?login=success");
    exit();
}
