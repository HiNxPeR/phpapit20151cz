<?php

namespace Resources;
use Tonic\Response;
use Controllers\MovieController;

/**
 * Description of MovieResource
 * @uri /movies/:id
 */
class MovieResource extends AuthenticationResource{

    public function __construct(\Tonic\Application $app, \Tonic\Request $request) {
        parent::__construct($app, $request);
        $this->logger = \Logger::getLogger("mylogger");
    }

    /**
     * @method GET
     * @formatOutput
     */
    public function getMovie() {
        $moviectrl = new MovieController();
        $response;
        $responsecode;
        
        try {
            $responsecode = Response::OK;
            $response = $moviectrl->getMovie($this->id);
            $this->logger->info("Obteniendo película: ".$this->id);
        } catch (\Exception $exc) {
            $responsecode = Response::NOTFOUND;
            $response = $this->getErrorArray($exc->getMessage(), $responsecode);
            $this->logger->warn("Obteniendo película: ".$this->id.". ".$exc->getMessage());
        }
        return new Response($responsecode, $response);
    }
    
    /**
     * @validateInput
     * @method PUT
     * @formatOutput
     */
    public function updateMovie() {
        $moviectrl = new MovieController();
        $response;
        $responsecode;
        try{
            $responsecode = Response::OK;
            $response = $moviectrl->updateMovie($this->id, $this->request->data);
            $this->logger->info("Actualizando película: ".$this->id);
        } catch (\Exception $exc) {
            $responsecode = $exc->getCode();
            $response = $this->getErrorArray($exc->getMessage(), $responsecode);
            $this->logger->warn("Actualizando película: ".$this->id.". ".$exc->getMessage());
        }
        return new Response($responsecode, $response);
    }
    
    /**
     * @method DELETE
     * @formatOutput
     */
    public function deleteMovie() {
        $moviectrl = new MovieController();
        $response;
        $responsecode;
        try{
            $responsecode = Response::OK;
            $response = $this->getErrorArray("Película eliminada.", $responsecode);
            $moviectrl->deleteMovie($this->id);
            $this->logger->info("Eliminando película: ".$this->id);
        } catch (\Exception $exc) {
            $responsecode = $exc->getCode();
            $response = $this->getErrorArray($exc->getMessage(), $responsecode);
            $this->logger->warn("Eliminando película: ".$this->id.". ".$exc->getMessage());
        }
        return new Response($responsecode, $response);
    }
    
    /**
     * @method POST
     * @formatOutput
     */
    public function addMovie() {
        $response = $this->getErrorArray("Método No implementado.", Response::METHODNOTALLOWED);
        return new Response(Response::METHODNOTALLOWED, $response);
    }
}
