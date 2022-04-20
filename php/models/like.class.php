<?php

class AddPost extends DBH
{
    protected function setLike($user, $idPost)
    {
        $stmt = $this->connect()->prepare("INSERT INTO posty (likes:=likes+1) WHERE idUsers = (?) VALUES (?);");
        if (!$stmt->execute(array($user, $idPost))) {
            $stmt = null;
            header("location: /ooppro/index.php?error=stmtfailed");
            exit();
        }
        $stmt = null;
    }
}
