<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Resources;

use Tonic\Response;
use Controllers\MovieController;

/**
 * Recurso que maneja los pedidos de colecciones de películas
 * @uri /movies
 */
class MovieCollectionResource extends AbstractBaseCollectionResource{
    
    
    public function __construct(\Tonic\Application $app, \Tonic\Request $request) {
        parent::__construct($app, $request);
        $this->logger = \Logger::getLogger("mylogger");
    }

    /**
     * Devuelve la descripción de la colección. Utilizada para armar el objeto
     * Meta de las colecciones.
     * @return string
     */
    protected function getCollectionDescription(){
        return parent::getCollectionDescription("Películas");
    }
    
    /**
     * 
     * @method GET
     * @formatOutput
     * @return Response
     */
    public function getCollection() {
        $this->entityName = "Películas";
        $this->getCollectionRequestParameters();
        $moviectrl = new MovieController();
        $response;
        $responsecode;
        try {   
            $responsecode = Response::OK;
            $collection = $moviectrl->getMovieCollection($this->offset,
                        $this->limit, $this->filterField, $this->filterValue);
            $response["meta"] = $this->getCollectionMetaInformation(
                                                    $collection["totalCount"]);
            $response["items"] = $collection["movies"];
            $this->logger->info("Obteniendo colección de películas");
        } catch (\Exception $exc) {
            $responsecode = Response::NOTFOUND;
            $response = $this->getErrorArray($exc->getMessage(), $responsecode);
            $logger->warn("Obteniendo colección de películas. ".$exc->getMessage());
        }
        return new Response($responsecode, $response);
        
    }
    
    /**
     * @validateInput
     * @method POST
     * @formatOutput
     */
    public function addMovie() {
        $response;
        $responsecode;
        $moviectrl = new MovieController();
        try{
            $responsecode = Response::CREATED;
            $response = $moviectrl->addMovie($this->request->data);
            $this->logger->info("Insertando película: ".$response["title"]);
        } catch (\Exception $exc) {
            $responsecode = $exc->getCode();
            $response = $this->getErrorArray($exc->getMessage(), $responsecode);
            $this->logger->warn("Insertando película. ".$exc->getMessage());
        }
        return new Response($responsecode, $response);
    }
}
