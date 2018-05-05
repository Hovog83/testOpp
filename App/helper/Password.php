<?php

namespace App\helper;

class Password{
 
    protected $password;
    protected $_passwords = [];

    public function __construct($start){
        $this->password = $start;
    }
    public function getNextPassword(){
        if(empty($this->_passwords)){
            echo ".";
            return $this->password++;
        }else{
            return array_shift($this->_passwords);
        }
    }
    public function addFailedPassword($password){
        $this->_passwords[] = $password;
    }
}