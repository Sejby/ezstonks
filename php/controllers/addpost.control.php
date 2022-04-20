<?php

class AddPostControl extends AddPost
{

    private $tema;
    private $text;
    private $user;
    private $username;

    public function __construct($tema, $text, $user, $username)
    {
        $this->tema = $tema;
        $this->text = $text;
        $this->user = $user;
        $this->username = $username;
    }

    public function getPost()
    {
        if ($this->emptyInput() == true) {
            header("location: /ooppro/index.php?error=emptyinput");
            exit();
        }

        $this->setPost($this->user, $this->username, $this->tema, $this->text);
    }

    private function emptyInput()
    {
        if (empty($this->tema) || empty($this->text)) {
            $result = true;
        }
    }
}
