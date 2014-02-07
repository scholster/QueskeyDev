<?php

namespace Queskey\FrontEndBundle\Model;

class UserLogin {
    
    private $id;
    private $name;
    private $email;
    
    
    function __construct($id, $name, $email ) {
        $this->name = $name;
        $this->email = $email;
        $this->id = $id;
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


}

?>
