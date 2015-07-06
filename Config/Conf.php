<?php

namespace Config;

class Conf {
    
    static private $instance = null;
    
    private $conf = null;
    
    private function __contruct() {}
    
    public static function getInstance() {
        if (self::$instance == null) {                    
            self::$instance = new Conf();
            self::$instance->conf = parse_ini_file('config.ini',true);
        }
        return self::$instance;
    }
    
    public static function getValue($section,$param){           
        return self::getInstance()->conf [$section][$param];
    }
    
    public static function setValue($section,$param, $value){           
        return self::getInstance()->conf [$section][$param] = $value;
    }
    
    //General
    public static function getRootDir(){
        return realpath('.');
    }       
    
    //Data Base
    public static function getDBDriver(){
        return self::getValue('db','driver');
    }
    
    public static function getDBUser(){
        return self::getValue('db','user');
    }
    
    public static function getDBPassword(){
        return self::getValue('db','password');
    }
    
    public static function getDBName(){
        return self::getValue('db','dbname');
    }
    
    public static function getDBHost(){
        return self::getValue('db','host');
    }
    
    public static function getDBPort(){
        return self::getValue('db','port');
    }
    
    //Doctrine
    public static function getDcotrineMappingsPath(){
        return self::getValue('doctrine','mappingsPath');
    }    
}