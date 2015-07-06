<?php

namespace Models;

class User
{
    private $id;
    private $login;
    private $name;
    private $password;

    /**
     * Setter para propiedad login
     * @param string $login login del usuario
     * @return User
     */
    public function setLogin($login){
        $this->login = $login;
        return $this;
    }
    
    /**
     * Devuelve el login del usuario
     * @return string
     */
    public function getLogin(){
        return $this->login;
    }

    /**
     * Setter para propiedad name
     * @param string $name nombre del usuario
     * @return User
     */
    public function setName($name){
        $this->name = $name;
        return $this;
    }
    
    /**
     * Devuelve el nobmre del usuario
     * @return string
     */
    public function getName(){
        return $this->name;
    }
    
    /**
     * Setter para propiedad password. Utiliza md5
     * @param string $password password del usuario
     * @return User
     */
    public function setPassword($name){
        $this->password = md5($name);
        return $this;
    }
    
    /**
     * Devuelve el password del usuario encriptado con md5
     * @return string
     */
    public function getPassword(){
        return $this->password;
    }
    
}