<?php
    if (isset($_POST["signup-submit"])) {

        $uid = $_POST["uid"];
        $pwd = $_POST["pwd"];
        $pwdrepeat = $_POST["pwd-repeat"];
        $email = $_POST["mail"];

        include "../models/dbh.class.php";
        include "../models/signup.class.php";
        include "../controllers/signup.control.php";
        $signup = new SignupControl($uid, $pwd, $pwdrepeat, $email);

        $signup->signupUser();

        header("Location: /ooppro/index.php?signup=success");
        exit();

    }
