<?php

class AddPost extends DBH
{
    protected function setPost($user, $tema, $text)
    {
        $stmt = $this->connect()->prepare("INSERT INTO posty (idUsers, topic, postText) VALUES (?,?,?);");
        if (!$stmt->execute(array($user, $tema, $text))) {
            $stmt = null;
            header("location: /ooppro/index.php?error=stmtfailed");
            exit();
        }
        $stmt = null;
    }
}
