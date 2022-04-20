<?php
class Signup extends DBH
{
    protected function setUser($uid, $pwd, $email)
    {
        $stmt = $this->connect()->prepare("INSERT INTO users (uidUsers, pwdUsers, emailUsers) VALUES (?,?,?);");
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
        if (!$stmt->execute(array($uid, $hashedPwd, $email))) {
            $stmt = null;
            header("location: /ooppro/index.php?error=stmtfailed");
            exit();
        }

        $stmt = null;
    }

    protected function checkUser($uid, $email)
    {
        $stmt = $this->connect()->prepare("SELECT uidUsers FROM users WHERE uidUsers= ? OR emailUsers = ?;");
        if (!$stmt->execute(array($uid, $email))) {
            $stmt = null;
            header("location: /ooppro/index.php?error=stmtfailed");
            exit();
        }

        if ($stmt->rowCount() > 0) {
            $resultCheck = false;
        } else {
            $resultCheck = true;
        }
        return $resultCheck;
    }
}
