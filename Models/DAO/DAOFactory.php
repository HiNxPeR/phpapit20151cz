<?php

namespace Models\DAO;

use \Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;
use \Config\Conf;

class DAOFactory {

    private static $instance;  

    private function __construct() {
        $this->em = $this->getEntityManager();
    }
    
    public static function getInstance() {
        if (self::$instance == null) { 
            self::$instance = new DAOFactory(); 
        }
        return self::$instance;
    }
    
    private function getEntityManager(){
        $paths = array(Conf::getRootDir().Conf::getDcotrineMappingsPath());
        $isDevMode = true;

        // the connection configuration
        $dbParams = array(
            'driver'   => Conf::getDBDriver(),
            'user'     => Conf::getDBUser(),
            'password' => Conf::getDBPassword(),
            'dbname'   => Conf::getDBName(),
            'host'     => Conf::getDBHost(),
            'port'     => Conf::getDBPort(),
        );

        $configDoctrine = Setup::createXMLMetadataConfiguration($paths, $isDevMode);
        return EntityManager::create($dbParams, $configDoctrine);
    }
      
    public function getUserDAO(){
        return new UserDAO('Models\User', self::$instance->em);
    }     
    
    public function getMovieDAO(){
        return new MovieDAO('Models\Movie', self::$instance->em);
    }   
    
}