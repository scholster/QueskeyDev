<?php

namespace Queskey\FrontEndBundle\Model;

class UserLogin {
    
    private $id;
    private $name;
    private $email;
    private $admin;
    
    
    function __construct($id, $name, $email, $admin ) {
        $this->name = $name;
        $this->email = $email;
        $this->id = $id;
        $this->admin = $admin;
    }

    
    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getId() {
        return $this->id;
    }
    
    public function getAdmin() {
        return $this->admin;
    }


}

?>
