<?php

class LoginControl extends Login
{

    private $username;
    private $password;

    public function __construct($uid, $pwd)
    {
        $this->username = $uid;
        $this->password = $pwd;
    }

    public function loginUser()
    {
        if ($this->emptyInput() == true) {
            header("location: /ooppro/index.php?error=emptyinput");
            exit();
        }

        $this->getUser($this->username, $this->password);
    }

    private function emptyInput()
    {
        if (empty($this->username) || empty($this->password)) {
            $result = true;
        }
    }
}
