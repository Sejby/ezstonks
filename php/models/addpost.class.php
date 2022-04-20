<?php

class AddPost extends DBH
{
    protected function setPost($user, $username, $tema, $text)
    {
        $stmt = $this->connect()->prepare("INSERT INTO posty (idUsers,uidUsers,topic, postText) VALUES (?,?,?,?);");
        if (!$stmt->execute(array($user, $username, $tema, $text))) {
            $stmt = null;
            header("location: /ooppro/index.php?error=stmtfailed");
            exit();
        }
        $stmt = null;
    }
}
