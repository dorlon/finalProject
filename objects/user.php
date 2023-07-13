<?php
Class User{
    protected $user_id;
    protected $full_name;
    protected $email;
    protected $password;
    protected $mobile;
    protected $city;
    protected $street;
    protected $house_number; 
    protected $user_picture;
    protected $user_type;
    protected $pass_not_hash;

    public function getDetailsOfUser($user_id) {
        $db = new dbClass();
        return $db->getDetailsOfUser($user_id);
    }

    public function getUser_id()
    {
        return $this->user_id;
    } 

    public function getFull_name()
    {
        return $this->full_name;
    }    

    public function setFull_name($newFull_name)
    {
        $this->full_name=$newFull_name;
    }

    public function getEmail()
    {
        return $this->email;
    } 

    public function setEmail($newEmail)
    {
        $this->email=$newEmail;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($newPassword)
    {
        $this->pass_not_hash = $newPassword;
        $this->password= password_hash($newPassword, PASSWORD_DEFAULT);
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setMobile($newMobile)
    {
        $this->mobile=$newMobile;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($newCity)
    {
        $this->city=$newCity;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet($newStreet)
    {
        $this->street=$newStreet;
    }

    public function getHouse_number()
    {
        return $this->house_number;
    }

    public function setHouse_number($newHouse_number)
    {
        $this->house_number=$newHouse_number;
    }

    public function getUser_picture()
    {
        return $this->user_picture;
    }

    public function setUser_picture($user_picture)
    {
        $this->user_picture=$newUser_picture;
    }

    public function getUser_type()
    {
        if($this->user_type === null)
        {
            return "0";
        }
        return $this->user_type;
    }

    public function setUser_type()
    {
        $this->user_type=$newUser_type;
    }
}

?>