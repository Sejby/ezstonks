<?php

class SignupControl extends Signup
{
    private $uid;
    private $pwd;
    private $pwdrepeat;
    private $email;

    public function __construct($uid, $pwd, $pwdrepeat, $email)
    {
        $this->uid = $uid;
        $this->pwd = $pwd;
        $this->pwdrepeat = $pwdrepeat;
        $this->email = $email;
    }

    public function signupUser()
    {
        if ($this->emptyInput() == true) {
            header("location: /ooppro/index.php?error=emptyinput");
            exit();
        }
        if ($this->invalidUid() == true) {
            header("location: /ooppro/index.php?error=invalidusername");
            exit();
        }
        if ($this->invalidEmail() == true) {
            header("location: /ooppro/index.php?error=invalidemail");
            exit();
        }
        if ($this->usernameTaken() == true) {
            header("location: /ooppro/index.php?error=usernamealreadytaken");
            exit();
        }
        if ($this->passwordMatch() == false) {
            header("location: /ooppro/index.php?error=passwordsnotmatching");
            exit();
        }

        $this->setUser($this->uid, $this->pwd, $this->email);
    }

    private function emptyInput()
    {
        if (empty($this->uid) || empty($this->pwd) || empty($this->pwdrepeat) || empty($this->email)) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    private function invalidUid()
    {
        if (preg_match("/^[a-zA-Z0-9]*$/", $this->uid)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    private function invalidEmail()
    {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    private function passwordMatch()
    {
        if ($this->pwd == $this->pwdrepeat) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    private function usernameTaken()
    {
        if ($this->checkUser($this->uid, $this->email)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}
