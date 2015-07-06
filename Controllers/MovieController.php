<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Controllers;


use Models\DAO\DAOFactory;

/**
 * Controller para Películas
 *
 * @author alejandro
 */
class MovieController {
    
    /**
     * Devuelve la colección de películas.
     * @param int offset indica el nro de registro donde empieza el set a devolver
     * @param int limit indica la cantidad de registros a devolver
     * @param string field campo por el cual se realiza la búsqueda
     * @param mixed value valor para realizar la búsqueda
     * @return array con la colección de películas
     */
    public function getMovieCollection($offset, $limit, $field, $value) {
        $movieDAO = DAOFactory::getInstance()->getMovieDAO();
        
        return $movieDAO->findMovieCollection($value, $field, $offset, $limit);
        
    }
    
    /**
     * Devuelve un array con las propiedades de la película solicitada.
     * @param int $movieId Identificador de la película
     * @return array con las 
     */
    public function getMovie($movieId) {
        $movieDAO = DAOFactory::getInstance()->getMovieDAO();
        $movie = $movieDAO->findByPK($movieId);
        if (empty($movie)) {
            throw new \Exception('Película inexistente', 404);
        }
        return $movieDAO->modelToArray($movie);
    }
    
    /**
     * Agrega una película
     * @param mixed $moviedata array con la información de una película
     * @return array array con las propiedades de la película. Sin el id.
     */
    public function addMovie($movieData) {
        $movieDAO = DAOFactory::getInstance()->getMovieDAO();
        try {
            return $movieDAO->saveMovie($movieData);

        } catch (\Exception $exc) {
            throw new \Exception("Error al insertar película. ".$exc->getMessage(),
                                                                        400);
        }
    }
    
    /**
     * Actualiza una película
     * @param int $movieId identificador de la película a actualizar
     * @param mixed $movieData array con la información de la película
     */
    public function updateMovie($movieId, $movieData) {
        $movieDAO = DAOFactory::getInstance()->getMovieDAO();
        try {
            $movie = $movieDAO->findByPK($movieId);
            if (empty($movie)) {
                throw new \Exception('Película inexistente', 404);
            }
            return $movieDAO->updateMovie($movie, $movieData);

        } catch (\Exception $exc) {
            throw new \Exception("Error al actualizar película. ".$exc->getMessage(),
                                                                        400);
        }
    }
    
    public function deleteMovie($movieId) {
        $movieDAO = DAOFactory::getInstance()->getMovieDAO();
        try {
            $movie = $movieDAO->findByPK($movieId);
            if (empty($movie)) {
                throw new \Exception('Película inexistente', 404);
            }
            return $movieDAO->delete($movie);

        } catch (\Exception $exc) {
            throw new \Exception("Error al eliminar película. ".$exc->getMessage(),
                                                                        400);
        }
    }
}
