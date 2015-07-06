<?php

/**
 * Controller para usuarios
 */

namespace Controllers;

use Models\DAO\DAOFactory;
use Models\User;
use Tonic\NotFoundException;

class UserController {
    
    /**
     * Valida que el usuario y la contraseña sean validas.
     * @param string $login login del usuario
     * @param string $password password del usuario
     * @return bool
     * @throw NotFoundException en caso de que el usuario no exista.
     */
    public function isValid($login, $password){
         $userDAO = DAOFactory::getInstance()->getUserDAO();
         $user = $userDAO->findByLogin($login);
         if(empty($user)){
            throw new NotFoundException('Usuario inexistente');
        }
        
        return md5($password)==$user->getPassword();
    }
    
}