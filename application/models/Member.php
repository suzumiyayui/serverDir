<?php

class Member extends CI_Model {
    
    
    public $memberId;
    public $memberUIDStr;



    public function __construct() {
        
        parent::__construct();
        
        
        
    }
    
    public function login($username,$password) {
        
        
        if($username == "admin" && $password == "admin"){
            
            
            $this->memberId = "1";
        
            $this->memberUIDStr = "MEMBERDIR".$this->memberId;
            
        }

        
        return $this;
    }
    
    
    
    
    
    
    
    
    
    
    
}