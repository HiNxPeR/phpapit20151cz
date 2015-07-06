<?php

namespace Resources;

use Tonic\Response;
use Tonic\UnauthorizedException;
use Tonic\NotFoundException;
use Controllers\UserController;

/**
 * Recurso que provee autenticación de usuarios utilizando Basic HTTP
 * "inspirado ;)" de http://php.net/manual/en/features.http-auth.php
 */
class AuthenticationResource extends AbstractBaseResource
{   
    public function __construct(\Tonic\Application $app, \Tonic\Request $request) {
        parent::__construct($app, $request);
        $this->logger = \Logger::getLogger("mylogger");
    }
    
    /**
     * Este método se ejecuta antes que cualquier otro método.
     * Valida que el usuario exista y que la contraseña sea la correcta.
     * @throws UnauthorizedException
     */
    public function setup()
    {
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
            isset($_SERVER['PHP_AUTH_USER']) &&  empty($_SERVER['PHP_AUTH_USER']) || 
            isset($_SERVER['PHP_AUTH_PW']) &&  empty($_SERVER['PHP_AUTH_PW'])){
            $this->logger->error("Intento de acceso sin usuario o contraseña");
            throw new UnauthorizedException("Usuario o contraseña incorrectos", 
                                                        Response::UNAUTHORIZED);
        }

        // valido usuario y contraseña
        $user = new UserController();
        try{
            if(!$user->isValid($_SERVER['PHP_AUTH_USER'], 
                                                    $_SERVER['PHP_AUTH_PW'])){
                $this->logger->error("Intento de acceso con usuario o contraseña incorrectos");
                throw new UnauthorizedException("Usuario o contraseña incorrectos", 
                                                        Response::UNAUTHORIZED);
            }
        } catch (NotFoundException $ex) {
            $this->logger->error("Intento de acceso con usuario inexistente");
            throw new UnauthorizedException("Usuario o contraseña incorrectos",
                                                        Response::UNAUTHORIZED);
        }   
        
        $this->logger->info("Acceso con usuario: ".$_SERVER["PHP_AUTH_USER"]);
        return;
    }
}
