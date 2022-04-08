<?php
class Login extends DBH
{
    protected function getUser($uid, $pwd)
    {
        $stmt = $this->connect()->prepare("SELECT pwdUsers FROM users WHERE uidUsers=? OR emailUsers=?;");
        if (!$stmt->execute(array($uid, $pwd))) {
            $stmt = null;
            header("location: /ooppro/index.php?error=stmtfailed");
            exit();
        }

        if ($stmt->rowCount() == 0) {
            $stmt = null;
            header("location: /ooppro/index.php?error=usernotfound");
            exit();
        }

        $hashedPwd = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPwd = password_verify($pwd, $hashedPwd[0]["pwdUsers"]);

        if ($checkPwd == false) {
            $stmt = null;
            header("location: /ooppro/index.php?error=wrongpassword");
            exit();
        } else if ($checkPwd == true) {
            $stmt = $this->connect()->prepare("SELECT * FROM users WHERE uidUsers= ? OR emailUsers = ? AND pwdUsers = ?;");
            if (!$stmt->execute(array($uid, $uid, $pwd))) {
                $stmt = null;
                header("location: /ooppro/index.php?error=stmtfailed");
                exit();
            }

            if ($stmt->rowCount() == 0) {
                $stmt = null;
                header("location: /ooppro/index.php?error=usernotfound");
                exit();
            }

            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            session_start();
            $_SESSION["userId"] = $user[0]["idUsers"];
            $_SESSION["userUId"] = $user[0]["uidUsers"];

            $stmt = null;
        }

        $stmt = null;
    }
}
