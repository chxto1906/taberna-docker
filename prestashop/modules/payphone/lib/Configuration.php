<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ConfigurationManager{
    
    private static $instance;
    
    private function __construct() {}
    
    /**
     * 
     * @return \ConfigurationManager
     */
    public static function Instance(){
        if (!isset(self::$instance)){
            $myClass = __CLASS__;
            self::$instance = new $myClass;
        }
        return self::$instance;
    }
    
    public function __clone() {
        trigger_error("La clonacion de este objeto no esta permitida");
    }
   
    public $Token = null;
    public $ApiPath = "https://pay.payphonetodoesposible.com/";
    public $Lang=null;
}