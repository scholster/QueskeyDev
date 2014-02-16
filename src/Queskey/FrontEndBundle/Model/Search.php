<?php

namespace Queskey\FrontEndBundle\Model;

class Search {
    
    private $id;
    private $name;
    private $image;
    private $provider;
    private $providerimg;
    private $price;
            
    
    function __construct($id, $name, $image, $provider, $providerimg, $price) {
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->provider = $provider;
        $this->providerimg = $providerimg;
        $this->price = $price;
    }

    
    public function getName() {
        return $this->name;
    }

    public function getImage() {
        return $this->image;
    }

    public function getId() {
        return $this->id;
    }
    
    public function getProvider() {
        return $this->provider;
    }
    
    public function getProviderImg() {
        return $this->providerimg;
    }
    
    public function getPrice() {
        return $this->price;
    }


}

?>
