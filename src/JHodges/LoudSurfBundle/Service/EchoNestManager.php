<?php
namespace JHodges\LoudSurfBundle\Service;

use Echonest\Service\Echonest;

class EchoNestManager{

    public function __construct($apiKey){
        Echonest::configure($apiKey);
    }

    public function query($name,$method,$data){
        return Echonest::query($name,$method,$data);
    }

}